<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Policy extends Model
{
    use HasFactory, HasUlids;

    protected $guarded = [];


    protected $casts = [
        'is_global' => 'integer',
        'policy_type' => 'integer',
        'status' => 'integer',
    ];

    /**
     * scopeActive
     *
     * @param  mixed $query
     * @return Builder
     */
    public function scopeActive($query): Builder
    {
        return $query->where('status', 1);
    }

    /**
     * scopeGlobal
     *
     * @param  mixed $query
     * @return Builder
     */
    public function scopeGlobal($query): Builder
    {
        return $query->where('is_global', true);
    }

    /**
     * isActive
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->status;
    }

    /**
     * isGlobal
     *
     * @return bool
     */
    public function isGlobal(): bool
    {
        return $this->is_global;
    }

    /**
     * Products
     *
     * @return BelongsToMany
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class);
    }
}
