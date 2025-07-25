<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class PaymentTransaction extends Model
{
    use HasUlids;

    protected $guarded = [];
}
