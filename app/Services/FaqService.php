<?php

namespace App\Services;

use App\Models\Faq;

class FaqService
{
    /**
     * Get FAQs by app ID
     *
     * @param string $appId
     * @return array
     */
    public function getFaqsByAppId(string $appId): array
    {
        // Retrieve active FAQs for the given app ID, ordered by the 'order' field
        return Faq::where('app_id', $appId)
            ->where('active', true)
            ->orderBy('order')
            ->get(['question', 'answer'])
            ->toArray();
    }
} 