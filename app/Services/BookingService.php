<?php

namespace App\Services;

use App\DTO\BookingAmountDTO;
use App\Enums\BookingStatus;
use App\Enums\PaymentStatus;
use App\Enums\TransactionStatus;
use App\Events\BookingEmailNotification;
use App\Http\Resources\BookingResource;
use App\Models\Activity;
use App\Models\Attendee;
use App\Models\Booking;
use Auth;
use Carbon\Carbon;
use DB;
use App\Http\Resources\ClientSearchResponse;
use App\Exceptions\BookingException;
use App\Models\Transaction;
use App\Enums\TransactionType;
use App\Models\User;
use Hash;
use Illuminate\Http\Client\Response;

class BookingService
{

    protected $activity;
    protected $booking;
    protected $product;
    protected $schedule;


    /**
     * bookWalkIns
     *
     * @return BookingResource
     */
    public function bookWalkIns()
    {

        try {
            // get activity to book 
            $this->activity = $this->findForBooking(request()->activity_id, request()->attendees);
            if (!$this->activity)
                throw new \Exception('activity.not_available_for_bookig', Response::HTTP_NOT_FOUND);

            // check if activity is already booked
            // TODO: accept force param to override the check and cancel other booked activities if force is true
            if (Booking::hasHostAnotherBooking($this->activity))
                throw new \Exception('booking.host_booking_exists', Response::HTTP_UNPROCESSABLE_ENTITY);

            //get product
            $this->product = $this->activity->product;
            if (!$this->product)
                throw new \Exception('messages.not_found', Response::HTTP_NOT_FOUND);


            // check if no_of_seats is not more than available seats
            $no_of_seats = count(request()->attendees);
            $available_seats = $this->activity->seats_available ?? 0;

            if ($no_of_seats > $available_seats)
                throw new \Exception('booking.seats_exceeded', Response::HTTP_UNPROCESSABLE_ENTITY);

            // get schedule if there is any
            $this->schedule = $this->activity->schedule ?? null;

            // create booking - single booking per attendee
            $bookings = $this->createBulkBookings($this->activity, request()->attendees, true);

            // Cancell all other activities of the same host that lies between the booking period
            $this->cancelOtherActivities($this->activity);

            // return response
            return BookingResource::collection($bookings);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * cancelBooking
     *
     * @param  Booking $booking
     * @param  mixed $attendee
     * @param  mixed $notes
     * @return void
     */
    public function cancelBooking(Booking $booking, Attendee $attendee, ?string $notes = null): void
    {
        try {
            DB::beginTransaction();

            // Delete the attendee
            //$booking->attendees()->find($attendee->id)->delete();

            // refund the attendee if single attendee
            $booking->cancel($notes ?? 'Booking cancelled by the host.');

            // record a refund transaction
            $this->refund($booking, $attendee, $notes);


            // update activity seats available and seats booked and status
            $booking->activity->update([
                'seats_available' => $booking->activity->seats_available + 1,
                'seats_booked' => $booking->activity->seats_booked - 1,
                //'status' => 1 // TODO: check if this is needed, if other activities are not cancelled for the same product  or for the same  host or with different product booking is available for the same host and time-range
            ]);

            DB::commit();

            // dispatch a cancellation event so listeners can send emails
            event(new \App\Events\BookingCancelled($booking, $attendee->client));
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * refund
     *
     * @param  Booking $booking
     * @param  Attendee $attendee
     * @param  string|null $notes
     * @return Transaction
     */
    public function refund(Booking $booking, Attendee $attendee, ?string $notes = null): Transaction
    {
        try {
            return $booking->transactions()->create([
                'client_id' => $attendee->client_id,
                'host_id' => $booking->host_id,
                'transaction_type' => TransactionType::REFUND,
                'transaction_status' => TransactionStatus::COMPLETED,
                'payment_method_id' => $booking->payment_method_id,
                'amount' => $booking->total_amount,
                'paid_at' => Carbon::now(),
                'notes' => $notes ?? 'Booking cancelled by the host.',
            ]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * cancelBookingByActivity
     *
     * @param  mixed $activity
     * @param  mixed $notes
     * @return void
     */
    public function cancelBookingByActivity(Activity $activity, ?string $notes = null): void
    {
        // get bookings for the activity
        $bookings = $activity->bookings()
            ->where('status', BookingStatus::CONFIRMED)
            ->with('attendees')->get();

        if (!$bookings || $bookings->isEmpty()) return;

        // cancel each booking and refund the attendees
        foreach ($bookings as $booking) {
            foreach ($booking->attendees as $attendee) {

                // cancel the booking
                $booking->cancel($notes ?? 'Booking cancelled by the host.');

                // record a refund transaction
                $this->refund($booking, $attendee, $notes);

                // dispatch a cancellation event so listeners can send emails
                event(new \App\Events\BookingCancelled($booking, $attendee->client));
            }
        }
    }

    /**
     * cancelOtherActivities
     *
     * @param  mixed $booking
     * @return void
     */
    public function cancelOtherActivities(Activity $activity): void
    {
        $bookingStart = $activity->start_time;
        $bookingEnd = Carbon::parse($activity->start_time)->addMinutes($activity->product->session_duration);
        // get actual host user id (hosts table links to a user)
        $hostUserId = $activity->host->id;

        // Disable all activities  overlapping the booking period
        $otherActivities = Activity::where('id', '!=', $activity->id)
            ->where('start_time', '<', $bookingEnd)
            ->where('end_time', '>', $bookingStart)
            ->get();

        // loop through each overlapping activity
        foreach ($otherActivities as $activity) {
            $activity->load('product');
            // mark the activity as inactive
            $activity->markAsInactive();

            // refund each confirmed booking for this activity and record a transaction
            $activity->bookings()->where('status', BookingStatus::CONFIRMED)->get()->each(function ($otherBooking) use ($activity, $hostUserId) {
                // restore product seats
                $activity->product->increment('no_of_slots', $otherBooking->seats_booked);
                // restore activity seats_booked count
                $activity->decrement('seats_booked', $otherBooking->seats_booked);

                // mark booking as refunded/cancelled
                $otherBooking->cancel('Automatically refunded because the activity was disabled.');

                // record a refund transaction
                Transaction::create([
                    'booking_id' => $otherBooking->id,
                    'client_id' => $otherBooking->client_id,
                    'host_id' => $hostUserId,
                    'transaction_type' => TransactionType::REFUND,
                    'transaction_status' => TransactionStatus::COMPLETED,
                    'payment_method_id' => $otherBooking->payment_method_id,
                    'amount' => $otherBooking->total_amount,
                    'paid_at' => Carbon::now(),
                    'notes' => 'Automatically refunded because the activity was disabled.',
                ]);

                // dispatch a cancellation event so listeners can send emails
                event(new \App\Events\BookingCancelled($otherBooking));
            });
        }
    }

    /**
     * createBooking
     *
     * @param int $no_of_seats
     * @param bool $is_walk_in
     * @return Booking
     */
    public function createBooking(Activity $activity, int $no_of_seats = 1, bool $is_walk_in = false): Booking
    {
        try {
            $booking = Booking::create([
                'booking_number' => Booking::generateUniqueBookingNumber(),
                'activity_id' => $activity->id,
                'product_id' => $activity->product_id,
                'created_by' => Auth::id(), // BookedBY
                'host_id' => $activity->host->id, // host of the activity

                'seats_booked' => $no_of_seats,
                'product_title' => $activity->product->title,
                'product_type' => $activity->product->productType->title,
                'activity_start_time' => $activity->start_time,
                'activity_end_time' => Carbon::parse($activity->start_time)->addMinutes($activity->product->session_duration),

                'payment_method_id' => request()->payment_method_id,
                'payment_status' => PaymentStatus::PAID,

                'is_walk_in' => $is_walk_in,

                'confirmed_at' => Carbon::now(),

                'status' => BookingStatus::CONFIRMED,
                'notes' => request()->notes,
                ...$this->calculateBooking(is_walk_in: true)->toArray()
            ]);

            // Create transaction record
            $booking->transactions()->create([
                'client_id' => Auth::id(),
                'host_id' => $activity->user_id,
                'transaction_type' => TransactionType::PAYMENT,
                'transaction_status' => TransactionStatus::COMPLETED,
                'payment_method_id' => request()->payment_method_id,
                'amount' => $booking->total_amount,
                'paid_at' => Carbon::now()
            ]);

            return $booking;
        } catch (\Exception $e) {
            throw $e;
        }
    }


    /**
     * Calculate booking amounts including price per seat, subtotal, tax, discount, and total
     *
     * @param bool $is_walk_in Whether this is a walk-in booking
     * @return BookingAmountDTO
     * @throws \Exception If product service fails or calculation error occurs
     */
    public function calculateBooking(bool $is_walk_in = false): BookingAmountDTO
    {
        try {
            if (!$this->product) {
                throw new \Exception('Product not found for booking calculation', Response::HTTP_NOT_FOUND);
            }

            $productService = new ProductService();
            $no_of_seats = 1; //request()->no_of_seats ?? 0;

            if ($no_of_seats <= 0) {
                throw new \Exception('Invalid number of seats for booking calculation', Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $price_per_seat = $productService->getPricePerSeat($this->product, $is_walk_in);
            $sub_total = $price_per_seat * $no_of_seats;

            // TODO: Implement tax calculation when needed
            $tax_amount = 0; // $productService->getTaxAmount($this->product, $sub_total);

            // TODO: Implement discount calculation when needed
            $discount_amount = 0; // $productService->getDiscountAmount($this->product, $sub_total);

            $total_amount = $sub_total + $tax_amount - $discount_amount;

            return new BookingAmountDTO(
                price_per_seat: $price_per_seat,
                sub_total: $sub_total,
                tax_amount: $tax_amount,
                discount_amount: $discount_amount,
                total_amount: $total_amount,
            );
        } catch (\Exception $e) {
            throw new \Exception('Failed to calculate booking amounts: ' . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * createBulkBookings
     *
     * @param  mixed $booking
     * @param  mixed $attendees
     * @param  bool $is_walk_in
     * @return array
     */
    public function createBulkBookings(Activity $activity, array $attendees, bool $is_walk_in = false): array
    {
        try {
            if (!is_array($attendees) || count($attendees) == 0)
                throw new \Exception('Attendees not found', 404);

            // create bookings
            return array_map(function ($data) use ($activity, $is_walk_in) {
                DB::beginTransaction();
                // create booking
                $booking = $this->createBooking($activity, 1, $is_walk_in);

                // create attendee
                $this->createAttendee($booking, $data);

                // Update activity available seats
                $activity->decrement('seats_available', 1);

                // increment seats booked
                $activity->increment('seats_booked', $booking->seats_booked);

                DB::commit();

                // Dispatch email notification event after transaction is committed
                event(new BookingEmailNotification($booking));

                return $booking;
            }, $attendees);
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
    /**
     * createAttendees
     *
     * @param  mixed $booking
     * @param  mixed $attendees
     * @return void
     */
    public function createAttendees($booking, $attendees): void
    {
        try {
            if (!is_array($attendees) || count($attendees) == 0)
                throw new \Exception('Attendees not found', 404);

            // create booking attendees
            foreach ($attendees as $data) {
                $this->createAttendee($booking, $data);
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * createAttendee
     *
     * @param  mixed $booking
     * @param  mixed $data
     * @return Attendee
     */
    public function createAttendee($booking, $data): Attendee
    {
        try {
            // create user if not exists
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                    'mobile_number' => $data['mobile_no'] ?? null,
                    'password' => Hash::make(\Str::random(10)),
                ]
            );

            // assign client role
            $user->assignRole('client');

            // create client record
            $user->hosts()->attach($booking->activity->host->id);

            // TODO: Send email to client and assign client role

            // Create attendee record
            return Attendee::create([
                'booking_id'       => $booking->id,
                'activity_id'      => $booking->activity->id,
                'client_id'         => $user->id,
                'host_id'         => $booking->activity->host->id,
                'is_lead_attendee' => $data['is_lead_attendee'] ?? 0,
                'notes'            => $data['notes'] ?? null,
                'first_name'       => $user->first_name,
                'last_name'        => $user->last_name,
                'email'            => $user->email,
                'mobile_number'    => $user->mobile_number,
            ]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Search bookings by client email or name
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     * @throws \Exception
     */
    public function searchByClient()
    {
        try {
            $searchTerm = strtolower(request()->input('s'));
            $perPage = request()->input('per_page', 15);

            $clients = User::select(
                'users.id as client_id',
                'users.code',
                'users.first_name',
                'users.last_name',
                'users.email',
                'users.mobile_number',
                DB::raw('COUNT(DISTINCT bookings.activity_id) as total_activities_booked')
            )
                ->where(function ($q) use ($searchTerm) {
                    $q->where('users.email', 'like', "%{$searchTerm}%")
                        ->orWhere(DB::raw("lower(CONCAT(users.first_name, ' ', users.last_name))"), 'like', "%{$searchTerm}%");
                })
                ->join('attendees', 'attendees.client_id', '=', 'users.id')
                ->join('bookings', function ($join) {
                    $join->on('attendees.booking_id', '=', 'bookings.id')
                        ->where('bookings.status', BookingStatus::CONFIRMED);
                })
                ->groupBy('users.id', 'users.code', 'users.first_name', 'users.last_name', 'users.email', 'users.mobile_number')
                ->with(['attendees.booking' => function ($q) {
                    $q->with(['activity', 'paymentMethod'])
                        ->where('status', BookingStatus::CONFIRMED);
                }])
                ->paginate($perPage);

            return ClientSearchResponse::collection($clients);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Find activity for booking and validate attendee availability
     *
     * @param string $id
     * @param array $attendees
     * @return Activity|null
     * @throws BookingException
     */
    public function findForBooking(string $id, array $attendees = []): Activity|null
    {
        $activity = Activity::where('id', $id)
            ->availableForBooking()
            ->first();

        if (!$activity) {
            return null;
        }

        // Get attendees who already have a booking for this activity
        $existingAttendees = User::whereIn('email', array_column($attendees, 'email'))
            ->whereHas('attendees.booking', function ($query) use ($activity) {
                $query->where('activity_id', $activity->id)
                    ->where('status', BookingStatus::CONFIRMED);
            })
            ->select('id', 'first_name', 'last_name', 'email')
            ->get();

        if ($existingAttendees->isNotEmpty()) {
            $attendeeList = $existingAttendees->map(function ($attendee) {
                return [
                    'id' => $attendee->id,
                    'name' => $attendee->first_name . ' ' . $attendee->last_name,
                    'email' => $attendee->email
                ];
            })->toArray();

            throw new BookingException('Some attendees have already booked this activity', 422, [
                'attendees' => $attendeeList
            ]);
        }

        return $activity;
    }

    /**
     * Delete an attendee from a booking
     *
     * @param Booking $booking
     * @param string $client_id
     * @param string $activity_id
     * @return void
     * @throws BookingException
     */
    public function deleteAttendee(Booking $booking, string $client_id, string $activity_id): void
    {
        try {
            $attendee = $booking->attendees()->where('client_id', $client_id)
                ->where('bookings.activity_id', $activity_id)
                ->join('bookings', function ($join) {
                    $join->on('attendees.booking_id', '=', 'bookings.id')
                        ->where('bookings.status', BookingStatus::CONFIRMED);
                })
                ->first();

            // check if attendee exists
            if (!$attendee) {
                throw new BookingException('Attendee not found', 404);
            }

            // cancel the booking
            $this->cancelBooking($booking, $attendee, notes: 'Booking cancelled because the attendee was deleted.');

            $attendee->delete(); // softdelete attendee
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
