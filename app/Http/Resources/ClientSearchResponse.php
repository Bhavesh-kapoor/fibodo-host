<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientSearchResponse extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'client_id' => $this->client_id,
            'client_code' => $this->code,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'mobile_number' => $this->mobile_number,
            'total_activities_booked' => $this->total_activities_booked,
            'bookings' => $this->whenLoaded('attendees.booking', function () {
                return $this->attendees->map(function ($attendee) {
                    return [
                        'booking_number' => $attendee->booking->booking_number,
                        'activity' => [
                            'id' => $attendee->booking->activity->id,
                            'title' => $attendee->booking->product->title,
                            'start_time' => $attendee->booking->activity_start_time,
                            'end_time' => $attendee->booking->activity_end_time,
                        ],
                        'status' => $attendee->booking->status,
                    ];
                });
            }),
        ];
    }
}
