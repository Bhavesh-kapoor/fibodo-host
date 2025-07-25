<?php

namespace App\Http\Controllers;

use App\Services\WelcomePageService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WelcomePageController extends Controller
{
    /**
     * @var WelcomePageService
     */
    protected $welcomePageService;
    
    /**
     * Constructor
     * 
     * @param WelcomePageService $welcomePageService
     */
    public function __construct(WelcomePageService $welcomePageService)
    {
        $this->welcomePageService = $welcomePageService;
    }
    
    /**
     * Get welcome pages configuration
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            // Get appId from header or use default value
            $appId = $request->header('appid', 'fibodo');
            
            // Get welcome pages config from service based on appId
            $welcomePagesConfig = $this->welcomePageService->getWelcomePages($appId);

            return response()->success(
                'messages.success',
                $welcomePagesConfig,
                null,
                200
            );
        } catch (Exception $e) {
            return response()->error($e->getMessage(), null, $e->getCode() ?: 400);
        }
    }
} 