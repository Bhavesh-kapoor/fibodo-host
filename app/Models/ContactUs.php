<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class ContactUs extends Model
{
    use HasFactory, HasUlids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'host_id',
        'subject',
        'message',
        'status',
    ];

   /**
     * Get the host that added this client.
     */
    public function host()
    {
        return $this->belongsTo(User::class, 'host_id');
    }
} 