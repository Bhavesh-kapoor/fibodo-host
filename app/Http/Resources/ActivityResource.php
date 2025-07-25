<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ActivityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $this->load('product');
        return [
            'id' => $this->id,
            'schedule_id' => $this->schedule_id,
            'schedule_day_id' => $this->schedule_day_id,
            'user_id' => $this->user_id,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'is_break' => $this->is_break,
            'is_time_off' => $this->is_time_off,
            'title' => $this->title,
            'note' => $this->note,

            'seats_booked' => $this->seats_booked,
            'seats_available' => $this->seats_available,

            'product' => $this->getProductResource(),
            'status' => $this->status,


            'created_at' => $this->created_at,
        ];
    }


    /**
     * getProductResource
     *
     * @return void
     */
    public function getProductResource()
    {
        return !$this->product_title ? $this->whenLoaded('product', fn() => [
            'id' => $this->product->id,
            'title' => $this->product->title,
            'sub_title' => $this->product->sub_title,
            'session_duration' => $this->product->session_duration,
            'price_settings' => new PriceResource($this->product)
        ]) : [
            'id' => $this->product_id,
            'title' => $this->product_title,
            'sub_title' => $this->product_sub_title ?? "",
            'session_duration' => $this->product_session_duration ?? 0,
            'price_settings' => new PriceResource($this)
        ];
    }
}
