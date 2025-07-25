<?php

return [
    'max_attempts' => env('OTP_MAX_ATTEMPTS', 5), // Default to 5 if not set
    'resend_timeout' => env('OTP_RESEND_TIMEOUT', 120), // Default to 120 seconds (2 minutes)
    'expires_in' => env('OTP_EXPIRES_IN', 5), // Default to 5 minutes
];
