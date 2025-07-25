<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\PaymentMethodResource;
use App\Models\PaymentMethod;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    /**
     * GetAllActivePaymentMethods.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $paymentMethods = PaymentMethod::active()
            ->orderBy('name')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => PaymentMethodResource::collection($paymentMethods),
        ]);
    }

    /**
     * GetPaymentMethodById.
     *
     * @param  \App\Models\PaymentMethod  $paymentMethod
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(PaymentMethod $paymentMethod): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'data' => new PaymentMethodResource($paymentMethod),
        ]);
    }
} 