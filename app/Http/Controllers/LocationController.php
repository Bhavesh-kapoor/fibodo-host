<?php

namespace App\Http\Controllers;

use App\Http\Requests\Location\LocationRequest;
use App\Models\Product;
use App\Services\LocationService;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;


class LocationController extends Controller
{
    use AuthorizesRequests;

    /**
     * service
     *
     * @var mixed
     */
    protected $service;


    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        // authorize
        $this->middleware('can:LocationAccess,product')->only(['storeLocation', 'getLocation']);

        // init
        $this->service = new LocationService();
    }

    /**
     * getLocation
     * @return JsonResponse
     */
    public function getLocation(Product $product): JsonResponse
    {
        try {
            return response()->success('messages.success', $this->service->get($product));
        } catch (Exception $e) {
            return response()->error($e->getMessage(), null, $e->getCode());
        }
    }


    /**
     * storeLocation
     *
     * @param  mixed $request
     * @param  mixed $product
     * @return JsonResponse
     */
    public function storeLocation(LocationRequest $request, Product $product): JsonResponse
    {
        try {
            return response()->success('messages.saved', $this->service->store($request, $product), null, 201);
        } catch (Exception $e) {
            return response()->error($e->getMessage(), null, $e->getCode());
        }
    }
}
