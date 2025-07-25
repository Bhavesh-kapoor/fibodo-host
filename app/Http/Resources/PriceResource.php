<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PriceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'price' => $this->price,
            'is_age_sensitive' => $this->is_age_sensitive,
            'junior_price' => $this->junior_price,
            'adult_price' => $this->adult_price,
            'senior_price' => $this->senior_price,
            'is_walk_in_pricing' => $this->is_walk_in_pricing,
            'walk_in_price' => $this->walk_in_price,
            'is_walk_in_age_sensitive' => $this->is_walk_in_age_sensitive,
            'walk_in_junior_price' => $this->walk_in_junior_price,
            'walk_in_adult_price' => $this->walk_in_adult_price,
            'walk_in_senior_price' => $this->walk_in_senior_price,
            'is_special_pricing' => $this->is_special_pricing,
            'multi_attendee_price' => $this->multi_attendee_price,
            'no_of_slots' => $this->no_of_slots,
            'all_space_price' => $this->all_space_price,
            'policies' => $this->whenLoaded('policies', fn() => PolicyResource::collection($this->policies)),
        ];
    }
}
