<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class VoucherTypeResource
 * 
 * Transforms VoucherType models into JSON response format.
 * Controls the structure and data included in voucher type API responses.
 */
class VoucherTypeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     * Defines the API structure for voucher type data.
     *
     * @param  Request  $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'description' => $this->description,
            'requires_account' => $this->requires_account,
            'default_expiry_days' => $this->default_expiry_days,
            'settings' => $this->settings,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
