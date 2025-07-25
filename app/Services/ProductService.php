<?php

namespace App\Services;

use App\Http\Requests\Price\PriceRequest;
use App\Http\Resources\PriceResource;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\Schedules\Schedule;
use DB;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class ProductService
{

    /**
     * get
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection<ProductResource>
     */
    public function get(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        try {

            $sortBy = request()->input('sort_by', ['created_at']);
            $sortOrder = request()->input('sort_order', ['desc']);
            $perPage = request()->input('per_page', 15);
            $is_archived = request()->input('archive', 0);

            return ProductResource::collection(
                Auth::user()
                    ->products()
                    ->when($is_archived, fn($q) => $q->Archived())
                    ->when(!$is_archived, fn($q) => $q->Unarchived())
                    ->when(request()->has('product_type_id'), fn($q) => $q->where('product_type_id', request('product_type_id')))
                    ->when(request()->has('s'), fn($q) => $q->search(request('s')))
                    ->when(!empty($sortBy), function ($q) use ($sortBy, $sortOrder) {
                        foreach ($sortBy as $index => $column) {
                            $order = $sortOrder[$index] ?? 'asc'; // Default to 'asc' if not provided
                            switch ($column) {
                                case 'incomplete':
                                    $q->OrderByIncomplete($order);
                                    break;
                                case 'price':
                                    $q->orderBy('adult_price', $order);
                                    break;
                                default:
                                    $q->orderBy($column, $order);
                            }
                        }
                    })
                    ->with(['media' => function ($query) {
                        $query->whereIn('collection_name', ['products/landscape', 'products/portrait']);
                    }])
                    ->paginate($perPage)
            );
        } catch (\Exception $e) {
            throw $e;
        }
    }


    /**
     * selectable
     *
     * @return Illuminate\Support\Collection
     */
    public function selectable(): \Illuminate\Support\Collection
    {
        try {

            $sortBy = request()->input('sort_by', 'created_at');
            $sortOrder = request()->input('sort_order', 'desc');
            $perPage = request()->input('per_page', 15);
            $schedules = request()->input('schedules', 0);

            $products =
                Auth::user()
                ->products()
                ->select('id', 'title', 'status', 'published_at')
                ->published()
                ->when(request()->has('s'), fn($q) => $q->search(request('s')))
                ->orderBy($sortBy, $sortOrder)
                ->paginate($perPage);

            // load schedules if needed
            if ($schedules) $products->load(['schedule.weeklySchedules']);
            return $products->map(fn($product) => (new ProductResource($product))->selectable());
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * create
     *
     * @return ProductResource
     */
    public function create(): ProductResource
    {
        try {
            $products = Product::create([
                'title' => request()->title,
                'product_type_id' => request()->product_type_id,
                'user_id' => Auth::id(),
                'status' => Product::STATUS_OVERVIEW
            ]);
            return new ProductResource($products);
        } catch (\Exception $e) {
            throw $e;
        }
    }


    /**
     * prepareProductUpdateData
     *
     * @param Product $product
     * @param  mixed $data
     * @return array
     */
    public function prepareProductUpdateData(Product $product, array $data): array
    {
        $attendee_settings = $data['attendee_settings'] ?? [];
        $price_settings = $data['price_settings'] ?? [];
        $location_settings = $data['location_settings'] ?? [];

        // unset extra keys 
        unset(
            $data['attendee_settings'],
            $data['price_settings'],
            $data['location_settings'],
            $data['forms'],
            $price_settings['policies']
        );

        // map attendee settings 
        if ($attendee_settings && !$attendee_settings['has_age_restriction'])
            $attendee_settings['age_below'] = $attendee_settings['age_above'] = null;

        if (request()->has('price_settings.is_age_sensitive') && request('price_settings.is_age_sensitive') !== 1)
            $attendee_settings['junior_price'] = $attendee_settings['adult_price'] = $attendee_settings['senior_price'] = null;

        if (request()->has('price_settings.is_walk_in_age_sensitive') && request('price_settings.is_walk_in_age_sensitive') !== 1)
            $attendee_settings['walk_in_junior_price'] = $attendee_settings['walk_in_adult_price'] = $attendee_settings['walk_in_senior_price'] = null;

        // set status based on settings
        $status = $product->status == 1 ? 1 : (!empty($location_settings)
            ? Product::STATUS_SCHEDULING
            : (!empty($price_settings) ? Product::STATUS_LOCATION : Product::STATUS_PRICING));


        // return mapped data
        return array_merge(
            $data,
            $attendee_settings,
            $price_settings,
            $location_settings,
            [
                'status' => request('status') ?? $status,
                'published_at' => request('status') == Product::STATUS_PUBLISH ? now() : null
            ],
        );
    }

    /**
     * update
     *
     * @return ProductResource
     */
    public function update(Product $product, array $data): ProductResource
    {
        try {

            // Update product with settings
            $product->update($this->prepareProductUpdateData($product, $data));

            // Attach forms & refund policies only if they exist in the request
            if (request()->has('forms'))
                $product->forms()->sync(request('forms', []));

            if (request()->has('price_settings.policies'))
                $product->policies()->sync(request('price_settings.policies', []));

            // mark as publish and fire events if required in future
            // if (request()->has('status') && request('status') == Product::STATUS_PUBLISH)
            //     $product->markAsPublished();

            // Load relationships to avoid N+1 problem
            $product->loadMissing(['forms', 'policies']);

            return new ProductResource($product);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * storePriceSettings
     *
     * @param  mixed $request
     * @param  mixed $product
     * @return PriceResource
     */
    public function storePriceSettings(PriceRequest $request, Product $product): PriceResource
    {
        try {
            if (!empty($request->validated())) {

                // set data to update 
                $data = $request->validated();
                if ($request->has('is_age_sensitive') && $request->get('is_age_sensitive') !== 1) {
                    $data['junior_price'] = $data['adult_price'] = $data['senior_price'] = null;
                }
                if ($request->has('is_walk_in_age_sensitive') && $request->get('is_walk_in_age_sensitive') !== 1) {
                    $data['walk_in_junior_price'] = $data['walk_in_adult_price'] = $data['walk_in_senior_price'] = null;
                }
                $product->update($data + ['status' => Product::STATUS_LOCATION]);
                return new PriceResource($product);
            }
            throw new \Exception('messages.not_found', Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            throw $e;
        }
    }


    /**
     * destroy
     *
     * @param  mixed $product
     * @return bool
     */
    public function destroy(Product $product): bool
    {
        try {
            // Start a transaction
            //TODO: implement transaction logic , 
            // if needed or not when using 'deleting' event and any of realtion deletion fails ?

            //TODO: will be implemented in future when needed
            //DB::beginTransaction();

            // Detach all policies first
            $product->policies()->detach();
            $product->forms()->detach();


            // Softdelete or force delete based on status 
            $product->isPublished() ? $product->delete() : $product->forceDelete();


            // save record
            //DB::commit();

            return true;
        } catch (Exception $e) {
            // Rollback the transaction and handle validation errors
            //DB::rollBack();
            throw $e;
        }
    }

    /**
     * getPricePerSeat
     *
     * @param  mixed $product
     * @param  mixed $no_of_seats
     * @param  mixed $schedule
     * @return int
     */
    public function getPricePerSeat(Product $product, $is_walk_in = false): int
    {
        try {

            // get booking pricing
            $price_per_seat = $this->getPrice($product, $is_walk_in);

            return $price_per_seat;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * getPrice
     *
     * @param  mixed $price_settings
     * @param  mixed $age
     * @param  mixed $is_walk_in
     * @return int
     */
    public function getPrice($product, $is_walk_in = false): int
    {
        // get booking pricing
        if ($product->is_walk_in_pricing == 1 && $is_walk_in) {
            $price = $this->getWalkInPrice($product);
        } else {
            $price = $this->getBookingPrice($product, $is_walk_in);
        }

        return $price;
    }

    /**
     * getWalkInPrice
     *
     * @param  Product $product
     * @return int
     */
    public function getWalkInPrice(Product $product): int
    {
        $price = 0;
        if ($product->is_walk_in_age_sensitive == 1) {
            $age = request()->age ?? null;
            if ($age >= $product->walk_in_age_above) {
                $price = $product->walk_in_adult_price;
            } elseif ($age <= $product->walk_in_age_below) {
                $price = $product->walk_in_junior_price;
            } else {
                $price = $product->walk_in_senior_price;
            }
        } else {
            $price = $product->walk_in_price;
        }

        return (float)$price;
    }


    /**
     * getBookingPrice
     *
     * @param  mixed $price_settings
     * @param  mixed $age
     * @return int
     */
    public function getBookingPrice(Product $product, $age = null): int
    {
        $price = 0;
        if ($product->is_age_sensitive == 1) {
            if ($age >= $product->age_above) {
                $price = $product->adult_price;
            } elseif ($age <= $product->age_below) {
                $price = $product->junior_price;
            } else {
                $price = $product->senior_price;
            }
        } else {
            $price = $product->price;
        }

        return $price;
    }


    /**
     * validateProducts
     * @param  array $product_ids Array of product IDs to validate
     * @return array
     * @throws ModelNotFoundException If any product is not found or not published
     */
    public function validateProducts($product_ids): array
    {
        try {
            return array_map(function ($id) {
                return Product::published()->findOrFail($id);
            }, $product_ids);
        } catch (Exception $e) {
            throw $e;
        }
    }
}
