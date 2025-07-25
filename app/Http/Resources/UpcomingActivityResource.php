<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UpcomingActivityResource extends JsonResource
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
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'note' => $this->note,
            'seats_booked' => $this->seats_booked,
            'seats_available' => $this->seats_available,

            'product' => [
                'id' => $this->id,
                'title' => $this->title,
                'sub_title' => $this->sub_title,
            ],
            'status' => $this->status,

            'created_at' => $this->created_at,
        ];
    }
}
