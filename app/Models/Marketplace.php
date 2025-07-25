<?php

namespace App\Models;

use App\Traits\GeneratesUniqueCode;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Marketplace extends Model implements HasMedia
{
    use HasFactory, HasUlids, SoftDeletes, GeneratesUniqueCode, HasSlug, InteractsWithMedia;

    /**
     * The attributes that are not mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

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
     * registerMediaCollections
     *
     * @return void
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('marketplaces/logo')
            ->singleFile();

        $this->addMediaCollection('marketplaces/cover-image')
            ->singleFile();
    }


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
}
