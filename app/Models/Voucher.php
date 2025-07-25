<?php

namespace App\Models;

use App\Enums\VoucherStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Voucher extends Model
{
    use HasFactory, HasUlids, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'voucher_type_id',
        'host_id',
        'name',
        'code',
        'description',
        'value',
        'pay_for_quantity',
        'get_quantity',
        'is_transferrable',
        'is_gift_eligible',
        'can_combine',
        'inventory_limit',
        'status',
        'expires_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'value' => 'decimal:2',
        'pay_for_quantity' => 'integer',
        'get_quantity' => 'integer',
        'is_transferrable' => 'boolean',
        'is_gift_eligible' => 'boolean',
        'can_combine' => 'boolean',
        'inventory_limit' => 'integer',
        'sold_count' => 'integer',
        'status' => VoucherStatus::class,
        'expires_at' => 'datetime'
    ];

    /**
     * Scope query to only include active vouchers.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', VoucherStatus::ACTIVE);
    }

    /**
     * Scope query to only include vouchers available for sale.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeAvailableForSale(Builder $query): Builder
    {
        return $query->where('status', VoucherStatus::ACTIVE)
            ->where(function ($query) {
                $query->whereNull('inventory_limit')
                    ->orWhereRaw('sold_count < inventory_limit');
            })
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            });
    }

    /**
     * Get the voucher type associated with this voucher.
     *
     * @return BelongsTo
     */
    public function voucherType(): BelongsTo
    {
        return $this->belongsTo(VoucherType::class);
    }

    /**
     * Get the host associated with this voucher.
     *
     * @return BelongsTo
     */
    public function host(): BelongsTo
    {
        return $this->belongsTo(User::class, 'host_id');
    }

    /**
     * Get the products this voucher can be applied to.
     *
     * @return BelongsToMany
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_voucher');
    }

    /**
     * Check if the voucher is active.
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->status === VoucherStatus::ACTIVE;
    }

    /**
     * Check if the voucher is global (applicable to all products).
     *
     * @return bool
     */
    public function isGlobal(): bool
    {
        return $this->products()->count() === 0;
    }

    /**
     * Check if the voucher is available for sale.
     *
     * @return bool
     */
    public function isAvailableForSale(): bool
    {
        if ($this->status !== VoucherStatus::ACTIVE) {
            return false;
        }

        // Check inventory limit if set
        if ($this->inventory_limit !== null && $this->sold_count >= $this->inventory_limit) {
            return false;
        }

        // Check expiry date if set
        if ($this->expires_at !== null && $this->expires_at->isPast()) {
            return false;
        }

        return true;
    }

    /**
     * Check if the voucher is expired.
     *
     * @return bool
     */
    public function isExpired(): bool
    {
        return $this->status === VoucherStatus::EXPIRED ||
            ($this->expires_at && $this->expires_at->isPast());
    }

    /**
     * Get the display text for the X for Y offer.
     *
     * @return string|null
     */
    public function getXforYText(): ?string
    {
        if ($this->pay_for_quantity && $this->get_quantity) {
            $total = $this->pay_for_quantity + $this->get_quantity;
            return "Pay for {$this->pay_for_quantity}, get {$total}";
        }

        return null;
    }

    /**
     * Check if the voucher is applicable to a specific product.
     *
     * @param string $productId
     * @return bool
     */
    public function isApplicableToProduct(string $productId): bool
    {
        // If no products are associated, the voucher is global
        if ($this->isGlobal()) {
            return true;
        }

        // Check if product is in related products
        return $this->products->contains('id', $productId);
    }
}
