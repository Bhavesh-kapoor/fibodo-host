<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
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
            'booking_number' => $this->booking_number,
            'status' => $this->status,
            'notes' => $this->notes,
            'is_walk_in' => (int)$this->is_walk_in,

            // Activity Information
            'activity' => [
                'id' => $this->activity_id,
                'start_time' => $this->activity_start_time,
                'end_time' => $this->activity_end_time,
            ],

            // Product Information
            'product' => [
                'id' => $this->product_id,
                'title' => $this->product_title,
                'type' => $this->product_type,
            ],

            // Booking Details
            'seats_booked' => $this->seats_booked,
            'price_per_seat' => $this->price_per_seat,


            // Payment Information
            'payment' => [
                'payment_method_id' => $this->payment_method_id,
                'payment_method' => $this->whenLoaded('paymentMethod', fn() => $this->paymentMethod?->name),
                'status' => $this->payment_status,
                'sub_total' => $this->sub_total,
                'tax_amount' => $this->tax_amount,
                'discount_amount' => $this->discount_amount,
                'total_amount' => $this->total_amount,
                'confirmed_at' => $this->confirmed_at,
            ],

            // Related Resources
            'transactions' => TransactionResource::collection($this->whenLoaded('transactions')),
            'attendees' => $this->whenLoaded('attendees', fn() =>  AttendeeResource::collection($this->attendees)),
            'client' => $this->whenLoaded('client', fn() => new UserResource($this->client)),
            'host' => $this->whenLoaded('host', fn() => new ClientResource($this->host)),

            // Timestamps
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'confirmed_at' => $this->confirmed_at,
            'cancelled_at' => $this->cancelled_at,
        ];
    }
}
