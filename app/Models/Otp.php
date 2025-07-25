<?php

namespace App\Models;

use App\Traits\OtpTrait;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    use HasFactory, OtpTrait, HasUlids;

    const SOURCE_LOGIN = 1;
    const SOURCE_RESET_PASSWORD = 2;
    const VERIFY_EMAIL = 3;


    protected $fillable = [
        'otp',
        'user_id',
        'email',
        'expires_at',
        'source'
    ];
}
