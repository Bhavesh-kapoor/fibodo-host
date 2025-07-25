<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Host extends Model implements HasMedia
{
    /** @use HasFactory<\Database\Factories\HostsFactory> */
    use HasFactory, HasUlids, HasSlug, InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $guarded = [];


    const PROFILE_STATE = [
        'BASIC' => 1,
        'ACCOUNT' => 2,
        'BUSINESS' => 3,
        'COMPANY' => 4,
        'COMPLETED' => 5
    ];

    const STATUS = [
        'ACTIVE' => 1,
        'INACTIVE' => 0
    ];


    /**
     * The array of booted models.
     *
     * @var array
     */
    protected static function booted()
    {
        static::created(function ($host) {
            // get permissions for host
            $permissions = config('permissions.host');
            // retrieve all permissions
            $permissionsToAssign = Permission::whereIn('name', array_merge(...array_values($permissions)))->get();

            // Assign permissions to the host's user
            $host->user->givePermissionTo($permissionsToAssign);
        });
    }


    /**
     * registerMediaCollections
     *
     * @return void
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('hosts/avatar')
            ->singleFile();

        $this->addMediaCollection('hosts/cover-image')
            ->singleFile();
    }


    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('business_name')
            ->saveSlugsTo('business_profile_slug')
            ->usingLanguage('en');
        //->doNotGenerateSlugsOnCreate()
    }


    /**
     * user
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * clients
     *
     * @return BelongsToMany
     */
    public function clients(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'host_client', 'host_id', 'client_id');
    }
}
