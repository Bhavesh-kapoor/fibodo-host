<?php

namespace App\Models;

use App\Traits\GeneratesUniqueCode;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements HasMedia
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasUlids, HasApiTokens, HasRoles, HasPermissions, InteractsWithMedia, GeneratesUniqueCode, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public const STATUS_ACTIVE = 1;
    public const STATUS_DRAFT = 0;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::creating(function ($user) {
            if (empty($user->code)) {
                $user->code = static::generateUniqueCode();
            }
        });
    }

    /**
     * Relationship with host
     *
     * @return HasOne
     */
    public function host(): HasOne
    {
        return $this->hasOne(Host::class);
    }

    /**
     * Get the host that added this client.
     */
    public function hosts(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'host_client', 'client_id', 'host_id');
    }


    /**
     * Relationship with host
     *
     * @return HasOne
     */
    public function otp(): HasOne
    {
        return $this->hasOne(Otp::class);
    }

    /**
     * scopeActive
     *
     * @param  mixed $query
     * @return Builder
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }


    /**
     * Get the user's full name.
     */
    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn() => ucfirst($this->first_name) . " " . ucfirst($this->last_name),
        );
    }

    /**
     * isActive
     *
     * @return Boolean
     */
    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    /**
     * getUserByEmail
     *
     * @param  mixed $email
     * @return User
     */
    public static function getUserByEmail(string $email, bool $active = true): User
    {
        $user = User::where('email', $email);
        if ($active) $user->active();
        return $user->first();
    }

    /**
     * Mark the user as active
     *
     * @return void
     */
    public function markActive(): void
    {
        $this->status = self::STATUS_ACTIVE;
        $this->save();
    }

    /**
     * products
     *
     * @return HasMany
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
     * isProductOwner
     *
     * @param  mixed $product_owner_id
     * @return bool
     */
    public function isProductOwner($product_owner_id): bool
    {
        return $this->id === $product_owner_id;
    }

    /**
     * isHost
     *
     * @return bool
     */
    public function isHost(): bool
    {
        return $this->hasRole('host');
    }

    /**
     * Activities
     *
     * @return HasMany
     */
    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class);
    }


    /**
     * canAccess
     *
     * @param  mixed $user_id
     * @return bool
     */
    public function canAccess($user_id): bool
    {
        return $this->id === $user_id;
    }

    /**
     * Relationship with client
     *
     * @return HasOne
     */
    public function client(): HasOne
    {
        return $this->hasOne(Client::class, 'id', 'id');
    }

    /**
     * Get the attendees for the client.
     */
    public function attendees(): HasMany
    {
        return $this->hasMany(Attendee::class, 'client_id', 'id');
    }

    /**
     * Scope to get users with bookings for a specific host.
     *
     * @param Builder $query
     * @param string $hostId
     * @return Builder
     */
    public function scopeHasBookingWithHost(Builder $query, string $hostId): Builder
    {
        return $query->whereHas('attendees', function ($query) use ($hostId) {
            $query->where('attendees.host_id', $hostId);
        });
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
        $s = strtolower($s);
        return $query->whereRaw('lower(first_name) like ?', ['%' . $s . '%'])
            ->orWhereRaw('lower(last_name) like ?', ['%' . $s . '%'])
            ->orWhereRaw("concat(lower(first_name), ' ', lower(last_name)) like ?", ['%' . $s . '%'])
            ->orWhereRaw('lower(email) like ?', ['%' . $s . '%'])
            ->orWhereRaw('lower(mobile_number) like ?', ['%' . $s . '%']);
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
     * membershipPlans
     *
     * @return HasMany
     */
    public function membershipPlans(): HasMany
    {
        return $this->hasMany(MembershipPlan::class, 'host_id', 'id');
    }

    /**
     * settings
     *
     * @return HasMany
     */
    public function settings(): HasMany
    {
        return $this->hasMany(Setting::class, 'user_id', 'id');
    }

    /**
     * policies
     *
     * @return HasMany
     */
    public function policies(): HasMany
    {
        return $this->hasMany(Policy::class);
    }

    /**
     * cards
     *
     * @return HasMany
     */
    public function cards(): HasMany
    {
        return $this->hasMany(Card::class);
    }
}
