<?php

namespace App\Http\Controllers;

use App\Http\Requests\Media\GetMediaRequest;
use App\Http\Requests\Media\GetProductMediaRequest;
use App\Http\Requests\Media\ProductMediaRequest;
use App\Http\Requests\Product\ProductCreateRequest;
use App\Http\Requests\Product\ProductRequest;
use App\Http\Requests\Product\ProductUpdateRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Services\MediaService;
use App\Services\ProductService;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ProductController extends Controller
{
    use AuthorizesRequests;

    //TODO: check user level permissions:  Gate::authorize('create products', 'web');

    protected $service;

    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        // authorize
        $this->authorizeResource(Product::class, 'product');

        // initialize the service
        $this->service = new ProductService();
    }


    /**
     * GetAllProducts
     *
     * @param  ProductRequest $request
     * @return JsonResponse
     */
    public function index(ProductRequest $request): JsonResponse
    {
        try {
            return response()->success(
                'messages.success',
                $this->service->get()
            );
        } catch (Exception $e) {
            return response()->error($e->getMessage(), null, $e->getCode());
        }
    }


    /**
     * GetSelectableProducts
     *
     * @param  ProductRequest $request
     * @return JsonResponse
     */
    public function selectable(ProductRequest $request): JsonResponse
    {
        try {
            return response()->success(
                'messages.success',
                $this->service->selectable()
            );
        } catch (Exception $e) {
            return response()->error($e->getMessage(), null, $e->getCode());
        }
    }


    /**
     * CreateProduct
     *
     * @param  mixed $request
     * @return JsonResponse
     */
    public function store(ProductCreateRequest $request): JsonResponse
    {
        try {
            return response()->success('messages.success', $this->service->create($request));
        } catch (Exception $e) {
            // Send error response
            return response()->error($e->getMessage(), null, $e->getCode());
        }
    }


    /**
     * getProductDetails
     *
     * @param  mixed $product
     * @return JsonResponse
     */
    public function show(Product $product): JsonResponse
    {
        try {
            $product->load(
                'media',
                'category',
                'subCategory',
                'productType',
                'activityType',
                'policies',
                'schedule',
            );
            return response()->success('messages.success', new ProductResource($product));
        } catch (Exception $e) {
            return response()->error($e->getMessage(), null, $e->getCode());
        }
    }


    /**
     * updateProduct
     *
     * @param  mixed $request
     * @param  mixed $product
     * @return JsonResponse
     */
    public function update(ProductUpdateRequest $request, Product $product): JsonResponse
    {
        try {
            return response()->success('messages.updated', $this->service->update($product, $request->validated()), null, 201);
        } catch (Exception $e) {
            return response()->error($e->getMessage(), null, $e->getCode());
        }
    }


    /**
     * destroyProduct
     *
     * @param  mixed $product
     * @return JsonResponse
     */
    public function destroy(Product $product): JsonResponse
    {
        try {
            $this->service->destroy($product);
            return response()->success('messages.deleted', null);
        } catch (Exception $e) {
            return response()->error($e->getMessage(), null, $e->getCode());
        }
    }


    /**
     * ArchiveProduct
     *
     * @param  mixed $product
     * @return JsonResponse
     */
    public function archive(Product $product): JsonResponse
    {
        try {
            // arvhive the product
            $product->markAsArchive();
            return response()->success('products.archived');
        } catch (Exception $e) {
            return response()->error($e->getMessage(), null, $e->getCode());
        }
    }


    /**
     * RestoreProduct
     *
     * @param  mixed $product
     * @return JsonResponse
     */
    public function restore(Product $product): JsonResponse
    {
        try {
            // restore the product
            $product->restore();
            return response()->success('products.restored');
        } catch (Exception $e) {
            return response()->error($e->getMessage(), null, $e->getCode());
        }
    }


    /**
     * storeMedia
     *
     * @param ProductMediaRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeMedia(ProductMediaRequest $request, Product $product, MediaService $mediaService): \Illuminate\Http\JsonResponse
    {
        try {
            return response()->success('media.uploaded', $mediaService->upload($request, $product), null, 201);
        } catch (Exception $e) {
            return response()->error($e->getMessage(), null, $e->getCode());
        }
    }


    /**
     * getMedia
     *
     * @param  GetMediaRequest $request
     * @param  Product $product
     * @param  MediaService $mediaService
     * @return Illuminate\Http\JsonResponse
     */
    public function getMedia(GetMediaRequest $request, Product $product, MediaService $mediaService): \Illuminate\Http\JsonResponse
    {
        try {
            return response()->success('messages.success', $mediaService->getMedia($product));
        } catch (Exception $e) {
            return response()->error($e->getMessage(), null, $e->getCode());
        }
    }
}
