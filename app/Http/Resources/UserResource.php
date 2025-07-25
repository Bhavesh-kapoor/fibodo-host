<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'code' => $this->code,
            'username' => $this->username ?? $this->email,
            'roles' => $this->roles->pluck('name'),
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'mobile_number' => $this->mobile_number,
            'nhs_id' => $this->nhs_id,
            'country_code' => $this->country_code,
            'date_of_birth' => $this->date_of_birth,
            'gender' => $this->gender,
            'avatar' => $this->host?->getMedia("hosts/avatar")?->first()?->getFullUrl(),
            'created_at' => $this->created_at,
        ];
    }
}
