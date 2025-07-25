<?php

return [
    /*
    |--------------------------------------------------------------------------
    | App Configurations
    |--------------------------------------------------------------------------
    |
    | This file contains configuration settings for different app versions.
    | 'default' is used when no specific app configuration is found.
    |
    */
    
    'default' => [
        'branch_driven' => 0,
        'need_signup' => 1,
        'must_login' => 1,
        'need_gender' => 1,
        'need_dob' => 1,
        'need_cards' => 1,
        'need_family' => 1,
        'welcome_pages' => [
            'timing' => 8,
            'colors' => [
                'title' => '#FFFFFF',
                'text' => '#FFFFFF'
            ]
        ]
    ],
    
    'host' => [
        'branch_driven' => 0,
        'need_signup' => 0,
        'must_login' => 1,
        'need_gender' => 0,
        'need_dob' => 0,
        'need_cards' => 0,
        'need_family' => 0,
        'welcome_pages' => [
            'timing' => 5,
            'colors' => [
                'title' => '#4A90E2',
                'text' => '#FFFFFF'
            ]
        ]
    ],
    
    'fibodo' => [
        'branch_driven' => 1,
        'need_signup' => 1,
        'must_login' => 1,
        'need_gender' => 1,
        'need_dob' => 1,
        'need_cards' => 1,
        'need_family' => 1,
        'welcome_pages' => [
            'timing' => 8,
            'colors' => [
                'title' => '#F5A623',
                'text' => '#FFFFFF'
            ]
        ]
    ],
]; 