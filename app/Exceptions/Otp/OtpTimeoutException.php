<?php

namespace App\Exceptions\Otp;

use Exception;
use Illuminate\Support\Facades\Lang;

class OtpTimeoutException extends Exception implements OtpExceptionInterface
{
    public function getErrorMessage(): string
    {
        return $this->message ?? Lang::get('otp.resend_timeout', ['seconds' =>  config('otp.resend_timeout')]);
    }
}
