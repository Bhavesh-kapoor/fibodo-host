<?php

namespace App\Http\Controllers;

use App\Services\AppConfigService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AppConfigController extends Controller
{
    /**
     * @var AppConfigService
     */
    protected $configService;
    
    /**
     * Constructor
     * 
     * @param AppConfigService $configService
     */
    public function __construct(AppConfigService $configService)
    {
        $this->configService = $configService;
    }
    
    /**
     * Get application configuration
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            // Get appId from header or use default value
            $appId = $request->header('appid', 'fibodo');
            
            // Get config from service based on appId
            $config = $this->configService->getConfig($appId);

            return response()->success(
                'messages.success',
                $config
            );
        } catch (Exception $e) {
            return response()->error($e->getMessage(), null, $e->getCode());
        }
    }
} 