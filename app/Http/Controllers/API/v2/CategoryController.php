<?php

namespace App\Http\Controllers\API\v2;

use App\Http\Resources\CategoryResource;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

class CategoryController
{
    public function index(Request $request): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return CategoryResource::collection(
            ProductCategory::where('parent_id', 0)
                ->orderBy('position', 'asc')
                ->paginate($request->get('limit') ?? 15)
        );
    }

    public function getById($id): CategoryResource
    {
        $category = ProductCategory::findOrFail($id);
        return new CategoryResource($category);
    }
}