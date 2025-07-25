<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Form extends Model
{
    use HasFactory, HasUlids;

    protected $guarded = [];


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
     * formType
     *
     * @return BelongsTo
     */
    public function formType(): BelongsTo
    {
        return $this->BelongsTo(FormType::class);
    }

    /**
     * Products
     *
     * @return BelongsToMany
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'form_product', 'form_id', 'product_id');
    }
}
