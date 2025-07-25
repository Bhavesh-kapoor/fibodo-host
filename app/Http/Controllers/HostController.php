<?php

namespace App\Http\Controllers;

use App\Http\Requests\Host\HostUpdateRequest;
use App\Http\Requests\Host\SignupRequest;
use App\Http\Requests\Media\GetMediaRequest;
use App\Http\Requests\Media\HostMediaRequest;
use App\Http\Resources\HostResource;
use App\Http\Resources\MediaResource;
use App\Models\Host;
use App\Services\HostService;
use App\Services\MediaService;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Response;

class HostController extends Controller
{
    protected $service;
    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        // initialize the service
        $this->service = new HostService();
    }

    /**
     * Host signup
     *
     * @return void
     */
    public function signup(SignupRequest $request, HostService $hostService)
    {
        try {
            return Response::success(
                'messages.success',
                $hostService->signup(),
                null,
                HttpResponse::HTTP_CREATED
            );
        } catch (Exception $e) {
            // Send error response
            return Response::error($e->getMessage(), null, $e->getCode());
        }
    }

    /**
     * getProfle
     *
     * @return void
     */
    public function getProfle()
    {
        try {
            return response()->success('messages.success', new HostResource(Auth::user()->host()->with('user', 'media')->first()), null, 200);
        } catch (Exception $e) {
            return response()->error($e->getMessage(), null, $e->getCode());
        }
    }

    /**
     * updateProfile
     *
     * @param  mixed $request
     * @return void
     */
    public function updateProfile(HostUpdateRequest $request)
    {
        try {
            return response()->success('messages.updated', $this->service->update($request, Auth::user()->host), null, 201);
        } catch (Exception $e) {
            return response()->error($e->getMessage(), null, $e->getCode());
        }
    }

    /**
     * storeMedia
     *
     * @param HostMediaRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeMedia(HostMediaRequest $request, MediaService $mediaService): \Illuminate\Http\JsonResponse
    {
        try {
            return response()->success('media.uploaded', $mediaService->upload($request, Auth::user()->host), null, 201);
        } catch (Exception $e) {
            return response()->error($e->getMessage(), null, $e->getCode());
        }
    }


    /**
     * getMedia
     *
     * @param  GetMediaRequest $request
     * @param  MediaService $mediaService
     * @return Illuminate\Http\JsonResponse
     */
    public function getMedia(GetMediaRequest $request,  MediaService $mediaService): \Illuminate\Http\JsonResponse
    {
        try {
            return response()->success('messages.success', $mediaService->getMedia(Auth::user()->host));
        } catch (Exception $e) {
            return response()->error($e->getMessage(), null, $e->getCode());
        }
    }
}
