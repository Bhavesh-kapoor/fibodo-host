<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $depth = (int) $request->depth ?? 0;
        $parent_id = (int) $request->parent_id ?? null;
        $categories = Category::getNestedCategoriesByDepth($depth, $parent_id)->paginate();

        return response()->success(
            'messages.success',
            CategoryResource::collection($categories),
        );
    }


    /**
     * Display the specified resource.
     */
    public function show(Category $category, Request $request)
    {
        $show_children = $request->get('children', false);

        // Load the 'children' relationship for the given category
        if ($show_children)
            $category->load(['children' => fn($query) => $query->paginate()]);

        return response()->success(
            'messages.success',
            new CategoryResource($category)
        );
    }
}
