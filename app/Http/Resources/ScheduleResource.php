<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ScheduleResource extends JsonResource
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
            'product_id' => $this->product_id,
            'recurres_in' => $this->recurres_in,
            'status' => $this->status,
            'weekly_schedules' => $this->whenLoaded('weeklySchedules', fn() => WeeklyScheduleResource::collection($this->weeklySchedules)),
        ];
    }
}
