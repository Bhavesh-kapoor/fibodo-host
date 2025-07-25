<?php

namespace App\Services;

use App\Http\Requests\Schedule\RenameScheduleRequest;
use App\Http\Requests\Schedule\StoreScheduleRequest;
use App\Http\Requests\Schedule\UpdateScheduleRequest;
use App\Http\Resources\ScheduleResource;
use App\Models\Product;
use App\Models\Schedules\Schedule;
use App\Models\Schedules\WeeklySchedule;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Response;

class ScheduleService
{

    /**
     * get
     *
     * @return void
     */
    public function get(Product $product)
    {
        try {

            if (!$product->schedule) return [];

            $product->schedule->load('weeklySchedules.days.breaks');
            return new ScheduleResource($product->schedule);
        } catch (\Exception $e) {
            throw $e;
        }
    }


    /**
     * create
     *
     * @return 
     */
    public function create(StoreScheduleRequest $request, Product $product)
    {
        try {

            // Allow only one Schedule Per product 
            if ($product->schedule) throw new Exception('Schedule already exists for the product', Response::HTTP_UNPROCESSABLE_ENTITY);

            $schedule = $product->schedule()->firstOrCreate($request->only('recurres_in'));

            $this->createSchedules($request, $schedule);

            // update product status
            $product->markAsPublished();

            $schedule->load('weeklySchedules.days.breaks');

            return new ScheduleResource($schedule);
        } catch (\Exception $e) {
            throw $e;
        }
    }


    /**
     * createSchedules
     *
     * @param  mixed $request
     * @param  mixed $schedule
     * @return void
     */
    public function createSchedules(StoreScheduleRequest $request, Schedule $schedule)
    {
        try {
            // Create Weekly Schedules, Days, and Breaks with schedule_id
            collect($request->input('weekly_schedules'))->map(function ($weekly) use ($schedule) {
                $this->createWeeklySchedule($weekly, $schedule);
            });
        } catch (\Exception $e) {
            throw $e;
        }
    }


    /**
     * createWeeklySchedule
     *
     * @param  mixed $weekly
     * @param  mixed $schedule
     * @return void
     */
    public function createWeeklySchedule($weekly, Schedule $schedule)
    {
        try {
            // Create weekly schedule
            $weeklySchedule = $schedule->weeklySchedules()->create($weekly);

            // Create days
            collect($weekly['days'])->map(function ($day) use ($weeklySchedule) {

                // Create day
                $dayModel = $weeklySchedule->days()->create($day);

                // Create breaks
                if (isset($day['breaks']))
                    $dayModel->breaks()->createMany($day['breaks']);
            });
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * updateSchedules
     *
     * @param  mixed $request
     * @param  mixed $schedule
     * @return void
     */
    public function updateSchedules(UpdateScheduleRequest $request, Schedule $schedule)
    {
        try {
            // Create Weekly Schedules, Days, and Breaks with schedule_id
            collect($request->input('weekly_schedules'))->map(function ($weekly) use ($schedule) {
                $this->createWeeklySchedule($weekly, $schedule);
            });

            // update main schedule 
            $schedule->update($request->only('recurres_in', 'status'));
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * update
     *
     * @return ScheduleResource
     */
    public function update(UpdateScheduleRequest $request, Schedule $schedule): ScheduleResource
    {
        try {

            // delete existing schedules 
            $schedule->weeklySchedules()->forceDelete();

            // update Schedule
            $this->updateSchedules($request, $schedule);

            // update product status
            $product = $schedule->product;
            $product->markAsPublished();

            // load relationships before return
            $schedule->load('weeklySchedules.days.breaks');
            return new ScheduleResource($schedule);
        } catch (\Exception $e) {
            throw $e;
        }
    }


    /**
     * renameWeeklySchedule
     *
     * @return ScheduleResource
     */
    public function renameWeeklySchedule(RenameScheduleRequest $request, WeeklySchedule $weeklySchedule): ScheduleResource
    {
        try {

            // rename weeklyschedule 
            $weeklySchedule->update(['name' => $request->name]);
            $schedule = $weeklySchedule->schedule;

            // load relationships before return
            $schedule->load('weeklySchedules.days.breaks');
            return new ScheduleResource($schedule);
        } catch (\Exception $e) {
            throw $e;
        }
    }


    /**
     * getSchedulesForActivity
     *
     * @param  mixed $productIds
     * @return Collection
     */
    public static function getSchedulesForActivity($productIds, $start_time, $end_time): Collection
    {

        $weekly_schedule_ids = request()->input('weekly_schedule_ids', null);

        return Schedule::select([
            'schedules.id as schedule_id',
            'weekly_schedules.id as weekly_schedule_id',
            'schedule_days.id as schedule_day_id',
            'schedules.product_id',
            'weekly_schedules.name',
            'weekly_schedules.is_default',
            'schedules.recurres_in',
            'schedule_days.day_of_week',
            'schedule_days.start_time as day_start',
            'schedule_days.end_time as day_end',
            'products.no_of_slots',
            'products.session_duration as duration'
        ])
            ->join('products', 'schedules.product_id', '=', 'products.id')
            ->join('weekly_schedules', function ($join) {
                $join->on('schedules.id', '=', 'weekly_schedules.schedule_id')
                    ->whereNull('weekly_schedules.deleted_at');
            })
            ->join('schedule_days', function ($join) use ($start_time, $end_time) {
                $join->on('weekly_schedules.id', '=', 'schedule_days.weekly_schedule_id')
                    //->where('schedule_days.start_time', '<=', $end_time)
                    //->where('schedule_days.end_time', '>=', $start_time)
                    ->whereNull('schedule_days.deleted_at');
            })
            ->whereIn('schedules.product_id', $productIds) // Ensure results are within given products
            ->where('products.session_duration', '>', 0)
            ->where('schedules.recurres_in', '>', 0)
            ->when($weekly_schedule_ids, fn($q) => $q->whereIn('weekly_schedules.id', $weekly_schedule_ids))
            ->where(function ($query) {
                // Get schedules where is_default = 1
                $query->where('weekly_schedules.is_default', 1)

                    // If default schedule exists for a day, exclude that day from fallback schedules
                    ->orWhere(function ($subquery) {
                        $subquery->where('weekly_schedules.is_default', '!=', 1) // Get alternate schedules
                            ->whereNotExists(function ($checkDefault) {
                                $checkDefault->select(\DB::raw(1))
                                    ->from('weekly_schedules as ws2')
                                    ->join('schedule_days as sd2', 'ws2.id', '=', 'sd2.weekly_schedule_id')
                                    ->whereColumn('sd2.day_of_week', 'schedule_days.day_of_week') // Prevent duplicate days
                                    ->whereColumn('ws2.schedule_id', 'weekly_schedules.schedule_id') // Keep in same product scope
                                    ->where('ws2.is_default', 1) // Default schedules only
                                    ->whereNull('ws2.deleted_at')
                                    ->whereNull('sd2.deleted_at');
                            });
                    });
            })
            ->orderBy('schedules.product_id', 'ASC')
            ->orderBy('schedule_days.day_of_week', 'ASC')
            ->orderBy('schedule_days.start_time', 'ASC')
            ->get();
    }
}
