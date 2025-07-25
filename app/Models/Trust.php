<?php

namespace App\Models;

use App\Traits\GeneratesUniqueCode;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Trust extends Model
{
    /** @use HasFactory<\Database\Factories\TrustFactory> */
    use HasFactory, HasUlids, SoftDeletes, GeneratesUniqueCode, HasSlug;

    /**
     * The attributes that are not mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
    ];

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug')
            ->preventOverwrite();
    }

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::creating(function ($model) {
            if (empty($model->code)) {
                $model->code = static::generateUniqueCode();
            }
        });
    }

    /**
     * Get the marketplaces that the trust belongs to.
     */
    public function marketplaces(): BelongsToMany
    {
        return $this->belongsToMany(Marketplace::class, 'marketplace_trust', 'trust_id', 'marketplace_id');
    }

    /**
     * Get the subscribers that the trust has.
     */
    public function subscribers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'marketplace_subscribers', 'trust_id', 'user_id')->withPivot('marketplace_id');
    }
}
