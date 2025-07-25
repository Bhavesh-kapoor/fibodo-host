<?php
//worldnet variables
return [
    'worldnet_url' => env('WORLDNET_URL', 'https://testpayments.worldnettps.com/merchant/paymentpage'), // Default to 5 if not set
    'worldnet_terminal_id' => env('WORLDNET_TERMINAL_ID', 3113003),
    'worldnet_currency' => env('WORLDNET_CURRENCY', 'GBP'),
    'worldnet_iframe' => env('WORLDNET_IFRAME', 'Y'),
    'worldnet_secret' => env('WORLDNET_SECRET', 'mySharedSecretUSD'),
    'worldnet_securetoken_url' => env('WORLDNET_SECURETOKEN_URL', 'https://testpayments.worldnettps.com/merchant/securecardpage'),
    'worldnet_register_action' => env('WORLDNET_REGISTER_ACTION', 'register'),

];
