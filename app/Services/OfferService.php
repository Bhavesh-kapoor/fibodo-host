<?php

namespace App\Services;

use App\Http\Resources\OfferResource;
use App\Models\Offer;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

class OfferService
{
    /**
     * Get all offers with optional filtering and pagination
     *
     * @param array $filters
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function get(array $filters = []): AnonymousResourceCollection
    {
        try {
            $query = Offer::query()->with(['offerType', 'host', 'products']);

            // Filter by host
            $query->where('host_id', Auth::id());

            // Filter by offer type
            if (isset($filters['offer_type_id'])) {
                $query->where('offer_type_id', $filters['offer_type_id']);
            }

            // Filter by status
            if (isset($filters['status'])) {
                $query->where('status', $filters['status']);
            }

            // Filter by active offers
            if (isset($filters['active']) && $filters['active']) {
                $query->active();
            }

            // Filter by available offers (start and expiry dates)
            if (isset($filters['available']) && $filters['available']) {
                $query->available();
            }

            // Filter by expiry status
            if (isset($filters['is_expired'])) {
                if ($filters['is_expired']) {
                    $query->whereNotNull('expires_at')->where('expires_at', '<', now());
                } else {
                    $query->where(function ($q) {
                        $q->whereNull('expires_at')->orWhere('expires_at', '>=', now());
                    });
                }
            }

            // Filter by target audience
            if (isset($filters['target_audience'])) {
                $query->where('target_audience', $filters['target_audience']);
            }

            // Filter by discount type
            if (isset($filters['is_discount'])) {
                $query->where('is_discount', $filters['is_discount']);
            }

            // Search by name or description
            if (isset($filters['s']) && !empty($filters['s'])) {
                $search = $filters['s'];
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            }

            // Sorting
            if (isset($filters['sort_by']) && is_array($filters['sort_by'])) {
                $sortOrders = $filters['sort_order'] ?? [];

                foreach ($filters['sort_by'] as $index => $column) {
                    $direction = $sortOrders[$index] ?? 'asc';
                    $query->orderBy($column, $direction);
                }
            } else {
                // Default sorting
                $query->orderBy('created_at', 'desc');
            }

            // Pagination
            $perPage = $filters['per_page'] ?? 15;

            return OfferResource::collection($query->paginate($perPage));
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Create a new offer
     *
     * @param array $data
     * @return OfferResource
     * @throws Exception
     */
    public function create(array $data): OfferResource
    {
        try {
            // Set host_id if not provided
            if (!isset($data['host_id'])) {
                $data['host_id'] = Auth::user()->id;
            }

            // Create the offer
            $offer = Offer::create($data);

            // Handle product relationships if 'product_ids' is provided
            if (isset($data['product_ids']) && is_array($data['product_ids'])) {
                $offer->products()->sync($data['product_ids']);
            }

            // Load relationships
            $offer->refresh();
            $offer->load(['offerType', 'host', 'products']);

            return new OfferResource($offer);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Get an offer by ID
     *
     * @param string $id
     * @return OfferResource
     * @throws ModelNotFoundException|Exception
     */
    public function find(string $id): OfferResource
    {
        try {
            $offer = Offer::with(['offerType', 'host', 'products'])->findOrFail($id);
            return new OfferResource($offer);
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Update an existing offer
     *
     * @param Offer $offer
     * @param array $data
     * @return OfferResource
     * @throws Exception
     */
    public function update(Offer $offer, array $data): OfferResource
    {
        try {
            // Update the offer
            $offer->update($data);

            // Handle product relationships if 'product_ids' is provided
            if (isset($data['product_ids']) && is_array($data['product_ids'])) {
                $offer->products()->sync($data['product_ids']);
            } else {
                // Clear product relationships if 'product_ids' is not provided
                $offer->products()->detach();
            }

            // Refresh the model with relationships
            $offer->refresh();
            $offer->load(['offerType', 'host', 'products']);

            return new OfferResource($offer);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Delete an offer
     *
     * @param Offer $offer
     * @return bool
     * @throws Exception
     */
    public function delete(Offer $offer): bool
    {
        try {
            // Remove product relationships
            $offer->products()->detach();

            // Delete the offer (soft delete)
            return $offer->delete();
        } catch (Exception $e) {
            throw $e;
        }
    }
}
