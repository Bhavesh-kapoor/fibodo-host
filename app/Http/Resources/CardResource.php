<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CardResource extends JsonResource
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
            'card_number' => $this->number,
            'card_holder_name' => $this->holder_name,
            'card_type' => $this->type,
            'card_expiry_date' => $this->expiry,
            'card_holder_email' => $this->holder_email,
            'card_holder_phone' => $this->holder_phone,
            'card_is_stored' => $this->is_stored,
            'card_is_default' => $this->is_default,
            'card_created_at' => $this->created_at,
            'card_updated_at' => $this->updated_at,
        ];
    }
}
