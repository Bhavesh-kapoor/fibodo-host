<?php

namespace App\Services;

use App\Enums\PolicyType;
use App\Models\Policy;
use Exception;

class PolicyService
{
    /**
     * get
     *
     * @return Collection
     */
    public function get()
    {
        try {
            $policy_type = PolicyType::fromSlug(request()->input('policy_type', ""));
            $isGlobal = request()->input('is_global', 1);
            $sortBy = request()->input('sort_by', 'created_at');
            $sortOrder = request()->input('sort_order', 'desc');
            $perPage = request()->input('per_page', 10);

            return Policy::where('is_global', $isGlobal)
                ->when($policy_type, function ($query) use ($policy_type) {
                    $query->where('policy_type', $policy_type->value);
                })
                ->active()
                ->orderBy($sortBy, $sortOrder)
                ->paginate($perPage);
        } catch (Exception $e) {
            throw $e;
        }
    }
}
