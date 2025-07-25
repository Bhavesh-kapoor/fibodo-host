<?php

namespace App\Models\Schedules;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ScheduleDay extends Model
{
    use HasUlids, HasFactory, SoftDeletes;


    /**
     * fillable
     *
     * @var array
     */
    protected $fillable = ['day_of_week', 'start_time', 'end_time'];


    /**
     * booted
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope('ordered', function (Builder $builder) {
            $builder->orderBy('day_of_week', 'asc');
        });
    }


    /**
     * WeeklySchedules
     *
     * @return BelongsTo
     */
    public function weeklySchedules(): BelongsTo
    {
        return $this->belongsTo(WeeklySchedule::class);
    }

    /**
     * days
     *
     * @return HasMany
     */
    public function breaks(): HasMany
    {
        return $this->hasMany(ScheduleBreak::class);
    }
}
