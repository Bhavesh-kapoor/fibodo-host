<?php

namespace App\Http\Controllers;

use App\Http\Requests\Policy\PolicyRequest;
use App\Http\Resources\PolicyResource;
use App\Models\Policy;
use App\Services\PolicyService;
use Exception;
use Illuminate\Http\JsonResponse;

class PolicyController extends Controller
{

    public function __construct(
        private PolicyService $service
    ) {}

    /**
     * Policies
     */
    public function index(PolicyRequest $request): JsonResponse
    {
        try {
            return response()->success(
                'message.success',
                PolicyResource::collection($this->service->get())
            );
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * GetPolicy
     */
    public function show(Policy $policy): JsonResponse
    {
        try {
            if (!$policy->isGlobal() && $policy->user_id !== \Auth::user()->id && $policy->user_id !== null) {
                return response()->error('message.not_found', null, 404);
            }

            return response()->success('message.success', new PolicyResource($policy));
        } catch (Exception $e) {
            throw $e;
        }
    }
}
