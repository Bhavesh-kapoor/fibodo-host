<?php

namespace App\Http\Resources;

use App\Enums\TargetAudience;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OfferResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'offer_type' => new OfferTypeResource($this->whenLoaded('offerType')),
            'host' => new UserResource($this->whenLoaded('host')),
            'name' => $this->name,
            'description' => $this->description,
            'value' => $this->value,
            'is_discount' => $this->is_discount,
            'target_audience' => [
                'id' => $this->target_audience,
                'name' => $this->target_audience->label(),
            ],
            'apply_to_all_products' => $this->apply_to_all_products,
            'products' => ProductResource::collection($this->whenLoaded('products')),
            'terms_conditions' => $this->terms_conditions,
            'status' => $this->status,
            'starts_at' => $this->starts_at,
            'expires_at' => $this->expires_at,
            'is_active' => $this->isActive(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
