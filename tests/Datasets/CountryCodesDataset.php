<?php

dataset('countryCodesDataset', [
    'country code is not a number' => [
        'data' => ['country_code' => "IN"],
        'field' => 'country_code'
    ],
    'country code is less than 2 digits' => [
        'data' => ['country_code' => 8],
        'field' => 'country_code'
    ],
    'country code is greter than 3 digits' => [
        'data' => ['country_code' => 1000],
        'field' => 'country_code'
    ]
]);
