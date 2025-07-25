<?php

namespace App\Http\Resources;

use App\Enums\TransactionType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {

        return [
            // Basic Information
            'id' => $this->id,
            'booking_id' => $this->booking_id,
            'client_id' => $this->client_id,
            'host_id' => $this->host_id,
            'transaction_type' => [
                'id' => $this->transaction_type->value,
                'label' => $this->transaction_type->label(),
            ],
            'transaction_status' => $this->transaction_status,
            'payment_method_id' => $this->payment_method_id,
            'amount' => $this->amount,
            'paid_at' => $this->paid_at,
            'notes' => $this->notes,

            // Payment Information
            // 'payment' => [
            //     'payment_method_id' => $this->payment_method_id,
            //     'payment_method' => $this->paymentMethod?->name,
            //     'status' => $this->payment_status,
            //     'sub_total' => $this->sub_total,
            //     'tax_amount' => $this->tax_amount,
            //     'discount_amount' => $this->discount_amount,
            //     'total_amount' => $this->total_amount,
            //     'confirmed_at' => $this->confirmed_at,
            // ],

            // Related Resources
            'booking' => new BookingResource($this->whenLoaded('booking')),
            'client' => new UserResource($this->whenLoaded('client')),
            'host' => new UserResource($this->whenLoaded('host')),

            // Timestamps
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'confirmed_at' => $this->confirmed_at,
            'cancelled_at' => $this->cancelled_at,
        ];
    }
}
