<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use App\Services\VideoService;
use App\Http\Requests\Video\VideoRequest;

class VideoController extends Controller
{
    use AuthorizesRequests;

    /**
     * @var VideoService
     */
    protected $service;

    /**
     * Constructor
     * 
     * @param VideoService $service
     */
    public function __construct(VideoService $service)
    {
        $this->service = $service;

        // Authorize resource actions
        //$this->authorizeResource(Video::class, 'video');
    }

    /**
     * Display a listing of the videos on basis of channel. //johnson digital as of now
     *
     * @param VideoRequest $request
     * @return JsonResponse
     */
    public function index(VideoRequest $request): JsonResponse
    {
        try {
            return Response::success(
                'messages.success',
                $this->service->get($request->validated())
            );
        } catch (Exception $e) {
            throw $e;
            return Response::error($e->getMessage(), null, $e->getCode() ?: 400);
        }
    }

    
}
