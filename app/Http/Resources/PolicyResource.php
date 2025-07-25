<?php

namespace App\Http\Resources;

use App\Enums\PolicyType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PolicyResource extends JsonResource
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
            'title' => $this->title,
            'description' => $this->description,
            'policy_type' => $this->policy_type ? PolicyType::from($this->policy_type)->slug() : null,
            'is_global' => $this->is_global,
            'status' => $this->isActive() ? 1 : 0,
        ];
    }
}
