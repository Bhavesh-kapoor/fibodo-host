<?php

namespace App\Services;

use App\Enums\PlanStatus;
use App\Models\MembershipPlan;
use App\Models\MembershipBenefit;
use Auth;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Exception;

class MembershipPlanService
{

    /**
     * List membership plans with optional filters and pagination.
     *
     * @param array $filters
     * @return LengthAwarePaginator
     * @throws Exception
     */
    public function get(array $filters = []): LengthAwarePaginator
    {
        try {
            $query = MembershipPlan::query()
                ->with('benefits')
                ->where('host_id', Auth::id())
                ->when(isset($filters['type']), function ($query) use ($filters) {
                    $query->where('type', $filters['type']);
                })
                ->when(isset($filters['plan_type']), function ($query) use ($filters) {
                    $query->where('plan_type', $filters['plan_type']);
                })
                ->when(isset($filters['status']), function ($query) use ($filters) {
                    $query->where('status', $filters['status']);
                })
                ->when(isset($filters['s']), function ($query) use ($filters) {
                    $query->where(function ($query) use ($filters) {
                        $query->where(\DB::raw('LOWER(title)'), 'like', '%' . strtolower($filters['s']) . '%')
                            ->orWhere(\DB::raw('LOWER(description)'), 'like', '%' . strtolower($filters['s']) . '%');
                    });
                })
                ->orderBy('created_at', 'desc');

            return $query->paginate($filters['per_page'] ?? 15);
        } catch (Exception $e) {
            // Rethrow to be handled by controller
            throw $e;
        }
    }


    /**
     * Create a new membership plan and attach benefits.
     *
     * @param array $data
     * @return MembershipPlan
     * @throws Exception
     */
    public function create(array $data): MembershipPlan
    {
        try {
            $benefits = $data['benefits'] ?? [];
            unset($data['benefits']);
            $status = $data['status'] ?? null;

            // Create membership plan
            $membershipPlan = MembershipPlan::create(
                $data + [
                    'host_id' => Auth::id(),
                    'status' => $status ?? PlanStatus::PUBLISHED->value,
                    'published_at' => $status === PlanStatus::PUBLISHED->value ? now() : null,
                    'archived_at' => $status === PlanStatus::ARCHIVED->value ? now() : null,
                ]
            );

            if (!empty($benefits)) {
                $this->syncBenefits($membershipPlan, $benefits);
            }

            return $membershipPlan->load('benefits');
        } catch (Exception $e) {
            // Rethrow to be handled by controller
            throw $e;
        }
    }

    /**
     * Update an existing membership plan and its benefits.
     *
     * @param MembershipPlan $membershipPlan
     * @param array $data
     * @return MembershipPlan
     * @throws Exception
     */
    public function update(MembershipPlan $membershipPlan, array $data): MembershipPlan
    {
        try {
            $benefits = $data['benefits'] ?? null;
            $status = $data['status'] ?? $membershipPlan->status->value ?? PlanStatus::PUBLISHED->value;
            unset($data['benefits']);

            $membershipPlan->update($data + [
                'status' => $status ?? PlanStatus::PUBLISHED->value,
                'published_at' => $status === PlanStatus::PUBLISHED->value ? now() : null,
                'archived_at' => $status === PlanStatus::ARCHIVED->value ? now() : null,
            ]);

            if ($benefits !== null) {
                $this->syncBenefits($membershipPlan, $benefits);
            }

            return $membershipPlan->load('benefits');
        } catch (Exception $e) {
            // Rethrow to be handled by controller
            throw $e;
        }
    }

    /**
     * Delete a membership plan.
     *
     * @param MembershipPlan $membershipPlan
     * @return bool
     * @throws Exception
     */
    public function delete(MembershipPlan $membershipPlan): bool
    {
        try {
            return $membershipPlan->delete();
        } catch (Exception $e) {
            // Rethrow to be handled by controller
            throw $e;
        }
    }

    /**
     * Find a membership plan by its ID, including benefits.
     *
     * @param string $id
     * @return MembershipPlan|null
     * @throws Exception
     */
    public function find(string $id): ?MembershipPlan
    {
        try {
            return MembershipPlan::with('benefits')->find($id);
        } catch (Exception $e) {
            // Rethrow to be handled by controller
            throw $e;
        }
    }

    /**
     * Sync membership plan benefits with pivot data.
     *
     * @param MembershipPlan $membershipPlan
     * @param array $benefits
     * @return void
     */
    protected function syncBenefits(MembershipPlan $membershipPlan, array $benefits): void
    {
        $benefitData = collect($benefits)->mapWithKeys(function ($benefit) {
            return [
                $benefit['id'] => [
                    'is_unlimited' => $benefit['is_unlimited'] ?? null,
                    'pass_count' => $benefit['pass_count'] ?? null,
                    'discount_percentage' => $benefit['discount_percentage'] ?? null,
                    'advance_booking_days' => $benefit['advance_booking_days'] ?? null,
                ]
            ];
        })->all();

        $membershipPlan->benefits()->sync($benefitData);
    }

    /**
     * getMembershipBenefits
     *
     * @param array $filters
     * @return LengthAwarePaginator
     */
    public function getMembershipBenefits(array $filters = []): LengthAwarePaginator
    {
        try {
            return MembershipBenefit::paginate($filters['per_page'] ?? 15);
        } catch (Exception $e) {
            throw $e;
        }
    }
}
