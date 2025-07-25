<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Notifications\Notifiable;

class Client extends Model
{
    use HasFactory, Notifiable, SoftDeletes, HasUlids;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'mobile_number',
        'code',
        'host_id',
        'user_id'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    protected $table = 'users';


    /**
     * Get the host that added this client.
     */
    public function hosts(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'host_client', 'client_id', 'host_id');
    }

    /**
     * Get the user associated with this client.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id', 'id');
    }

    /**
     * Get all of the bookings for the client.
     */
    public function bookings(): HasManyThrough
    {
        return $this->hasManyThrough(Booking::class, Attendee::class, 'client_id', 'id', 'id', 'booking_id');
    }

    /**
     * Get the attendees for the client.
     */
    public function attendees(): HasMany
    {
        return $this->hasMany(Attendee::class);
    }


    /**
     * Scope a query to only include clients for a specific host.
     *
     * @param Builder $query
     * @param string $hostId
     * @return Builder
     */
    public function scopeWithHost(Builder $query, string $hostId): Builder
    {
        return $query->join('users', 'users.id', '=', 'clients.user_id')
            ->where('clients.host_id', $hostId);
    }
    /**
     * Check if a client exists with the same host and email.
     *
     * @param string $hostId
     * @param string $email
     * @param string $mobileNumber
     * @param string $userId
     * @return bool
     */
    public static function existWithHost(string $hostId, string $email, ?string $mobileNumber, $userId = null): bool
    {
        return self::where(function ($query) use ($email, $mobileNumber) {
            $query->where('email', $email)
                ->when($mobileNumber, function ($q) use ($mobileNumber) {
                    $q->orWhere('mobile_number', $mobileNumber);
                });
        })
            ->join('host_client', function ($join) use ($hostId) {
                $join->on('host_client.client_id', '=', 'users.id')
                    ->where('host_client.host_id', $hostId);
            })->exists();
    }
}
