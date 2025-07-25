<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Module extends Model
{
    /** @use HasFactory<\Database\Factories\ModuleFactory> */
    use HasFactory, HasSlug, HasUlids;

    protected $fillable = [];

    /**
     * Get the permission name for this module
     */
    public function getPermissionNameAttribute()
    {
        return 'access-' . $this->slug;
    }

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug')
            ->usingLanguage('en')
            ->doNotGenerateSlugsOnCreate();
    }
}
