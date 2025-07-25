<?php

namespace App\Models\Schedules;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ScheduleBreak extends Model
{
    use HasFactory, HasUlids, SoftDeletes;

    /**
     * fillable
     *
     * @var array
     */
    protected $fillable = ['name', 'start_time', 'end_time'];

    /**
     * days
     *
     * @return belongsTo
     */
    public function days(): BelongsTo
    {
        return $this->belongsTo(ScheduleDay::class);
    }
}
