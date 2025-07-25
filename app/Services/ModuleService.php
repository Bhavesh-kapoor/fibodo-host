<?php

namespace App\Services;

use App\Http\Resources\ModuleResource;
use App\Models\Module;

class ModuleService
{

    /*
     * Get all modules
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function get(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        try {
            return ModuleResource::collection(Module::paginate(request()->get('per_page', 15)));
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
