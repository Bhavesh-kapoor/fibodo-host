<?php

namespace App\Services;

use App\Models\WelcomePage;

class WelcomePageService
{
    /**
     * Get welcome pages based on appId
     *
     * @param string $appId
     * @return array
     */
    public function getWelcomePages(string $appId): array
    {
        // Get pages from database for this appId
        $pages = WelcomePage::where('app_id', $appId)
                          ->active()
                          ->orderBy('sort_order')
                          ->get();
        
        if ($pages->isNotEmpty()) {
            return $pages->map(function ($page) {
                return [
                    'title' => $page->title,
                    'text' => $page->text
                ];
            })->toArray();
        }
        
        // If no pages found for appId, try to get default pages
        if ($appId !== 'default') {
            $defaultPages = WelcomePage::where('app_id', 'default')
                               ->active()
                               ->orderBy('sort_order')
                               ->get();
            
            if ($defaultPages->isNotEmpty()) {
                return $defaultPages->map(function ($page) {
                    return [
                        'title' => $page->title,
                        'text' => $page->text
                    ];
                })->toArray();
            }
        }
        
        // If still no pages, return minimal default response
        return [
            [
                'title' => 'Welcome',
                'text' => 'Welcome to our application. This is a default welcome message.'
            ]
        ];
    }
} 