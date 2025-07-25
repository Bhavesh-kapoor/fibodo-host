<?php

namespace App\Http\Controllers;

use App\Http\Resources\ActivityTypeResource;
use App\Models\ActivityType;

class ActivityTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            return response()->success(
                'messages.success',
                ActivityTypeResource::collection(ActivityType::Active()->paginate())
            );
        } catch (\Exception $e) {
            return response()->error(
                $e->getMessage(),
                null,
                $e->getCode()
            );
        }
    }
}
