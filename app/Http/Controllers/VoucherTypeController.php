<?php

namespace App\Http\Controllers;

use App\Http\Resources\VoucherTypeResource;
use App\Models\VoucherType;
use App\Services\VoucherTypeService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;


class VoucherTypeController extends Controller
{
    /**
     * @var service
     */
    protected $service;

    /**
     * Constructor
     * 
     * @param VoucherTypeService $service
     */
    public function __construct(VoucherTypeService $service)
    {
        $this->service = $service;
    }

    /**
     * getAllVoucherTypes
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            return Response::success('messages.success', $this->service->get());
        } catch (Exception $e) {
            return Response::error($e->getMessage(), null, $e->getCode() ?: 400);
        }
    }

    /**
     * getVoucherTypeById
     *
     * @param VoucherType $voucherType
     * @return JsonResponse
     */
    public function show(VoucherType $voucherType): JsonResponse
    {
        try {
            // if not active voucher type, return 404
            if (!$voucherType->isActive()) {
                return Response::error('messages.voucher_type_not_found', null, 404);
            }

            return Response::success('messages.success', new VoucherTypeResource($voucherType));
        } catch (Exception $e) {
            return Response::error($e->getMessage(), null, $e->getCode() ?: 400);
        }
    }
}
