<?php

namespace App\Models;

use App\Enums\TargetAudience;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Offer extends Model
{
    use HasFactory, HasUlids, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'offer_type_id',
        'host_id',
        'name',
        'description',
        'value',
        'is_discount',
        'target_audience',
        'apply_to_all_products',
        'terms_conditions',
        'status',
        'starts_at',
        'expires_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'value' => 'decimal:2',
        'is_discount' => 'integer',
        'target_audience' => TargetAudience::class,
        'apply_to_all_products' => 'integer',
        'status' => 'integer',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    /**
     * Scope a query to only include active offers.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 1);
    }

    /**
     * Scope a query to only include available offers.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAvailable(Builder $query): Builder
    {
        return $query->where(function ($q) {
            $q->whereNull('starts_at')
                ->orWhere('starts_at', '<=', now());
        })->where(function ($q) {
            $q->whereNull('expires_at')
                ->orWhere('expires_at', '>', now());
        });
    }

    /**
     * Get the offer type for the offer.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function offerType(): BelongsTo
    {
        return $this->belongsTo(OfferType::class, 'offer_type_id');
    }

    /**
     * Get the host for the offer.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function host(): BelongsTo
    {
        return $this->belongsTo(User::class, 'host_id');
    }

    /**
     * Get the products for the offer.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class);
    }

    /**
     * Check if the offer is active.
     *
     * @return bool
     */
    public function isActive(): bool
    {
        if ($this->status !== 1) {
            return false;
        }

        // Check start date if set
        if ($this->starts_at && $this->starts_at->isFuture()) {
            return false;
        }

        // Check expiry date if set
        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }

        return true;
    }

    /**
     * Check if the offer is applicable to a product.
     *
     * @param string $productId
     * @return bool
     */
    public function isApplicableToProduct(string $productId): bool
    {
        // If apply to all products is true, the offer is global
        if ($this->apply_to_all_products) {
            return true;
        }

        // Check if product is in related products
        return $this->products->contains('id', $productId);
    }
}
