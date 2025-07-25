<?php

namespace App\Models\Schedules;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class WeeklySchedule extends Model
{
    use HasFactory, HasUlids, SoftDeletes;

    /**
     * fillable
     *
     * @var array
     */
    protected $fillable = ['schedule_id', 'name', 'is_default', 'status'];


    /**
     * schedule
     *
     * @return BelongsTo
     */
    public function schedule(): BelongsTo
    {
        return $this->BelongsTo(Schedule::class);
    }

    /**
     * days
     *
     * @return HasMany
     */
    public function days(): HasMany
    {
        return $this->hasMany(ScheduleDay::class);
    }
}
