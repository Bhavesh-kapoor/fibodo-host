<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttendeeSettingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'ability_level' => $this->ability_level,
            'has_age_restriction' => $this->has_age_restriction,
            'age_below' => $this->age_below,
            'age_above' => $this->age_above,
            'gender_restrictions' => $this->gender_restrictions,
            'is_family_friendly' => $this->is_family_friendly,
        ];
    }
}
