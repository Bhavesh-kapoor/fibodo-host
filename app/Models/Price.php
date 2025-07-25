<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Price extends Model
{
    use HasFactory, HasUlids, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    protected $casts = [
        'price' => 'float',
        'junior_price' => 'float',
        'adult_price' => 'float',
        'senior_price' => 'float',
        'walk_in_price' => 'float',
        'walk_in_junior_price' => 'float',
        'walk_in_adult_price' => 'float',
        'walk_in_senior_price' => 'float',
        'multi_attendee_price' => 'float',
        'all_space_price' => 'float',
    ];
}
