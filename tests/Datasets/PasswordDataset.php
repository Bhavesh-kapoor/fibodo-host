<?php

dataset('passwordDataset', [
    'password is less than 8 characters' => [
        'data' => ['password' => 'Short1!'],
        'field' => 'password',
    ],
    'password does not have an uppercase letter' => [
        'data' => ['password' => 'lowe@#rcase1!'],
        'field' => 'password',
    ],
    'password does not have a lowercase letter' => [
        'data' => ['password' => 'UPPE@RCASE1!'],
        'field' => 'password',
    ],
    'password does not contain a digit' => [
        'data' => ['password' => 'NoNum@#bers!'],
        'field' => 'password',

    ],
    'password does not contain a special character' => [
        'data' => ['password' => 'NoSpecial1'],
        'field' => 'password',
    ],
    'password and confirmed password do not match' => [
        'data' => [
            'password' => 'securepassword123',
            'confirm_password' => 'abc1234',
        ],
        'field' => 'confirm_password',
    ],
]);
