<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use App\Enums\BookingStatus;
use App\Enums\PaymentStatus;

class Booking extends Model
{
    /** @use HasFactory<\Database\Factories\BookingFactory> */
    use HasFactory, HasUlids, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be cast to enums.
     *
     * @var array
     */
    protected $casts = [
        'status' => BookingStatus::class,
        'payment_status' => PaymentStatus::class,
    ];

    /**
     * product
     *
     * @return BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }


    /**
     * hasHostAnotherBooking
     *
     * @param  mixed $activity
     * @return bool
     */
    public static function hasHostAnotherBooking($activity): bool
    {
        $start_time = $activity->start_time;
        $end_time = Carbon::parse($start_time)->addMinutes($activity->product->session_duration);

        return Booking::where('activity_start_time', '<', $end_time)
            ->where('activity_end_time', '>', $start_time)
            ->where('status', BookingStatus::CONFIRMED)
            ->where('activity_start_time', '>', now())
            ->where('activity_id', '!=', $activity->id)
            ->where('host_id', $activity->host->id)
            ->exists();
    }


    /**
     * Generate a unique booking ID.
     *
     * @param string $prefix
     * @return string
     */
    public static function generateUniqueBookingNumber($prefix = 'FIB')
    {
        do {
            $part1 = mt_rand(10000, 99999);       // 5-digit number
            $part2 = mt_rand(1000000, 9999999);   // 7-digit number
            $bookingNumber = "{$prefix}-{$part1}-{$part2}";
        } while (self::where('booking_number', $bookingNumber)->exists());

        return $bookingNumber;
    }

    /**
     * Get the activity associated with the booking.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function activity(): BelongsTo
    {
        return $this->belongsTo(Activity::class, 'activity_id', 'id');
    }

    /**
     * Get the attendees associated with the booking.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attendees(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Attendee::class);
    }

    /**
     * Get the transactions associated with the booking.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transactions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Get the payment method associated with the booking.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id', 'id');
    }

    /**
     * Scope a query to only include upcoming bookings.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUpcoming($query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('host_id', \Auth::id())
            ->where('status', BookingStatus::CONFIRMED)
            ->where('activity_start_time', '>', now())
            ->orderBy('activity_start_time', 'ASC');
    }

    /**
     * Scope a query to only include cancelled bookings.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCancelled($query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('status', BookingStatus::CANCELLED)
            ->orderBy('cancelled_at', 'DESC');
    }

    /**
     * Scope a query to search bookings by attendee email or name
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $searchTerm
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearchByAttendee($query, string $searchTerm): \Illuminate\Database\Eloquent\Builder
    {
        return $query->whereHas('attendees', function ($q) use ($searchTerm) {
            $q->where('email', 'like', "%{$searchTerm}%")
                ->orWhere(DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', "%{$searchTerm}%");
        });
    }

    /**
     * Cancel the booking.
     *
     * @return void
     */
    public function cancel($note = null)
    {
        $this->update([
            'status' => BookingStatus::CANCELLED,
            'payment_status' => PaymentStatus::REFUNDED,
            'cancelled_at' => now(),
            'cancellation_note' => $note,
        ]);
    }
}
