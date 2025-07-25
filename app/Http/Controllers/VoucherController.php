<?php

namespace App\Http\Controllers;

use App\Http\Requests\Voucher\StoreVoucherRequest;
use App\Http\Requests\Voucher\UpdateVoucherRequest;
use App\Http\Requests\Voucher\VoucherRequest;
use App\Models\Voucher;
use App\Services\VoucherService;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;

class VoucherController extends Controller
{
    use AuthorizesRequests;

    /**
     * @var VoucherService
     */
    protected $service;

    /**
     * Constructor
     * 
     * @param VoucherService $service
     */
    public function __construct(VoucherService $service)
    {
        $this->service = $service;

        // Authorize resource actions
        $this->authorizeResource(Voucher::class, 'voucher');
    }

    /**
     * Display a listing of the vouchers.
     *
     * @param VoucherRequest $request
     * @return JsonResponse
     */
    public function index(VoucherRequest $request): JsonResponse
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

    /**
     * Store a newly created voucher in storage.
     *
     * @param StoreVoucherRequest $request
     * @return JsonResponse
     */
    public function store(StoreVoucherRequest $request): JsonResponse
    {
        try {
            return Response::success(
                'messages.created',
                $this->service->create($request->validated()),
                null,
                201
            );
        } catch (Exception $e) {
            return Response::error($e->getMessage(), null, $e->getCode() ?: 400);
        }
    }

    /**
     * Display the specified voucher.
     *
     * @param Voucher $voucher
     * @return JsonResponse
     */
    public function show(Voucher $voucher): JsonResponse
    {
        try {
            return Response::success(
                'messages.success',
                $this->service->find($voucher->id)
            );
        } catch (ModelNotFoundException $e) {
            return Response::error('messages.not_found', null, 404);
        } catch (Exception $e) {
            return Response::error($e->getMessage(), null, $e->getCode() ?: 400);
        }
    }

    /**
     * Update the specified voucher in storage.
     *
     * @param UpdateVoucherRequest $request
     * @param Voucher $voucher
     * @return JsonResponse
     */
    public function update(UpdateVoucherRequest $request, Voucher $voucher): JsonResponse
    {
        try {
            return Response::success(
                'messages.updated',
                $this->service->update($voucher, $request->validated()),
                null,
                200
            );
        } catch (Exception $e) {
            return Response::error($e->getMessage(), null, $e->getCode() ?: 400);
        }
    }

    /**
     * Remove the specified voucher from storage.
     *
     * @param Voucher $voucher
     * @return JsonResponse
     */
    public function destroy(Voucher $voucher): JsonResponse
    {
        try {
            $this->service->delete($voucher);
            return Response::success('messages.deleted', null, null, 200);
        } catch (Exception $e) {
            return Response::error($e->getMessage(), null, $e->getCode() ?: 400);
        }
    }
}
