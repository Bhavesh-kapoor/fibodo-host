<?php

namespace App\Services;

use App\Enums\VoucherStatus;
use App\Http\Resources\VoucherResource;
use App\Models\Voucher;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class VoucherService
{
    /**
     * Get all vouchers with optional filtering and pagination
     *
     * @param array $filters
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function get(array $filters = []): AnonymousResourceCollection
    {
        try {
            $query = Voucher::query()->with(['voucherType', 'host', 'products']);

            // Filter by host
            $query->where('host_id', Auth::id());

            // Filter by voucher type
            if (isset($filters['voucher_type_id'])) {
                $query->where('voucher_type_id', $filters['voucher_type_id']);
            }

            // Filter by status
            if (isset($filters['status'])) {
                $query->where('status', $filters['status']);
            }

            // Filter by active for sale
            if (isset($filters['active_for_sale'])) {
                $query->where('active_for_sale', $filters['active_for_sale']);
            }

            // Filter by available for sale (inventory and expiry)
            if (isset($filters['available_for_sale']) && $filters['available_for_sale']) {
                $query->availableForSale();
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

            // Search by name or code
            if (isset($filters['s']) && !empty($filters['s'])) {
                $search = $filters['s'];
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%")
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

            return VoucherResource::collection($query->paginate($perPage));
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Create a new voucher
     *
     * @param array $data
     * @return VoucherResource
     * @throws Exception
     */
    public function create(array $data): VoucherResource
    {
        try {
            // Generate a unique code if not provided
            if (!isset($data['code'])) {
                $data['code'] = $this->generateUniqueCode();
            }

            // Set host_id if not provided
            if (!isset($data['host_id'])) {
                $data['host_id'] = Auth::user()->id;
            }

            // Explicitly set status if not provided
            if (!isset($data['status'])) {
                $data['status'] = VoucherStatus::ACTIVE->value; // or whatever your default should be
            }

            // Create the voucher
            $voucher = Voucher::create($data);

            // Handle product relationships if 'product_ids' is provided
            if (isset($data['product_ids']) && is_array($data['product_ids'])) {
                $voucher->products()->sync($data['product_ids']);
            }

            // Load relationships
            $voucher->load(['voucherType', 'host', 'products']);

            return new VoucherResource($voucher);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Get a voucher by ID
     *
     * @param string $id
     * @return VoucherResource
     * @throws ModelNotFoundException|Exception
     */
    public function find(string $id): VoucherResource
    {
        try {
            $voucher = Voucher::with(['voucherType', 'host', 'products'])->findOrFail($id);
            return new VoucherResource($voucher);
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Update an existing voucher
     *
     * @param Voucher $voucher
     * @param array $data
     * @return VoucherResource
     * @throws Exception
     */
    public function update(Voucher $voucher, array $data): VoucherResource
    {
        try {
            // Update the voucher
            $voucher->update($data);

            // Handle product relationships if 'product_ids' is provided
            if (isset($data['product_ids']) && is_array($data['product_ids'])) {
                $voucher->products()->sync($data['product_ids']);
            } else {
                // Clear product relationships if 'product_ids' is not provided
                $voucher->products()->detach();
            }

            // Refresh the model with relationships
            $voucher->load(['voucherType', 'host', 'products']);

            return new VoucherResource($voucher);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Delete a voucher
     *
     * @param Voucher $voucher
     * @return bool
     * @throws Exception
     */
    public function delete(Voucher $voucher): bool
    {
        try {
            // Remove product relationships
            $voucher->products()->detach();

            // Delete the voucher (soft delete)
            return $voucher->delete();
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Generate a unique voucher code
     *
     * @return string
     */
    protected function generateUniqueCode(): string
    {
        $attempts = 0;
        $maxAttempts = 10;

        do {
            // Generate a random code
            $code = strtoupper(Str::random(8));

            // Check if code exists
            $exists = Voucher::where('code', $code)->exists();

            $attempts++;

            // Prevent infinite loop
            if ($attempts >= $maxAttempts && $exists) {
                throw new Exception('Failed to generate a unique voucher code after multiple attempts');
            }
        } while ($exists);

        return $code;
    }
}
