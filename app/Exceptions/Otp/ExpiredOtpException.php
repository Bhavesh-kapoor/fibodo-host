<?php

namespace App\Exceptions\Otp;

use Exception;
use Illuminate\Support\Facades\Lang;

class ExpiredOtpException extends Exception implements OtpExceptionInterface
{
    public function getErrorMessage(): string
    {
        return Lang::get('otp.expired');
    }
}
