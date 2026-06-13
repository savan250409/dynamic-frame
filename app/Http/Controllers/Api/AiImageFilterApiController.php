<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AiImageFilterCategory;
use App\Models\AiImageFilter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AiImageFilterApiController extends Controller
{
    public function getCategories()
    {
        $categories = AiImageFilterCategory::select('id', 'category_name', 'category_image', 'sort_order')
            ->where('status', 1)
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'desc')
            ->get();

        if ($categories->isEmpty()) {
            return response()->json([
                'status'  => false,
                'message' => 'No categories found',
                'data'    => [],
            ], 404);
        }

        $data = $categories->map(function ($category) {
            $filters = AiImageFilter::where('category_id', $category->id)
                ->orderBy('sort_order', 'asc')
                ->orderBy('id', 'desc')
                ->limit(4)
                ->get();

            // Skip categories with no filters
            if ($filters->isEmpty()) {
                return null;
            }

            $filtersData = $filters->map(function ($filter) use ($category) {
                return $this->formatFilter($filter, $category);
            });

            return [
                'id'            => $category->id,
                'category_name' => $category->category_name,
                'thumbnail'     => $category->category_image
                    ? asset('upload/ai_image_filter/' . rawurlencode($category->category_name) . '/thumbnail/' . rawurlencode($category->category_image))
                    : null,
                'filters'       => $filtersData,
            ];
        })->filter()->values();

        if ($data->isEmpty()) {
            return response()->json([
                'status'  => false,
                'message' => 'No categories with filters found',
                'data'    => [],
            ], 404);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Categories fetched successfully',
            'data'    => $data,
        ]);
    }

    public function getFiltersByCategoryId(Request $request)
    {
        $data = $request->isJson() ? $request->json()->all() : $request->all();

        $validator = Validator::make($data, [
            'category_id' => 'required|integer',
        ], [
            'category_id.required' => 'category_id is required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => $validator->errors()->first(),
                'data'    => [],
            ], 422);
        }

        $category = AiImageFilterCategory::where('id', $data['category_id'])
            ->where('status', 1)
            ->first();

        if (!$category) {
            return response()->json([
                'status'  => false,
                'message' => 'Category not found',
                'data'    => [],
            ], 404);
        }

        $filters = AiImageFilter::where('category_id', $category->id)
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'desc')
            ->get();

        if ($filters->isEmpty()) {
            return response()->json([
                'status'  => false,
                'message' => 'No filters found for this category',
                'data'    => [],
            ], 404);
        }

        $transformed = $filters->map(fn($filter) => $this->formatFilter($filter, $category));

        return response()->json([
            'status'        => true,
            'message'       => 'Filters fetched successfully',
            'category_name' => $category->category_name,
            'data'          => $transformed,
        ]);
    }

    private function formatFilter(AiImageFilter $filter, AiImageFilterCategory $category): array
    {
        return [
            'id'       => $filter->id,
            'name'     => $filter->name,
            'ai_prompt' => $filter->ai_prompt,
            'image'    => $filter->image_path
                ? asset('upload/ai_image_filter/' . rawurlencode($category->category_name) . '/images/' . rawurlencode($filter->image_path))
                : null,
            'zip_file' => $filter->zip_file
                ? asset('upload/ai_image_filter/' . rawurlencode($category->category_name) . '/zip/' . rawurlencode($filter->zip_file))
                : null,
        ];
    }
}
