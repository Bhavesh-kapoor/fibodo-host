<?php

dataset('emailDataset', [
    'is not in correct format' => [
        'data' => ['email' => 'confirmed.test.com'], //not a correct email format, missing @
        'field' => 'email'
    ],
    'and confirmed email does not match' => [
        'data' => [
            'email' => 'confirmed@test.com',
            'confirm_email' => 'notconfirmed@test.com',
        ],
        'field' => 'confirm_email'
    ],

]);
