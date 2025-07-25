<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttendeeResource extends JsonResource
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
            'booking_id' => $this->booking_id,
            'client_id' => $this->client_id,
            'host_id' => $this->host_id,
            'activity_id' => $this->activity_id,

            // Attendee Information
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'mobile_no' => $this->mobile_number,
            'is_lead_attendee' => $this->is_lead_attendee,
            'notes' => $this->notes,

            // Relationships
            'client' => $this->whenLoaded('client', fn() => new ClientResource($this->client)),
            'host' => $this->whenLoaded('host', fn() => new HostResource($this->host)),
            'booking' => $this->whenLoaded('booking', function () {
                return [
                    'id' => $this->booking->id,
                    'booking_number' => $this->booking->booking_number,
                    'status' => $this->booking->status,
                    'is_walk_in' => $this->booking->is_walk_in,
                    'booking_date' => $this->booking->created_at,
                ];
            }),

            'status' => $this->status,
        ];
    }
}
