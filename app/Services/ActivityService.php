<?php

namespace App\Services;

use App\Enums\BookingStatus;
use App\Exceptions\ActivityConflictException;
use App\Http\Requests\Activity\ActivityCancelRequest;
use App\Http\Requests\Activity\ActivityCreateRequest;
use App\Http\Requests\Activity\ActivityGetRequest;
use App\Http\Requests\Activity\ActivityUpdateRequest;
use App\Http\Requests\Activity\SetTimeOffRequest;
use App\Http\Requests\Activity\UpdateTimeOffRequest;
use App\Http\Resources\ActivityResource;
use App\Http\Resources\AttendeeResource;
use App\Models\Activity;
use App\Models\Attendee;
use App\Models\Booking;
use App\Models\Product;
use App\Models\Schedules\ScheduleBreak;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class ActivityService
{

    /**
     * get
     *
     * @param  mixed $request
     * @return ResourceCollection
     */
    public function get(ActivityGetRequest $request): ResourceCollection
    {
        try {

            // collect requested params
            $start_time = $request->input('start_time');
            $end_time = $request->input('end_time');
            $product_id = $request->input('product_id');
            $per_page = request()->input('per_page', null);

            // query activities
            $activities = $request->user()->activities()
                ->when($start_time, fn($q) => $q->where('end_time', '>=', $start_time))
                ->when($end_time, fn($q) => $q->where('start_time', '<=', $end_time))
                ->when($product_id, function ($query) use ($product_id) {
                    return $query->whereHas('product', function ($q) use ($product_id) {
                        $q->where('products.id', $product_id);
                    });
                })
                ->when($request->has('is_break'), fn($q) => $q->where('is_break', $request->input("is_break", 0)))
                ->when($request->has('is_time_off'), fn($q) => $q->where('is_time_off', $request->input("is_time_off", 0)))
                ->leftJoin('products', 'products.id', '=', 'activities.product_id')
                ->whereNull('products.deleted_at')
                ->selectRaw("
                    activities.id as id, 
                    activities.start_time, 
                    activities.end_time, 
                    activities.user_id as user_id, 
                    activities.schedule_id as schedule_id, 
                    activities.schedule_day_id as schedule_day_id, 

                    activities.status as status, 
                    activities.is_break as is_break, 
                    activities.is_time_off as is_time_off, 
                    activities.title as title, 
                    activities.note as note, 
                    activities.seats_booked as seats_booked,
                    activities.seats_available as seats_available,
                    activities.created_at as created_at,
                    
                    products.id as product_id,
                    products.title as product_title,
                    products.sub_title as product_sub_title,
                    products.session_duration as product_session_duration,
                    
                    products.price as price,

                    products.is_age_sensitive as is_age_sensitive,
                    products.junior_price as junior_price,
                    products.adult_price as adult_price,
                    products.senior_price as senior_price,

                    products.is_walk_in_pricing as is_walk_in_pricing,
                    products.walk_in_price as walk_in_price,
                    
                    products.is_walk_in_age_sensitive as is_walk_in_age_sensitive,
                    products.walk_in_junior_price as walk_in_junior_price,
                    products.walk_in_adult_price as walk_in_adult_price,
                    products.walk_in_senior_price as walk_in_senior_price,

                    products.is_special_pricing as is_special_pricing,
                    products.multi_attendee_price as multi_attendee_price,
                    products.all_space_price as all_space_price
                    
                    ")
                ->active();

            // setup acitvitiy count
            $activitiesCount = $per_page ?? (clone $activities)->count();

            $activities->orderBy('activities.start_time', 'ASC')
                ->orderBy('products.id', 'ASC');

            // paginate activities
            return ActivityResource::collection($activities->paginate($activitiesCount));
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * getGrouped
     *
     * @param  mixed $request
     * @return ResourceCollection
     */
    public function getGrouped(ActivityGetRequest $request): ResourceCollection
    {
        try {

            // collect requested params
            $start_time = $request->input('start_time');
            $end_time = $request->input('end_time');
            $product_id = $request->input('product_id');
            $per_page = request()->input('per_page', null);

            // query activities
            $activities = $request->user()->activities()
                ->when($start_time, fn($q) => $q->where('end_time', '>=', $start_time))
                ->when($end_time, fn($q) => $q->where('start_time', '<=', $end_time))
                ->when($product_id, function ($query) use ($product_id) {
                    return $query->whereHas('product', function ($q) use ($product_id) {
                        $q->where('products.id', $product_id);
                    });
                })
                ->when($request->has('is_break'), fn($q) => $q->where('is_break', $request->input("is_break", 0)))
                ->when($request->has('is_time_off'), fn($q) => $q->where('is_time_off', $request->input("is_time_off", 0)))
                ->leftJoin('products', 'products.id', '=', 'activities.product_id')
                ->whereNull('products.deleted_at')
                ->selectRaw("
                    MIN(activities.id) as id, 
                    activities.start_time, 
                    activities.end_time, 
                    MIN(activities.user_id) as user_id, 
                    MIN(activities.product_id) as product_id, 
                    MIN(activities.schedule_id) as schedule_id, 
                    MIN(activities.schedule_day_id) as schedule_day_id, 

                    MIN(activities.status) as status, 
                    MIN(activities.is_break) as is_break, 
                    MIN(activities.is_time_off) as is_time_off, 
                    MIN(activities.title) as title, 
                    MIN(activities.note) as note, 
                    MIN(activities.created_at) as created_at,
                    ARRAY_AGG(DISTINCT products.id) as product_ids, 
                    {$this->getJsonBagOfProducts()} ")
                ->active();

            // setup acitvitiy count
            $activitiesCount = $per_page ?? (clone $activities)->count();
            // append groupby for real query
            $activities->groupBy('activities.start_time', 'activities.end_time')
                ->orderBy('activities.start_time', 'ASC')
                ->count();

            // paginate activities
            return ActivityResource::collection($activities->paginate($activitiesCount));
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function getJsonBagOfProducts()
    {

        return '
        jsonb_agg(
            DISTINCT jsonb_build_object(
                \'id\', products.id, 
                \'title\', products.title,
                \'title\', products.title,
                \'no_of_slots\', products.no_of_slots,
                \'price\', products.price,
                \'junior_price\', products.junior_price,
                \'adult_price\', products.adult_price,
                \'senior_price\', products.senior_price,
                \'walk_in_price\', products.walk_in_price,
                \'walk_in_junior_price\', products.walk_in_junior_price,
                \'walk_in_adult_price\', products.walk_in_adult_price,
                \'walk_in_senior_price\', products.walk_in_senior_price,
                \'multi_attendee_price\', products.multi_attendee_price,
                \'all_space_price\', products.all_space_price
                )
            ) 
        FILTER (WHERE products.id IS NOT NULL) as products';
    }


    /**
     * create
     * TODO: Implement QUEUE JOBS
     * @param ActivityCreateRequest $request    
     * @return ActivityResource
     */
    public function create(ActivityCreateRequest $request)
    {
        try {
            $start_time = $request->input('start_time');
            $end_time = $request->input('end_time');
            $product_ids = $request->input('product_ids');

            $starts_at = Carbon::parse($start_time);
            $ends_at = Carbon::parse($end_time);
            $is_force = $request->input('force', false);

            // chcek if all the products with ids exists and published
            $products = (new ProductService)->validateProducts($product_ids);

            // get product schdules
            [$productIdsWithSchedules, $productIdsWithoutSchedule] = $this->filterProductIdBySchedule($products);

            // if product has schedules 
            if (!empty($productIdsWithSchedules) && $start_time && $end_time) {
                return $this->scheduleActivities($productIdsWithSchedules, $starts_at, $ends_at, $is_force);
            }
            $productIdsWithoutSchedule = empty($end_time) ? $productIdsWithSchedules : $productIdsWithoutSchedule;
            // create activities which don't have any schedule
            return $this->createActivities($productIdsWithoutSchedule, $starts_at, $is_force);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * filterProductIdBySchedule
     * @param $products
     * @return array
     */
    public function filterProductIdBySchedule($products): array
    {
        $productIdsWithSchedules = [];
        $productIdsWithoutSchedule = [];
        foreach ($products as $product) {
            if ($product->has_published_schedule) {
                $productIdsWithSchedules[] = $product->id;
            } else {
                $productIdsWithoutSchedule[] = $product->id;
            }
        }
        // return product ids 
        return [$productIdsWithSchedules, $productIdsWithoutSchedule];
    }



    /**
     * createActivities
     *
     * @param  array $product_ids
     * @param  Carbon $starts_at
     * @param  bool $is_force
     * @return ResourceCollection|null
     */
    public function createActivities(array $product_ids, Carbon $starts_at, bool $is_force): ResourceCollection|null
    {
        $activitiesData = [];

        // validate activities
        $this->validateActivities($product_ids, $starts_at, $is_force);

        // create activities 
        foreach ($product_ids as $product_id) {
            $product = Product::published()->findOrFail($product_id);
            $end_time = $starts_at->copy()->addMinutes($product->session_duration);

            if ($is_force) {
                // get all the bookings and cancel them if force is true
                $bookedActivities = Activity::getBookedActivities($starts_at, $end_time);
                if ($bookedActivities && $bookedActivities->count()) {
                    foreach ($bookedActivities as $activity) {
                        // get the activity
                        $activity = Activity::find($activity['id']);

                        // cancel booking by activity
                        (new BookingService())->cancelBookingByActivity($activity);

                        // delete the activity
                        $activity->delete();
                    }
                }

                // cancel all the activities in the time range if force is true
                Activity::cancelActivities([$product_id], $starts_at, $end_time);
            }

            $activitiesData[] = [
                'product_id' => $product_id,
                'start_time' => $starts_at,
                'duration' => $product->session_duration,
                'end_time' => $end_time,
                'status' => 1,
                'seats_available' => $product->no_of_slots,
            ];
        }

        if (!count($activitiesData)) return null;

        return ActivityResource::collection(Auth::user()->activities()->createMany($activitiesData));
    }


    /**
     * validateActivities
     *
     * @param  array $product_ids
     * @param  Carbon $starts_at
     * @param  bool $is_force
     * @return bool
     */
    public function validateActivities(array $product_ids, Carbon $starts_at, bool $is_force): bool
    {
        try {

            if ($is_force) return true;

            $errors = [];
            foreach ($product_ids as $product_id) {
                $product = Product::published()->findOrFail($product_id);
                $end_time = $starts_at->copy()->addMinutes($product->session_duration);

                // get booked activities
                $bookedActivities = Activity::getBookedActivities($starts_at->toDateTimeString(), $end_time->toDateTimeString());
                $bookingCount = $bookedActivities?->count() ?? 0;

                // check if there are any timeOff errors
                if (Activity::withinTimeOffRange($starts_at->toDateTimeString(), $end_time->toDateTimeString(), $product_id)) {
                    $errors['timeoff'][] = __("activity.conflict_with_timeoff", ['product' => $product->title]);
                }

                // check if there are any break errors
                if (Activity::withinBreakRange($starts_at->toDateTimeString(), $end_time->toDateTimeString(), $product_id)) {
                    $errors['break'][] = __("activity.conflict_with_break", ['product' => $product->title]);
                }

                // check if there are any conflicts
                if (Activity::hasConflicts($starts_at->toDateTimeString(), $end_time->toDateTimeString(), product_id: $product_id)) {
                    $errors['overlap'][] = __("activity.conflict");
                }

                // booking conflicts
                if ($bookingCount) {
                    $errors['booking'][] = __("activity.conflict_with_bookings");
                }
            }

            if (!empty($errors)) {
                throw ValidationException::withMessages($errors);
            }

            return true;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * scheduleActivities
     *
     * @param  mixed $product_ids
     * @param  mixed $starts_at
     * @param  mixed $ends_at
     * @param  mixed $is_force
     * @return void
     */
    public function scheduleActivities($product_ids, $starts_at, $ends_at, $is_force)
    {
        try {

            // get booked activities
            $bookedActivities = Activity::getBookedActivities(request()->start_time, request()->end_time);
            if ($bookedActivities && $bookedActivities->count() && !$is_force) {
                // if there are booked activities, or activities are under the same time range, throw an error
                throw new ActivityConflictException(__("activity.conflict_with_bookings"), Response::HTTP_CONFLICT, $bookedActivities->toArray());
            }

            // get the active schedules
            $schedules = ScheduleService::getSchedulesForActivity($product_ids, $starts_at->format("H:i"), $ends_at->format("H:i"));

            if (!$schedules->count())
                throw ValidationException::withMessages(['schedules' => 'Products does not have any schedules']);

            // Loop through each corresponding date 
            while ($starts_at < $ends_at) {

                foreach ($schedules->where('day_of_week', ($starts_at->isoWeekday() % 7)) as $schedule) {

                    // start &  end time 
                    $start_time = $day_start = $this->getTime($schedule->day_start);
                    $day_end = $this->getTime($schedule->day_end);

                    if (!$day_start || !$day_end) {
                        Log::channel('activity')->error("Activity creation failed due to start or end time is not defined.", $schedule->toArray());
                        continue; // continue to next itteration 
                    }

                    if (!$schedule->duration || !$schedule->recurres_in) {
                        Log::channel('activity')->error("Activity creation failed due to duration or recurres minutes not defined.", $schedule->toArray());
                        continue; // continue to next itteration 
                    }

                    // loop through schdule start and end time 
                    while ($start_time <= $day_end) {

                        $end_time = $start_time->copy()->addMinutes((int) $schedule->recurres_in); // $schedule->duration

                        // only create if within the hours requested in create REQUEST, 
                        if ($this->isWithinRequestedTime($starts_at, $ends_at, $start_time, $end_time)) {

                            $activity = [
                                'user_id' => Auth::id(),
                                'product_id' => $schedule->product_id,
                                'schedule_id' => $schedule->schedule_id,
                                'schedule_day_id' => $schedule->schedule_day_id,
                                'start_time' => $starts_at->copy()->setTimeFromTimeString($start_time->toTimeString()),
                                'end_time' => $starts_at->copy()->setTimeFromTimeString($end_time->toTimeString()),
                                'status' => 1,
                                'is_break' => 0,
                                'is_time_off' => 0,
                                'seats_available' => $schedule->no_of_slots ?? 0,
                            ];

                            // if timeOff mark status = 0
                            if (Activity::withinTimeOffRange($activity['start_time'], $activity['end_time'])) {
                                $activity['status'] = 0;
                            }

                            // post break if any
                            if ($this->postBreak($schedule, $start_time, $end_time, $starts_at, $activity['status'])) { // post break even if timeoff exists
                                $activity['status'] = 0;
                            }

                            // post activity
                            $this->postActivity($activity);
                        }

                        // set next start_time
                        $start_time = $this->calculateStartTime($start_time, $schedule);
                    }
                };

                // increment day by 1 
                $starts_at = $starts_at->copy()->addDays(1);
            }

            // if there are booked activities, refund all the booked seats
            if ($bookedActivities && $bookedActivities->count() && $is_force) {
                foreach ($bookedActivities as $activity) {
                    (new BookingService())->cancelBookingByActivity(Activity::find($activity['id']));
                }
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }


    /**
     * isWithinRequestedTime
     * validates the activities must be created within the hours requested in create REQUEST. 
     *
     * @param  mixed $start_time
     * @param  mixed $end_time
     * @return bool
     */
    public function isWithinRequestedTime($starts_at, $ends_at, $start_time, $end_time): bool
    {
        if ($starts_at == Carbon::parse(request()->start_time))
            return $starts_at->format('H:i') <= $start_time->format('H:i');

        if ($starts_at->format('Y-m-d') == $ends_at->format('Y-m-d'))
            return $ends_at->format('H:i') >= $start_time->format('H:i');

        // else within range 
        return true;
    }

    /**
     * getBreakTime
     *
     * @param  mixed $schedule
     * @param  mixed $start_time
     * @param  mixed $end_time
     */
    public function getBreakTime($schedule, $start_time, $end_time)
    {
        $breaks = ScheduleBreak::where(['schedule_day_id' => $schedule->schedule_day_id])->get();
        if (!$breaks) return false;


        return $breaks->first(function ($break) use ($start_time, $end_time) {
            $breakStart = Carbon::parse($start_time->toDateString() . ' ' . $break->start_time);
            $breakEnd = Carbon::parse($start_time->toDateString() . ' ' . $break->end_time);

            return $start_time->lt($breakEnd) && $end_time->gt($breakStart);
        });
    }


    /**
     * postBreak
     *
     * @param  mixed $schedule
     * @param  mixed $start_time
     * @param  mixed $end_time
     */
    public function postBreak($schedule, $start_time, $end_time, $date, $status)
    {
        // check if break time is within the activity time
        $break = $this->getBreakTime($schedule, $start_time, $end_time);

        if (!$break) return false;

        // post a Break type activity 
        $break = Activity::firstOrCreate([
            'title' => $break->name,
            'is_break' => 1,
            'is_time_off' => 0,
            'start_time' => $date->copy()->setTimeFromTimeString(Carbon::parse($break->start_time)->toTimeString()),
            'end_time' => $date->copy()->setTimeFromTimeString(Carbon::parse($break->end_time)->toTimeString()),
            'user_id' => Auth::id(),
            'product_id' => $schedule->product_id,
            'schedule_id' => $schedule->schedule_id,
            'schedule_day_id' => $schedule->schedule_day_id,
            'duration' =>  0,
            'recurres_in' => 0,
            'note' => null,
            'status' => $status
        ]);

        // may log duplicate if falls within the same time
        Log::channel('activity')->debug('Break:', $break->toArray());

        return $break;
    }

    /**
     * getDateRangeByWeekDay
     *
     * @param  mixed $day_of_week
     * @return \Illuminate\Support\Collection
     */
    public function getDateRangeByWeekDay($day_of_week): \Illuminate\Support\Collection
    {
        // loop through each requested date , 
        $startDate = Carbon::parse(request()->start_time);
        $endDate = Carbon::parse(request()->end_time);

        return Collection::times(intval($startDate->diffInDays($endDate) + 1), fn($i) => $startDate->copy()->addDays($i - 1))
            ->filter(fn($date) => $date->isoWeekday() === ++$day_of_week);
    }



    /**
     * postActivity
     *
     * @param  mixed $activity
     * @param  mixed $date
     * @return Activity|void
     */
    public function postActivity($activity): Activity|null
    {

        // create or update record for product
        $activity = Activity::firstOrCreate($activity);

        //TODO - Logs.. will be added in production.
        Log::channel('activity')->debug('Activity:', $activity->toArray());

        return $activity;
    }

    /**
     * calculateStartTime
     *
     * @param  mixed $start_time
     * @param  mixed $schedule
     * @return 
     */
    public function calculateStartTime($start_time, $schedule)
    {
        return $start_time->copy()->addMinutes((int) $schedule->recurres_in); // + (int) $schedule->duration
    }


    /**
     * getTime
     *
     * @param  mixed $time
     */
    public function getTime($time)
    {
        // 
        try {
            return Carbon::parse($time);
        } catch (\Carbon\Exceptions\InvalidFormatException $e) {
            Log::channel('activity')->error($e->getMessage());
            return null;
        }
    }



    /**
     * update
     *
     * @param  mixed $request
     * @param  Activity $activity
     * @return ActivityResource
     */
    public function update(ActivityUpdateRequest $request, Activity $activity): ActivityResource
    {
        try {
            $start_time = $request->input('start_time', $activity->start_time);
            $end_time = $request->input('end_time', $activity->end_time);
            $is_force = $request->input('force', false);

            // get booked activities
            $bookedActivities = Activity::getBookedActivities($start_time, $end_time);
            $bookingCount = $bookedActivities?->count() ?? 0;

            // update activity fields 
            if ((Activity::hasConflicts($start_time, $end_time, $activity->id, $activity->product_id) || $bookingCount) && !$is_force) {
                throw new ActivityConflictException(__($bookingCount > 0 ? "activity.conflict_with_bookings" :  "activity.conflict"), Response::HTTP_CONFLICT, $bookedActivities->toArray());
            }


            // if there are booked activities, refund all the booked seats
            if ($bookingCount && $is_force) {

                // initialize booking service
                $bookingService = new BookingService();

                // loop through each booked activity
                $bookings = Booking::whereIn('activity_id', $bookedActivities->pluck('id'))
                    ->where('status', BookingStatus::CONFIRMED)
                    ->get();

                // cancel the booking
                foreach ($bookings as $booking) {
                    foreach ($booking->attendees as $attendee) {
                        $bookingService->cancelBooking($booking, $attendee, notes: 'Booking cancelled because the activity was updated by the host.');
                    }
                }
            }

            // update activity 
            $activity->update($request->validated());

            // return response
            return new ActivityResource($activity);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Handle time-off creation or update
     *
     * @param array $data
     * @param Activity|null $existingTimeOff
     * @return Activity
     */
    private function handleTimeOffUpdate(array $data, ?Activity $existingTimeOff = null): Activity
    {
        if ($existingTimeOff) {
            $existingTimeOff->update([
                'start_time' => min($existingTimeOff->start_time, $data['start_time']),
                'end_time' => max($existingTimeOff->end_time, $data['end_time']),
                'title' => $data['title'] ?? $existingTimeOff->title,
                'note' => $data['note'] ?? $existingTimeOff->note
            ]);
            return $existingTimeOff;
        }

        return Activity::create($data);
    }

    /**
     * Set time-off
     *
     * @param SetTimeOffRequest $request
     * @return ActivityResource
     */
    public function setTimeOff(SetTimeOffRequest $request): ActivityResource
    {
        try {
            // Check if time-off can be posted
            if (!Activity::canTimeOffBePosted($request->start_time, $request->end_time) && !$request->input('force', false)) {
                throw new Exception(__('activity.no_slots_for_timeoff'), Response::HTTP_CONFLICT);
            }

            // Handle force parameter
            if ($request->input('force', false)) {
                Activity::markOverlappingActivitiesInactive($request->start_time, $request->end_time);
            }

            // Find overlapping time-off
            $existingTimeOff = Activity::findOverlappingTimeOff($request->start_time, $request->end_time);

            // Create or update time-off
            $timeOff = $this->handleTimeOffUpdate(
                $request->except('force') + [
                    'user_id' => Auth::id(),
                    'is_time_off' => 1,
                    'status' => 1
                ],
                $existingTimeOff
            );

            return new ActivityResource($timeOff);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Update time-off
     *
     * @param UpdateTimeOffRequest $request
     * @param Activity $timeOff
     * @return ActivityResource
     */
    public function updateTimeOff(UpdateTimeOffRequest $request, Activity $timeOff): ActivityResource
    {
        try {
            // Verify this is a time-off activity
            if (!$timeOff->is_time_off) {
                throw new Exception("Not a time-off activity", Response::HTTP_BAD_REQUEST);
            }

            // Get new time range
            $newStart = $request->input('start_time', $timeOff->start_time);
            $newEnd = $request->input('end_time', $timeOff->end_time);

            // Check if time-off can be posted
            if (($request->has('start_time') || $request->has('end_time')) &&
                !Activity::canTimeOffBePosted($newStart, $newEnd) &&
                !$request->input('force', false)
            ) {
                throw new Exception(__('activity.no_slots_for_timeoff'), Response::HTTP_CONFLICT);
            }

            // Handle force parameter
            if ($request->input('force', false) && ($request->has('start_time') || $request->has('end_time'))) {
                Activity::markOverlappingActivitiesInactive($newStart, $newEnd, $timeOff->id);
            }

            // Find overlapping time-off
            $overlappingTimeOff = Activity::findOverlappingTimeOff($newStart, $newEnd, $timeOff->id);

            if ($overlappingTimeOff) {
                // Update overlapping time-off and delete current one
                $overlappingTimeOff->update([
                    'start_time' => min($overlappingTimeOff->start_time, $newStart),
                    'end_time' => max($overlappingTimeOff->end_time, $newEnd),
                    'title' => $request->input('title', $overlappingTimeOff->title),
                    'note' => $request->input('note', $overlappingTimeOff->note)
                ]);
                $timeOff->delete();
                return new ActivityResource($overlappingTimeOff);
            }

            // Update current time-off
            $timeOff->update($request->except('force'));
            return new ActivityResource($timeOff);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Cancel time-off
     *
     * @param Activity $timeOff
     * @return void
     */
    public function cancelTimeOff(Activity $timeOff): void
    {
        try {
            if (!$timeOff->is_time_off && !$timeOff->is_break) {
                throw new Exception("messages.not_found", 404);
            }

            // Mark other activities as active
            Activity::markAsActiveByTimeRange($timeOff->start_time, $timeOff->end_time);
            $timeOff->delete();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * cancel
     *
     * @param  ActivityCancelRequest $request
     * @return JsonResponse
     */
    public function cancel(ActivityCancelRequest $request)
    {
        try {
            $is_force = $request->input('force', false);
            $starts_at = $request->input('start_time', null);
            $end_time = $request->input('end_time', null);
            $note = $request->input('note', null);
            $product_ids = $request->input('product_ids', []);

            // get booked activities
            $bookedActivities = Activity::getBookedActivities($starts_at, $end_time);
            if ($bookedActivities && $bookedActivities->count()) {
                if (!$is_force) {
                    // if there are booked activities, or activities are under the same time range, throw an error
                    throw new ActivityConflictException(__("activity.conflict_with_bookings"), Response::HTTP_CONFLICT, $bookedActivities->toArray());
                }

                // loop through each booked activity
                foreach ($bookedActivities as $activity) {
                    // get the activity
                    $activity = Activity::find($activity['id']);

                    // cancel booking by activity
                    (new BookingService())->cancelBookingByActivity($activity, $note);

                    // delete the activity
                    $activity->cancel($note);
                }
            }

            // cancel all the activities in the time range if force is true
            Activity::cancelActivities($product_ids, $starts_at, $end_time);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * delete
     *
     * @param  Activity $activity
     * @return void
     */
    public function delete(Activity $activity): void
    {
        try {
            $is_force = request()->input('force', false);
            if ($activity->bookings()->count()) {
                if (!$is_force) {
                    throw new Exception(__("activity.has_bookings"), Response::HTTP_CONFLICT);
                }

                // cancel booking by activity
                (new BookingService())->cancelBookingByActivity($activity, 'Activity deleted by the host.');
            }

            // delete the activity
            $activity->delete();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * attendees
     * TODO: move attendees to attendee service
     * @param  Activity $activity
     * @return ResourceCollection
     */
    public function attendees(Activity $activity): ResourceCollection
    {
        try {
            if (!Auth::user()->canAccess($activity->user_id)) {
                throw new Exception(__("activity.not_found"), Response::HTTP_NOT_FOUND);
            }

            if (!$activity->isActive()) {
                throw new Exception(__("activity.not_found"), Response::HTTP_NOT_FOUND);
            }

            $attendees = Attendee::join('bookings', 'bookings.id', '=', 'attendees.booking_id')
                ->where('bookings.status', BookingStatus::CONFIRMED)
                ->where('bookings.activity_id', $activity->id)
                ->with('booking')
                ->paginate(request()->per_page) ?? [];

            return AttendeeResource::collection($attendees);
        } catch (Exception $e) {
            throw $e;
        }
    }
}
