<?php

namespace App\Models;

use App\Enums\TransactionType;
use App\Enums\TransactionStatus;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, HasUlids, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'booking_id',
        'client_id',
        'host_id',
        'transaction_type',
        'transaction_status',
        'payment_method_id',
        'amount',
        'paid_at',
        'notes',
    ];

    /**
     * The attributes that should be cast to native types or enums.
     *
     * @var array
     */
    protected $casts = [
        'transaction_type' => TransactionType::class,
        'transaction_status' => TransactionStatus::class,
        'paid_at' => 'datetime',
    ];

    /**
     * Get the booking associated with the transaction.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Get the client associated with the transaction.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    /**
     * Get the host associated with the transaction.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function host(): BelongsTo
    {
        return $this->belongsTo(User::class, 'host_id');
    }

    /**
     * Get the payment method associated with the transaction.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }
}
