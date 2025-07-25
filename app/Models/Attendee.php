<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendee extends Model
{
    use HasFactory, HasUlids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Get the booking that owns the attendee.
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Get the client that owns the attendee.
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id', 'id');
    }

    /**
     * getFullNameAttribute
     *
     * @return string
     */
    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * activity
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function activity(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Activity::class);
    }

    /**
     * Scope a query to only include active attendees.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('attendees.status', 1);
    }
}
