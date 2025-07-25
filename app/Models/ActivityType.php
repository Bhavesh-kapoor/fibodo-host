<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityType extends Model
{
    /** @use HasFactory<\Database\Factories\ActivityTypeFactory> */
    use HasFactory, HasUlids;

    protected $guarded = [];


    public function scopeActive($query): Builder
    {
        return $query->where('status', 1);
    }
}
