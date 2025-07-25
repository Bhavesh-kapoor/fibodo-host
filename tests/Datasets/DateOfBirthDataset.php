<?php

dataset('dateOfBirthDataset', [
    'not in the date format of Y-m-d' => [
        'data' => ['date_of_birth' => '2023-23-01'], // // passing Y-d-m while it expects Y-m-d
        'field' => 'date_of_birth'
    ],
    'not a past date' => [
        'data' => ['date_of_birth' => '2025-01-01'],
        'field' => 'date_of_birth'
    ],
]);
