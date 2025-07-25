<?php

namespace App\Http\Controllers;

use App\Services\FaqService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    /**
     * @var FaqService
     */
    protected $faqService;
    
    /**
     * Constructor
     * 
     * @param FaqService $faqService
     */
    public function __construct(FaqService $faqService)
    {
        $this->faqService = $faqService;
    }
    
    /**
     * Get FAQs
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            // Get appId from header or use default value
            $appId = $request->header('appid', 'fibodo');
            
            // Get FAQs from service based on appId
            $faqs = $this->faqService->getFaqsByAppId($appId);

            return response()->success(
                'messages.success',
                $faqs
            );
        } catch (Exception $e) {
            return response()->error($e->getMessage(), null, $e->getCode());
        }
    }
} 