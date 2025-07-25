<?php

namespace App\Services;

use App\Http\Resources\VoucherTypeResource;
use App\Models\VoucherType;
use Exception;


class VoucherTypeService
{
    /**
     * Get voucher types based on optional filters
     *
     * @param array $filters
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection<VoucherTypeResource>
     * @throws Exception
     */
    public function get(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        try {
            return VoucherTypeResource::collection(VoucherType::active()->paginate(request()->per_page));
        } catch (Exception $e) {
            throw $e;
        }
    }
}
