<?php

namespace App\Http\Controllers;

use App\Http\Requests\MembershipPlan\CreateMembershipPlanRequest;
use App\Http\Requests\MembershipPlan\MembershipBenefitRequest;
use App\Http\Requests\MembershipPlan\MembershipPlanRequest;
use App\Http\Requests\MembershipPlan\UpdateMembershipPlanRequest;
use App\Http\Resources\MembershipBenefitResource;
use App\Http\Resources\MembershipBenefitTypeResource;
use App\Http\Resources\MembershipPlanResource;
use App\Models\MembershipPlan;
use App\Services\MembershipPlanService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class MembershipPlanController extends Controller
{

    use AuthorizesRequests;

    /**
     * MembershipPlanController constructor.
     *
     * @param MembershipPlanService $service
     */
    public function __construct(
        private readonly MembershipPlanService $service
    ) {
        // $this->authorizeResource(MembershipPlan::class, 'membershipPlan');
    }

    /**
     * Get a list of membership plans for the host.
     *
     * @return AnonymousResourceCollection|JsonResponse
     */
    public function index(MembershipPlanRequest $request): JsonResponse
    {
        try {
            $membershipPlans = $this->service->get($request->validated());
            return response()->success('messages.success', MembershipPlanResource::collection($membershipPlans));
        } catch (Exception $e) {
            return response()->error($e->getMessage(), null, $e->getCode() ?: 400);
        }
    }

    /**
     * Create a new membership plan for the host.
     *
     * @param CreateMembershipPlanRequest $request
     * @return JsonResponse
     */
    public function store(CreateMembershipPlanRequest $request): JsonResponse
    {
        try {
            $membershipPlan = $this->service->create($request->validated());
            return response()->success('messages.created', new MembershipPlanResource($membershipPlan), null, 201);
        } catch (Exception $e) {
            return response()->error($e->getMessage(), null, $e->getCode() ?: 400);
        }
    }

    /**
     * Get a specific membership plan by ID.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function show(MembershipPlan $membershipPlan): JsonResponse
    {
        try {

            $this->authorize('view', $membershipPlan);

            $membershipPlan->load('benefits');
            return response()->success('messages.success', new MembershipPlanResource($membershipPlan));
        } catch (Exception $e) {
            return response()->error($e->getMessage(), null, $e->getCode() ?: 400);
        }
    }

    /**
     * Update a membership plan by ID.
     *
     * @param UpdateMembershipPlanRequest $request
     * @param MembershipPlan $membershipPlan
     * @return JsonResponse
     */
    public function update(UpdateMembershipPlanRequest $request, MembershipPlan $membershipPlan): JsonResponse
    {
        try {
            $this->authorize('update', $membershipPlan);

            return response()->success('messages.updated', new MembershipPlanResource($this->service->update($membershipPlan, $request->validated())), null, 200);
        } catch (Exception $e) {
            return response()->error($e->getMessage(), null, $e->getCode() ?: 400);
        }
    }

    /**
     * Delete a membership plan by ID.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function destroy(MembershipPlan $membershipPlan): JsonResponse
    {
        try {
            $this->authorize('delete', $membershipPlan);

            $this->service->delete($membershipPlan);

            return response()->success('messages.deleted', null, null, 200);
        } catch (Exception $e) {
            return response()->error($e->getMessage(), null, $e->getCode() ?: 400);
        }
    }

    /**
     * Get a list of membership plans for the host.
     *
     * @return AnonymousResourceCollection|JsonResponse
     */
    public function getMembershipBenefits(MembershipBenefitRequest $request): JsonResponse
    {
        try {
            return response()->success(
                'messages.success',
                MembershipBenefitResource::collection($this->service->getMembershipBenefits($request->validated()))
            );
        } catch (Exception $e) {
            return response()->error($e->getMessage(), null, $e->getCode() ?: 400);
        }
    }

    /**
     * archive
     *
     * @param MembershipPlan $membershipPlan
     * @return JsonResponse
     */
    public function archive(MembershipPlan $membershipPlan): JsonResponse
    {
        try {
            $this->authorize('archive', $membershipPlan);

            $membershipPlan->markAsArchived();

            return response()->success('messages.archived', new MembershipPlanResource($membershipPlan));
        } catch (Exception $e) {
            return response()->error($e->getMessage(), null, $e->getCode() ?: 400);
        }
    }

    /**
     * restore
     *
     * @param MembershipPlan $membershipPlan
     * @return JsonResponse
     */
    public function restore(MembershipPlan $membershipPlan): JsonResponse
    {
        try {
            $this->authorize('restore', $membershipPlan);

            $membershipPlan->markAsPublished();

            return response()->success('messages.restored', new MembershipPlanResource($membershipPlan));
        } catch (Exception $e) {
            return response()->error($e->getMessage(), null, $e->getCode() ?: 400);
        }
    }
}
