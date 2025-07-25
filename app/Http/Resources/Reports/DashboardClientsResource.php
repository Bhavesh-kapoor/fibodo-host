<?php

namespace App\Http\Resources\Reports;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DashboardClientsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'new_clients' => $this['new_clients'],
            'total_clients' => $this['total_clients'],
            'percentage_of_total' => $this['percentage_of_total'],
            'growth_rate' => $this['growth_rate'],
            'graph_data' => $this['graph_data'],
        ];
    }
}
