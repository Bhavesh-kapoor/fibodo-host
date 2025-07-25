<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            'id' => $this->user->id,
            'code' => $this->user->code,
            'first_name' => $this->user->first_name,
            'last_name' => $this->user->last_name,
            'email' => $this->user->email,
            'roles' => $this->user->roles->pluck('name'),
            'mobile_number' => $this->user->mobile_number,
            'date_of_birth' => $this->user->date_of_birth,
            'gender' => $this->user->gender,
            'media' => $this->when(
                $this->relationLoaded('media') && $this->media->isNotEmpty(),
                fn() => $this->media
                    ->groupBy('collection_name')
                    ->map(fn($items) => $items->count() > 1 ?  MediaResource::collection($items) : new MediaResource($items->first()))
            ),
            'business_name' => $this->business_name,
            'business_tagline' => $this->business_tagline,
            'business_about' => $this->business_about,
            'business_website' => $this->business_website,
            'company_name' => $this->company_name,
            'company_address_line1' => $this->company_address_line1,
            'company_address_line2' => $this->company_address_line2,
            'company_city' => $this->company_city,
            'company_zip' => $this->company_zip,
            'company_country' => $this->company_country,
            'company_contact_no' => $this->company_contact_no,
            'company_email' => $this->company_email,
            'company_vat' => $this->company_vat,
            'company_website' => $this->company_website,
            'profile_state' => $this->profile_state,
            'business_profile_slug' => $this->business_profile_slug,
            'is_active' => (int)$this->user->isActive(),
            'created_at' => $this->created_at,
        ];
    }
}
