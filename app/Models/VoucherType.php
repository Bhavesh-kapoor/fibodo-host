<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class VoucherType
 * 
 * Represents different types of vouchers in the system with enhanced features.
 * Voucher types define the behavior, constraints, and settings for vouchers.
 * Types include: Gift Voucher, X for Y Voucher, Multi-Purchase Voucher, and Special Offer Voucher.
 */
class VoucherType extends Model
{
    use HasFactory, SoftDeletes, HasUlids;

    /**
     * The attributes that are mass assignable.
     * These fields can be filled during model creation/update.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'code',
        'description',
        'requires_account',
        'default_expiry_days',
        'settings',
        'status'
    ];

    /**
     * The attributes that should be cast.
     * Defines how values retrieved from the database should be cast to PHP types.
     *
     * @var array
     */
    protected $casts = [
        'requires_account' => 'boolean',
        'status' => 'integer',
        'default_expiry_days' => 'integer',
        'settings' => 'array'
    ];

    /** 
     * Scope to filter active voucher types.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Get the vouchers of this type.
     * Establishes the one-to-many relationship between a voucher type and its vouchers.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function vouchers()
    {
        return $this->hasMany(Voucher::class);
    }

    /**
     * Check if the voucher type is active.
     * A voucher type with status=1 is considered active.
     *
     * @return bool True if the voucher type is active, false otherwise
     */
    public function isActive()
    {
        return $this->status === 1;
    }
}
