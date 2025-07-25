<?php

namespace App\Http\Controllers;

use App\Http\Requests\Schedule\GetScheduleRequest;
use App\Http\Requests\Schedule\StoreScheduleRequest;
use App\Http\Requests\Schedule\UpdateScheduleRequest;
use App\Http\Requests\Schedule\RenameScheduleRequest;
use App\Http\Resources\ScheduleResource;
use App\Models\Product;
use App\Models\Schedules\Schedule;
use App\Models\Schedules\WeeklySchedule;
use App\Services\ScheduleService;
use Dedoc\Scramble\Attributes\ExcludeRouteFromDocs;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{

    use AuthorizesRequests;

    //TODO: check user level permissions:  Gate::authorize('create products', 'web');

    protected $service;
    protected $product;

    /**
     * __construct
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        // authorize
        if ($request->has('product_id')) {
            $this->product = Product::findOrFail($request->input('product_id'));

            // Manually authorize viewAny 
            $this->authorize('viewAny', [Schedule::class, $this->product]);
        } else {
            $this->authorizeResource(Schedule::class, 'schedule');
        }

        // initialize the service
        $this->service = new ScheduleService();
    }



    /**
     * GetProductSchedules
     * 
     * @param  mixed $request
     * @return void
     */
    public function index(GetScheduleRequest $request)
    {
        try {
            return response()->success(
                'messages.success',
                $this->service->get($this->product)
            );
        } catch (Exception $e) {
            return response()->error($e->getMessage(), null, $e->getCode());
        }
    }


    /**
     * GetScheduleDetails
     * 
     * @param  mixed $schedule
     * @return void
     */
    public function show(Schedule $schedule)
    {
        try {
            $schedule->load('weeklySchedules.days.breaks');
            return response()->success('messages.success', new ScheduleResource($schedule));
        } catch (Exception $e) {
            return response()->error($e->getMessage(), null, $e->getCode());
        }
    }



    /**
     * AddSchedules
     * 
     * @param  mixed $request
     * @return void
     */
    public function store(StoreScheduleRequest $request)
    {
        try {
            return response()->success(
                'messages.created',
                $this->service->create($request, $this->product),
                null,
                201
            );
        } catch (Exception $e) {
            return response()->error($e->getMessage(), null, $e->getCode());
        }
    }



    /**
     * UpdateSchedule
     * 
     * @param  mixed $request
     * @param  mixed $schedule
     * @return void
     */
    public function update(UpdateScheduleRequest $request, Schedule $schedule)
    {
        try {
            return response()->success(
                'messages.updated',
                $this->service->update($request, $schedule)
            );
        } catch (Exception $e) {
            return response()->error($e->getMessage(), null, $e->getCode());
        }
    }


    /**
     * renameWeeklySchedule
     *
     * @param  mixed $request
     * @param  mixed $schedule
     * @return void
     */
    public function renameWeeklySchedule(RenameScheduleRequest $request, WeeklySchedule $weeklySchedule)
    {
        try {
            return response()->success('messages.updated', $this->service->renameWeeklySchedule($request, $weeklySchedule));
        } catch (Exception $e) {
            return response()->error($e->getMessage(), null, $e->getCode());
        }
    }


    #[ExcludeRouteFromDocs]
    public function destroy(Schedule $schedule) {}
}
