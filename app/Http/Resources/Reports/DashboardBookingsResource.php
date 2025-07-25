<?php

namespace App\Http\Resources\Reports;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DashboardBookingsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'total_bookings' => $this['total_bookings'],
            'walk_in_bookings' => $this['walk_in_bookings'],
            'online_bookings' => $this['online_bookings'],
            'graph_data' => $this['graph_data']
        ];
    }
}
