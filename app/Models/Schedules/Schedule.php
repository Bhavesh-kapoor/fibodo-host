<?php

namespace App\Models\Schedules;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Schedule extends Model
{

    use HasFactory, HasUlids, SoftDeletes;

    public const STATUS_ACTIVE = 1;

    /**
     * fillable
     *
     * @var array
     */
    protected $fillable = ['product_id', 'recurres_in', 'status'];


    /**
     * scopeActive
     *
     * @param  mixed $query
     * @return Builder
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('schedules.status', self::STATUS_ACTIVE);
    }

    /**
     * WeeklySchedules
     *
     * @return HasMany
     */
    public function weeklySchedules(): HasMany
    {
        return $this->hasMany(WeeklySchedule::class);
    }


    /**
     * product
     *
     * @return BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
