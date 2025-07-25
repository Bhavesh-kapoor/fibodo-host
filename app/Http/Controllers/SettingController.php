<?php

namespace App\Http\Controllers;

use App\Http\Requests\Setting\SettingCreateRequest;
use App\Http\Requests\Setting\SettingRequest;
use App\Http\Requests\Setting\SettingUpdateRequest;
use App\Http\Resources\SettingResource;
use App\Models\Setting;
use App\Services\SettingService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class SettingController extends Controller
{
    public function __construct(private SettingService $service) {}


    /**
     * index
     *
     * @return JsonResponse
     */
    public function index(SettingRequest $request): JsonResponse
    {
        try {
            $settings = $this->service->get($request->all());
            return Response::success('messages.success', SettingResource::collection($settings));
        } catch (Exception $e) {
            return Response::error($e->getMessage(), null, $e->getCode() ?: 400);
        }
    }

    /**
     * store
     *
     * @param SettingCreateRequest $request
     * @return JsonResponse
     */
    public function store(SettingCreateRequest $request): JsonResponse
    {
        try {
            return Response::success(
                'messages.created',
                SettingResource::collection($this->service->updateOrCreate($request->all())),
                null,
                201
            );
        } catch (Exception $e) {
            return Response::error($e->getMessage(), null, $e->getCode() ?: 400);
        }
    }

    /**
     * show
     *
     * @param string $key
     * @return JsonResponse
     */
    public function show(string $key): JsonResponse
    {
        try {
            $setting = $this->service->getByKey($key);
            if (!$setting) {
                return Response::error('messages.not_found', null, 404);
            }

            return Response::success(
                'messages.success',
                new SettingResource($setting)
            );
        } catch (Exception $e) {
            return Response::error($e->getMessage(), null, $e->getCode() ?: 400);
        }
    }

    /**
     * showByGroup
     *
     * @param string $group
     * @return JsonResponse
     */
    public function showByGroup(string $group): JsonResponse
    {
        try {
            $settings = $this->service->getByGroup($group);
            return Response::success('messages.success', SettingResource::collection($settings));
        } catch (Exception $e) {
            return Response::error($e->getMessage(), null, $e->getCode() ?: 400);
        }
    }

    /**
     * destroy
     *
     * @param string $key
     * @return JsonResponse
     */
    public function destroy(string $key): JsonResponse
    {
        try {
            $setting = $this->service->getByKey($key);
            if (!$setting) {
                return Response::error('messages.not_found', null, 404);
            }

            return Response::success('messages.deleted', $this->service->delete($setting), null, 200);
        } catch (Exception $e) {
            return Response::error($e->getMessage(), null, $e->getCode() ?: 400);
        }
    }
}
