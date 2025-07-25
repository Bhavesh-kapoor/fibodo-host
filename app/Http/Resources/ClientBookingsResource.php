<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientBookingsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $this->load('product');
        return [
            'booking_id' => $this->id,
            'booking_number' => $this->booking_number,
            'activity_id' => $this->activity->id,
            'activity_title' => $this->product->title,
            'activity_start_time' => $this->activity_start_time,
            'activity_end_time' => $this->activity_end_time,

            'booking_status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
