<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\Schedules\Schedule;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Product extends Model implements HasMedia
{
    use HasFactory, HasUlids, SoftDeletes, InteractsWithMedia;

    // CONSTANTS
    const STATUS_UNPUBLISH = 0;
    const STATUS_PUBLISH = 1;
    const STATUS_DRAFT = 2;
    const STATUS_REVIEW = 3;

    // Product BUILD stage status
    const STATUS_OVERVIEW = 4;
    const STATUS_PRICING = 5;
    const STATUS_LOCATION = 6;
    const STATUS_SCHEDULING = 7;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The array of booted models.
     *
     * @var array
     */
    protected static function booted() {}


    /**
     * registerMediaCollections
     *
     * @return void
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('products/landscape')
            ->useFallbackUrl(asset('assets/media/default.svg'))
            ->singleFile();

        $this->addMediaCollection('products/portrait')
            ->useFallbackUrl(asset('assets/media/default.svg'))
            ->singleFile();

        $this->addMediaCollection('products/gallery')
            ->useFallbackUrl(asset('assets/media/default.svg'));
    }

    /**
     * isPublished
     *
     * @return bool
     */
    public function isPublished(): bool
    {
        return $this->published_at !== null;
    }


    /**
     * Activities
     *
     * @return HasMany
     */
    public function activities(): HasMany
    {
        return $this->HasMany(Activity::class);
    }

    /**
     * productType
     *
     * @return BelongsTo
     */
    public function productType(): BelongsTo
    {
        return $this->belongsTo(ProductType::class);
    }

    /**
     * activityType
     *
     * @return BelongsTo
     */
    public function activityType(): BelongsTo
    {
        return $this->belongsTo(ActivityType::class);
    }

    /**
     * category
     *
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * subCategory
     *
     * @return BelongsTo
     */
    public function subCategory(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'sub_category_id');
    }


    /**
     * schedule
     *
     * @return HasMany
     */
    public function schedule(): HasOne
    {
        return $this->HasOne(Schedule::class);
    }

    /**
     * Define the relationship to published schedules only
     */
    public function publishedSchedule(): HasOne
    {
        return $this->schedule()->where('status', 1);
    }

    /**
     * Attribute to check if the product has any published schedule
     */
    public function getHasPublishedScheduleAttribute(): bool
    {
        return $this->publishedSchedule()->exists();
    }

    /**
     * Scope to filter products that have published schedules
     */
    public function scopeHasPublishedSchedule($query)
    {
        return $query->whereHas('schedule', function ($query) {
            $query->where('status', 'published');
        });
    }


    /**
     * Policies
     *
     * @return BelongsToMany
     */
    public function policies(): BelongsToMany
    {
        return $this->belongsToMany(Policy::class, 'policy_product', 'product_id', 'policy_id');
    }

    /**
     * Forms
     *
     * @return BelongsToMany
     */
    public function forms(): BelongsToMany
    {
        return $this->belongsToMany(Form::class, 'form_product', 'product_id', 'form_id');
    }


    /**
     * isArchived
     *
     * @return bool
     */
    public function isArchived(): bool
    {
        return null !== $this->archived_at;
    }


    /**
     * archive
     *
     * @return void
     */
    public function markAsArchive()
    {
        if ($this->isArchived()) return;

        $this->archived_at = Carbon::now();
        $this->save();
    }

    /**
     * restore
     *
     * @return void
     */
    public function restore()
    {
        if (!$this->isArchived()) return;

        $this->archived_at = null;
        if (self::STATUS_PUBLISH === $this->status && null === $this->published_at)
            $this->published_at = Carbon::now();

        $this->save();
    }


    /**
     * scopePublished
     *
     * @param  mixed $query
     * @return Builder
     */
    public function scopePublished($query): Builder
    {
        return $query->whereNotNull('published_at')
            ->where('status', '=', self::STATUS_PUBLISH);
    }

    /**
     * scopeArchived
     *
     * @param  mixed $query
     * @return Builder
     */
    public function scopeArchived($query): Builder
    {
        return $query->whereNotNull('archived_at');
    }

    /**
     * scopeUnarchived
     *
     * @param  mixed $query
     * @return Builder
     */
    public function scopeUnarchived($query): Builder
    {
        return $query->whereNull('archived_at');
    }


    /**
     * scopeOrderByIncomplete
     *
     * @param  mixed $query
     * @param  mixed $order
     * @return Builder
     */
    public function scopeOrderByIncomplete($query, $order = 'DESC'): Builder
    {
        $incompleteStatus = implode(",", [
            self::STATUS_OVERVIEW,
            self::STATUS_PRICING,
            self::STATUS_LOCATION,
            self::STATUS_SCHEDULING
        ]);

        return $query->orderByRaw("CASE WHEN status in($incompleteStatus) THEN 0 ELSE 1 END")
            ->orderBy('status', $order);
    }


    /**
     * scopeSearch
     *
     * @param  mixed $query
     * @param  mixed $s
     * @return Builder
     */
    public function scopeSearch($query, $s): Builder
    {
        return $query->where('title', 'like', '%' . $s . '%')
            ->orWhere('sub_title', 'like', '%' . $s . '%');
    }

    /**
     * markAsPublished
     *
     * @return void
     */
    public function markAsPublished()
    {
        if ($this->isPublished()) return;

        $this->status = self::STATUS_PUBLISH;
        $this->published_at = Carbon::now();
        $this->save();
    }
}
