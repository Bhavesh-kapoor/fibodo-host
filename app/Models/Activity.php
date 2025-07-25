<?php

namespace App\Models;

use App\Enums\BookingStatus;
use App\Models\Schedules\Schedule;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;


class Activity extends Model
{
    use HasFactory, HasUlids, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];


    /**
     * product
     *
     * @return BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->BelongsTo(Product::class);
    }

    /**     
     * attendees
     *
     * @return HasMany
     */
    public function attendees(): HasMany
    {
        return $this->hasMany(Attendee::class);
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
     * host
     *
     * @return BelongsTo
     */
    public function host(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Scope a query to only include active activities.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('activities.status', 1);
    }


    /**
     * isActive
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->status == 1;
    }


    /**
     * hasConflict
     *
     * @return bool
     */
    public static function hasConflicts(string $start_time, string $end_time, ?string $activity_id = null, ?string $product_id = null): bool
    {
        return Activity::where('end_time', '>=', $start_time)
            ->where('start_time', '<=', $end_time)
            ->where('activities.status', 1)
            ->where('user_id', Auth::id())
            ->where('activities.product_id', $product_id)
            ->where('activities.id', '!=', $activity_id)
            ->exists();
    }


    /**
     * getBookedActivities
     *
     * @param string $start_time
     * @param string $end_time
     * @return Collection
     */
    public static function getBookedActivities(string $start_time, string $end_time): Collection
    {
        $bookedActivities = Activity::where('end_time', '>=', $start_time)
            ->where('start_time', '<=', $end_time)
            ->where('activities.status', 1)
            ->where('user_id', Auth::id())
            ->where('seats_booked', '>', 0)
            ->get();

        return $bookedActivities->map(function ($activity) {
            return [
                'id' => $activity->id,
                'product_id' => $activity->product_id,
                'start_time' => $activity->start_time,
                'end_time' => $activity->end_time,
                'seats_booked' => $activity->seats_booked,
            ];
        });
    }


    /**
     * withinTimeOffRange
     *
     * @param  string $start_time
     * @param  string $end_time
     * @param  string $product_id
     * @return boolean
     */
    public static function withinTimeOffRange(string $start_time, string $end_time, ?string $product_id = null): bool
    {
        return Activity::where('user_id', \Auth::id())
            ->when($product_id, fn($q) => $q->where('product_id', $product_id))
            ->where('end_time', '>=', $start_time)
            ->where('start_time', '<=', $end_time)
            ->where(['is_time_off' =>  1, 'status' => 1])
            ->exists();
    }


    /**
     * withinBreakRange
     *
     * @param  string $start_time
     * @param  string $end_time
     * @param  string $product_id
     * @return boolean
     */
    public static function withinBreakRange(string $start_time, string $end_time, ?string $product_id = null): bool
    {
        return Activity::where('end_time', '>=', $start_time)
            ->when($product_id, fn($q) => $q->where('product_id', $product_id))
            ->where('start_time', '<=', $end_time)
            ->where(['is_break' =>  1, 'status' => 1])
            ->exists();
    }


    /**
     * markAsActiveByTimeRange
     *
     * @param  mixed $start_time
     * @param  mixed $end_time
     * @return void
     */
    public static function markAsActiveByTimeRange($start_time, $end_time)
    {
        Activity::where('user_id', \Auth::id())
            ->where('end_time', '>=', $start_time)
            ->where('start_time', '<=', $end_time)
            ->where('is_time_off', '!=', 1)
            //->where('is_break', '!=', 1)
            ->update(['status' => 1]);
    }

    /**
     * markAsActive
     *
     * @return void
     */
    public function markAsActive()
    {
        $this->update(['status' => 1]);
    }

    /**
     * markAsInactive
     *
     * @return void
     */
    public function markAsInactive()
    {
        $this->update(['status' => 0]);
    }


    /**
     * schedule
     *
     * @return BelongsTo
     */
    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    }

    /**
     * Scope a query to only include activities available for booking.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAvailableForBooking($query)
    {
        return $query->active();
    }


    /**
     * Find overlapping time-off
     *
     * @param string $startTime
     * @param string $endTime
     * @param int|null $excludeId
     * @return Activity|null
     */
    public static function findOverlappingTimeOff(string $startTime, string $endTime, ?string $excludeId = null): ?Activity
    {
        return static::where('user_id', Auth::id())
            ->where('is_time_off', 1)
            ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
            ->where(function ($query) use ($startTime, $endTime) {
                $query->where(function ($q) use ($startTime) {
                    // Time-off starts before or at start and ends after start
                    $q->where('start_time', '<=', $startTime)
                        ->where('end_time', '>=', $startTime);
                })->orWhere(function ($q) use ($endTime) {
                    // Time-off starts before end and ends after or at end
                    $q->where('start_time', '<=', $endTime)
                        ->where('end_time', '>=', $endTime);
                })->orWhere(function ($q) use ($startTime, $endTime) {
                    // Time-off is completely within the range
                    $q->where('start_time', '>=', $startTime)
                        ->where('end_time', '<=', $endTime);
                });
            })
            ->first();
    }

    /**
     * Mark overlapping activities as inactive
     *
     * @param string $startTime
     * @param string $endTime
     * @param int|null $excludeId
     * @return int
     */
    public static function markOverlappingActivitiesInactive(string $startTime, string $endTime, ?string $excludeId = null): int
    {
        return static::where('user_id', Auth::id())
            ->where('end_time', '>=', $startTime)
            ->where('start_time', '<=', $endTime)
            ->where('is_time_off', '!=', 1)
            ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
            ->update(['status' => 0]);
    }

    /**
     * cancelActivities
     *
     * @param array $product_ids
     * @param string $startTime
     * @param string $endTime
     * @return void
     */
    public static function cancelActivities(array $product_ids, string $startTime, string $endTime): void
    {
        static::where('user_id', Auth::id())
            ->whereIn('product_id', $product_ids)
            ->where('end_time', '>=', $startTime)
            ->where('start_time', '<=', $endTime)
            ->update(['status' => 0, 'deleted_at' => now()]);
    }

    /**
     * Check if time-off can be posted
     *
     * @param string $startTime
     * @param string $endTime
     * @return bool
     */
    public static function canTimeOffBePosted(string $startTime, string $endTime): bool
    {
        return !static::where('user_id', Auth::id())
            ->where('end_time', '>=', $startTime)
            ->where('start_time', '<=', $endTime)
            ->exists();
    }

    /**
     * bookings
     *
     * @return HasMany
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }


    /**
     * bookingCount
     *
     * @return int
     */
    public function confirmedBookingsCount(): int
    {
        // Count all attendees for confirmed bookings of this activity
        return Attendee::join('bookings', 'attendees.booking_id', '=', 'bookings.id')
            ->where('bookings.activity_id', $this->id)
            ->where('bookings.status', 1)
            ->count();
    }

    /**
     * upcoming
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUpcoming($query)
    {
        return $query->select(
            'activities.id',
            'activities.start_time',
            'activities.seats_booked',
            'activities.seats_available',
            'activities.status',
            'activities.created_at',

            'products.id',
            'products.title',
            'products.sub_title',
            'bookings.activity_end_time as end_time',
        )
            ->where('activities.user_id', Auth::id())
            ->join('bookings', 'activities.id', '=', 'bookings.activity_id')
            ->join('products', 'activities.product_id', '=', 'products.id')
            ->where('bookings.status', BookingStatus::CONFIRMED)
            ->where('bookings.activity_start_time', '>=', now())
            ->orderBy('bookings.activity_start_time', 'asc');
    }
}
