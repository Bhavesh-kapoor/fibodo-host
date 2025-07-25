<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WeeklyScheduleResource extends JsonResource
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
            'schedule_id' => $this->schedule_id,
            'name' => $this->name,
            'is_default' => $this->is_default,
            'status' => $this->status,
            'days' => $this->whenLoaded('days', fn() => ScheduleDayResource::collection($this->days)),
        ];
    }
}
