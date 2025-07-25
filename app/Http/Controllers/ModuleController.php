<?php

namespace App\Http\Controllers;

use App\Http\Resources\ModuleResource;
use App\Models\Module;
use App\Services\ModuleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    protected $service;

    public function __construct(ModuleService $service)
    {
        $this->service = $service;
    }

    /**
     * GetModules
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            return response()->success("messages.success", $this->service->get());
        } catch (\Exception $e) {
            return response()->error($e->getMessage(), null, $e->getCode());
        }
    }

    /**
     * GetModule
     *
     * @param Module $module
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Module $module): JsonResponse
    {
        try {
            return response()->success("messages.success", new ModuleResource($module));
        } catch (\Exception $e) {
            return response()->error($e->getMessage(), null, $e->getCode());
        }
    }
}
