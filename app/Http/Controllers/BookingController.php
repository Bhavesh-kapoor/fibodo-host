<?php

namespace App\Http\Controllers;

use App\Http\Requests\Booking\BookingCreateRequest;
use App\Http\Resources\BookingResource;
use App\Services\BookingService;
use Illuminate\Http\JsonResponse;
use App\Models\Booking;
use Illuminate\Http\Request;
use App\Http\Requests\Booking\SearchByClientRequest;
use App\Exceptions\BookingException;
use App\Http\Requests\Booking\DeleteAttendeeRequest;
use App\Http\Resources\AttendeeResource;
use Exception;
use Illuminate\Support\Facades\Response;

class BookingController extends Controller
{

    protected $service;

    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        // initialize the service
        $this->service = new BookingService();
    }


    /**
     * getBookingDetails
     *
     * @param Booking $booking
     * @return JsonResponse
     */
    public function show(Booking $booking): JsonResponse
    {
        try {
            if (!\Auth::user()->canAccess($booking->host_id))
                throw new Exception('messages.unauthorized', 403);

            $booking->load(['transactions']);
            return Response::success('messages.success', new BookingResource($booking));
        } catch (BookingException $e) {
            return Response::error($e->getMessage(), $e->getData(), $e->getCode());
        } catch (\Exception $e) {
            return Response::error($e->getMessage(), null, $e->getCode() ?: 500);
        }
    }

    /**
     * bookWalkIns
     *
     * @param  mixed $request
     * @return JsonResponse
     */
    public function bookWalkIns(BookingCreateRequest $request): JsonResponse
    {
        try {
            return Response::success('messages.created', $this->service->bookWalkIns(), null, 201);
        } catch (BookingException $e) {
            return Response::error($e->getMessage(), $e->getData(), $e->getCode());
        } catch (\Exception $e) {
            return Response::error($e->getMessage(), null, $e->getCode() ?: 500);
        }
    }

    /**
     * upcomingBookings
     *
     * @param  Request $request
     * @return JsonResponse
     */
    public function upcoming(Request $request): JsonResponse
    {
        try {
            $bookings = Booking::upcoming()
                ->with(['activity', 'attendees', 'transactions', 'paymentMethod'])
                ->paginate($request->per_page);

            return Response::success('messages.success', BookingResource::collection($bookings));
        } catch (\Exception $e) {
            return Response::error($e->getMessage(), null, $e->getCode());
        }
    }

    /**
     * cancelledBookings
     *
     * @param  Request $request
     * @return JsonResponse
     */
    public function cancelled(Request $request): JsonResponse
    {
        try {
            $bookings = Booking::cancelled()
                ->with(['activity', 'attendees', 'transactions', 'paymentMethod'])
                ->paginate($request->per_page);

            return Response::success(
                'messages.success',
                BookingResource::collection($bookings),
                null,
                200
            );
        } catch (\Exception $e) {
            return Response::error($e->getMessage(), null, $e->getCode());
        }
    }

    /**
     * searchByClient
     *
     * @param SearchByClientRequest $request
     * @return JsonResponse
     */
    public function searchByClient(SearchByClientRequest $request): JsonResponse
    {
        try {
            return Response::success(
                'messages.success',
                $this->service->searchByClient()
            );
        } catch (\Exception $e) {
            return Response::error($e->getMessage(), null, $e->getCode());
        }
    }

    /**
     * deleteAttendee
     *
     * @param DeleteAttendeeRequest $request
     * @param Booking $booking
     * @return JsonResponse
     */
    public function deleteAttendee(DeleteAttendeeRequest $request, Booking $booking): JsonResponse
    {
        try {
            $this->service->deleteAttendee($booking, $request->client_id, $request->activity_id);
            return Response::success('messages.deleted', null, null, 200);
        } catch (BookingException $e) {
            return Response::error($e->getMessage(), $e->getData(), $e->getCode());
        } catch (\Exception $e) {
            return Response::error($e->getMessage(), null, $e->getCode());
        }
    }
}
