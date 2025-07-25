<?php

namespace App\Http\Controllers;

use App\Exceptions\ActivityConflictException;
use App\Http\Requests\Activity\ActivityCancelRequest;
use App\Http\Requests\Activity\ActivityCreateRequest;
use App\Http\Requests\Activity\ActivityGetRequest;
use App\Http\Requests\Activity\ActivityUpdateRequest;
use App\Http\Requests\Activity\SetTimeOffRequest;
use App\Http\Requests\Activity\UpdateTimeOffRequest;
use App\Http\Resources\ActivityResource;
use App\Http\Resources\UpcomingActivityResource;
use App\Models\Activity;
use App\Services\ActivityService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ActivityController extends Controller
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
        $this->service = new ActivityService();
    }


    /**
     * Get Activities
     *
     * @param  mixed $request
     * @return JsonResponse
     */
    public function index(ActivityGetRequest $request): JsonResponse
    {
        try {
            return response()->success('messages.success', $this->service->get($request));
        } catch (\Exception $e) {
            return response()->error($e->getMessage(), null, $e->status ?? $e->getCode());
        }
    }


    /**
     * Post Activity
     *
     * @param  mixed $request
     * @return JsonResponse
     */
    public function store(ActivityCreateRequest $request)
    {
        try {
            return response()->success('messages.created', $this->service->create($request), null, 201);
        } catch (ValidationException $e) {
            return response()->error(__('validation.failed'), $e->errors(), $e->status ?? $e->getCode());
        } catch (Exception $e) {
            return response()->error($e->getMessage(), null, $e->getCode());
        }
    }


    /**
     * Get Activity Detail
     *
     * @param  mixed $activity
     * @return JsonResponse
     */
    public function show(Activity $activity): JsonResponse
    {
        try {

            if (!\Auth::user()->canAccess($activity->user_id))
                throw new Exception("You are not authorized to access this resource", 403);

            return response()->success('messages.success', new ActivityResource($activity));
        } catch (Exception $e) {
            return response()->error($e->getMessage(), null, $e->getCode());
        }
    }


    /**
     * Update Activity
     *
     * @param  mixed $request
     * @param  mixed $activity
     * @return void
     */
    public function update(ActivityUpdateRequest $request, Activity $activity)
    {
        try {

            if (!\Auth::user()->canAccess($activity->user_id))
                throw new Exception("You are not authorized to access this resource", 403);

            return response()->success('messages.updated', $this->service->update($request, $activity));
        } catch (ActivityConflictException $e) {
            return response()->error($e->getMessage(), $e->getData(), $e->getCode());
        } catch (Exception $e) {
            return response()->error($e->getMessage(), null, $e->getCode());
        }
    }


    /**
     * Delete Activity
     *
     * @param  mixed $activity
     * @return void
     */
    public function destroy(Activity $activity, Request $request)
    {
        try {

            if (!\Auth::user()->canAccess($activity->user_id))
                throw new Exception("You are not authorized to access this resource", 403);

            //TODO: if activity is booked, refund
            //TODO: rollback seats booked and available seats in the activity
            return response()->success('messages.deleted', $this->service->delete($activity));
        } catch (Exception $e) {
            return response()->error($e->getMessage(), null, $e->getCode());
        }
    }


    /**
     * setTimeOff
     *
     * @param  SetTimeOffRequest $request
     * @return JsonResponse
     */
    public function setTimeOff(SetTimeOffRequest $request): JsonResponse
    {
        try {
            return response()->success('messages.created', $this->service->setTimeOff($request), null, 201);
        } catch (Exception $e) {
            return response()->error($e->getMessage(), null, $e->status ?? $e->getCode());
        }
    }


    /**
     * deleteTimeOff
     *
     * @param  mixed $timeoff
     * @return void
     */
    public function deleteTimeOff(Activity $timeoff)
    {
        try {

            if (!\Auth::user()->canAccess($timeoff->user_id))
                throw new Exception("You are not authorized to access this resource", 403);

            $this->service->cancelTimeoff($timeoff);
            return response()->success('messages.deleted', null);
        } catch (Exception $e) {
            return response()->error($e->getMessage(), null, $e->getCode());
        }
    }

    /**
     * updateTimeOff
     *
     * @param  UpdateTimeOffRequest $request
     * @param  Activity $timeoff
     * @return JsonResponse
     */
    public function updateTimeOff(UpdateTimeOffRequest $request, Activity $timeoff): JsonResponse
    {
        try {
            if (!\Auth::user()->canAccess($timeoff->user_id)) {
                throw new Exception("You are not authorized to access this resource", 403);
            }

            return response()->success('messages.updated', $this->service->updateTimeOff($request, $timeoff));
        } catch (Exception $e) {
            return response()->error($e->getMessage(), null, $e->status ?? $e->getCode());
        }
    }


    /**
     * cancel
     *
     * @param  ActivityCancelRequest $request
     * @return JsonResponse
     */
    public function cancel(ActivityCancelRequest $request): JsonResponse
    {
        try {
            return response()->success('activity.cancelled', $this->service->cancel($request));
        } catch (Exception $e) {
            return response()->error($e->getMessage(), null, $e->getCode());
        }
    }


    /**
     * upcoming
     *
     * @param  Request $request
     * @return JsonResponse
     */
    public function upcoming(Request $request): JsonResponse
    {
        try {
            $upcomingActivities = Activity::upcoming()->paginate($request->per_page ?? 15);
            return response()->success('messages.success', UpcomingActivityResource::collection($upcomingActivities));
        } catch (Exception $e) {
            return response()->error($e->getMessage(), null, $e->getCode());
        }
    }


    /**
     * attendees
     *
     * @param  mixed $activity
     * @return JsonResponse 
     */
    public function attendees(Activity $activity): JsonResponse
    {
        try {
            return response()->success('messages.success', $this->service->attendees($activity));
        } catch (Exception $e) {
            throw $e;
            return response()->error($e->getMessage(), null, $e->getCode());
        }
    }
}
