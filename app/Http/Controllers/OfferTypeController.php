<?php

namespace App\Http\Controllers;

use App\Http\Resources\OfferTypeResource;
use App\Models\OfferType;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;

class OfferTypeController extends Controller
{
    /**
     * Get all offer types with pagination
     *
     * @return AnonymousResourceCollection|JsonResponse
     */
    public function index(): AnonymousResourceCollection|JsonResponse
    {
        try {
            $offerTypes = OfferType::paginate(20);
            return Response::success('messages.success', OfferTypeResource::collection($offerTypes));
        } catch (Exception $e) {
            Log::error('Error fetching offer types: ' . $e->getMessage());
            return Response::error($e->getMessage(), null, $e->getCode());
        }
    }

    /**
     * Get a specific offer type
     *
     * @param string $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        try {
            $offerType = OfferType::findOrFail($id);
            return Response::success('messages.success', new OfferTypeResource($offerType));
        } catch (Exception $e) {
            Log::error('Error fetching offer type: ' . $e->getMessage());
            return Response::error($e->getMessage(), null, $e->getCode());
        }
    }
}
