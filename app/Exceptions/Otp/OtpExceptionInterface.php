<?php

namespace App\Exceptions\Otp;

interface OtpExceptionInterface
{
    public function getErrorMessage(): string;
}
