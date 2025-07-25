<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'user' => new UserResource($this),
            'auth' => [
                'token' => $this->auth->accessToken,
                'token_type' => 'Bearer',
                'expires_at' => $this->auth->token->expires_at->toISOString()
            ]
        ];
    }
}
