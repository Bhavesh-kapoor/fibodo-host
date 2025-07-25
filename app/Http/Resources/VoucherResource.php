<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VoucherResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'voucher_type' => new VoucherTypeResource($this->whenLoaded('voucherType')),
            'host' => $this->when($this->relationLoaded('host'), function () {
                return [
                    'id' => $this->host->id,
                    'full_name' => $this->host->full_name
                ];
            }),
            'name' => $this->name,
            'code' => $this->code,
            'description' => $this->description,
            'value' => $this->value,
            'pay_for_quantity' => $this->pay_for_quantity,
            'get_quantity' => $this->get_quantity,
            'x_for_y_text' => $this->when($this->pay_for_quantity && $this->get_quantity, $this->getXforYText()),
            'products' => $this->when($this->relationLoaded('products'), function () {
                return $this->products->map(function ($product) {
                    return [
                        'id' => $product->id,
                        'title' => $product->title
                    ];
                });
            }),
            'is_transferrable' => $this->is_transferrable,
            'is_gift_eligible' => $this->is_gift_eligible,
            'can_combine' => $this->can_combine,
            'inventory_limit' => $this->inventory_limit,
            'sold_count' => $this->sold_count,
            'available_count' => $this->when($this->inventory_limit !== null, function () {
                return max(0, $this->inventory_limit - $this->sold_count);
            }),
            'status' => $this->when($this->status, function () {
                return [
                    'value' => $this->status->value,
                    'name' => $this->status->name
                ];
            }, null),
            'is_active' => $this->isActive(),
            'is_available_for_sale' => $this->isAvailableForSale(),
            'is_expired' => $this->isExpired(),
            'expires_at' => $this->expires_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
