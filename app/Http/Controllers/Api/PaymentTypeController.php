<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\PaymentType;

class PaymentTypeController extends Controller
{
    /**
     * Get all paymentTypes
     */
    public function index(): JsonResponse
    {
        $paymentTypes = PaymentType::all();

        return response()->json([
            'data' => compact('paymentTypes')
        ]);
    }

    /**
     * Get paymentType by id
     */
    public function show(PaymentType $paymentType): JsonResponse
    {
        return response()->json([
            'data' => compact('paymentType')
        ]);
    }
}
