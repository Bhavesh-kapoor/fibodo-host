<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductTypeResource;
use App\Models\ProductType;
use Exception;
use Illuminate\Http\Request;

class ProductTypeController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            return response()->success(
                'messages.success',
                ProductTypeResource::collection(ProductType::all())
            );
        } catch (Exception $e) {
            return respone()->error($e->getMessage(), null, $e->getCode());
        }
    }
}
