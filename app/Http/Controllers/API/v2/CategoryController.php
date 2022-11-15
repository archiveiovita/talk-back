<?php

namespace App\Http\Controllers\API\v2;

use App\Http\Resources\CategoryResource;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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

    public function getBySlug(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'slug' => 'required',
        ]);

        if ($validator->fails()) {
            $data = ['errors' => $validator->errors()];
            return response()->json($data, 422);
        }

        $alias = stripslashes(trim(htmlspecialchars($request->get('slug'))));
        $category = ProductCategory::where('alias', $alias)->first();

        if (!$category) {
            $data = [ 'errors' => ["slug" => [
                "The category with the slug '" . $alias . "' was not found."
            ]]];

            return response()->json($data, 404);
        }
        return new CategoryResource($category);
    }
}