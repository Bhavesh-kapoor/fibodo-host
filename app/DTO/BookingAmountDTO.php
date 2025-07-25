<?php

namespace App\DTO;

class BookingAmountDTO
{
    public float $price_per_seat;
    public float $sub_total;
    public float $tax_amount;
    public float $discount_amount;
    public float $total_amount;

    public function __construct(
        float $price_per_seat,
        float $sub_total,
        float $tax_amount,
        float $discount_amount,
        float $total_amount
    ) {
        $this->price_per_seat = $price_per_seat;
        $this->sub_total = $sub_total;
        $this->tax_amount = $tax_amount;
        $this->discount_amount = $discount_amount;
        $this->total_amount = $total_amount;
    }

    /**
     * Convert the DTO into an array format
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'price_per_seat' => $this->price_per_seat,
            'sub_total' => $this->sub_total,
            'tax_amount' => $this->tax_amount,
            'discount_amount' => $this->discount_amount,
            'total_amount' => $this->total_amount,
        ];
    }
}
