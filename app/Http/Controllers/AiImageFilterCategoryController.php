<?php

namespace App\Http\Controllers;

use App\Models\AiImageFilterCategory;
use App\Models\AiImageFilter;
use App\Support\UploadHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class AiImageFilterCategoryController extends Controller
{
    public function index(Request $request)
    {
        $page    = $request->page ?? 1;
        $search  = $request->input('search', '');
        $perPage = $request->input('per_page', 10);

        if (!$request->ajax() && session()->has('ai_filter_cat_state')) {
            $state   = session('ai_filter_cat_state');
            $page    = $state['page']     ?? 1;
            $search  = $state['search']   ?? '';
            $perPage = $state['per_page'] ?? 10;

            \Illuminate\Pagination\Paginator::currentPageResolver(function () use ($page) {
                return $page;
            });
        } elseif ($request->ajax()) {
            session([
                'ai_filter_cat_state' => [
                    'page'     => $request->page,
                    'search'   => $request->search,
                    'per_page' => $request->per_page,
                ]
            ]);
        }

        $query = AiImageFilterCategory::withCount('filters');
        if ($search) {
            $query->where('category_name', 'like', '%' . $search . '%');
        }
        $categories = $query->orderBy('sort_order', 'asc')
                            ->orderBy('id', 'desc')
                            ->paginate($perPage);
        $categories->appends(['search' => $search, 'per_page' => $perPage]);

        if ($request->ajax()) {
            return view('admin.ai_image_filter_category.index', compact('categories'))->render();
        }

        return view('admin.ai_image_filter_category.index', compact('categories', 'search', 'perPage'));
    }

    public function create()
    {
        return view('admin.ai_image_filter_category.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_name'  => 'required|string|max:255|unique:ai_image_filter_categories,category_name',
            'category_image' => 'required|image|mimes:webp',
        ], [
            'category_image.required' => 'Please upload a thumbnail image.',
            'category_image.mimes'    => 'Only .webp images are allowed.',
        ]);

        $categoryName = $request->category_name;
        $path = public_path('upload/ai_image_filter/' . $categoryName . '/thumbnail');

        if (!File::exists($path)) {
            File::makeDirectory($path, 0777, true, true);
        }

        $imageName = UploadHelper::uniqueName($path, $request->file('category_image')->getClientOriginalName());
        $request->file('category_image')->move($path, $imageName);

        AiImageFilterCategory::create([
            'category_name'  => $categoryName,
            'category_image' => $imageName,
            'sort_order'     => 0,
            'status'         => $request->has('status') ? 1 : 0,
        ]);

        return redirect()->route('ai-image-filter-categories.index')
                         ->with('success', 'Category created successfully.');
    }

    public function edit(AiImageFilterCategory $aiImageFilterCategory)
    {
        return view('admin.ai_image_filter_category.edit', compact('aiImageFilterCategory'));
    }

    public function update(Request $request, AiImageFilterCategory $aiImageFilterCategory)
    {
        $request->validate([
            'category_name'  => 'required|string|max:255|unique:ai_image_filter_categories,category_name,' . $aiImageFilterCategory->id,
            'category_image' => 'nullable|image|mimes:webp',
        ], [
            'category_image.mimes' => 'Only .webp images are allowed.',
        ]);

        $oldName   = $aiImageFilterCategory->category_name;
        $newName   = $request->category_name;
        $imageName = $aiImageFilterCategory->category_image;

        if ($oldName !== $newName) {
            $oldFolder = public_path('upload/ai_image_filter/' . $oldName);
            $newFolder = public_path('upload/ai_image_filter/' . $newName);
            if (File::exists($oldFolder) && !File::exists($newFolder)) {
                File::move($oldFolder, $newFolder);
            }
        }

        if ($request->hasFile('category_image')) {
            // Delete old image
            if ($aiImageFilterCategory->category_image) {
                $oldFile = public_path('upload/ai_image_filter/' . $newName . '/thumbnail/' . $aiImageFilterCategory->category_image);
                if (File::exists($oldFile)) {
                    File::delete($oldFile);
                }
            }

            $path = public_path('upload/ai_image_filter/' . $newName . '/thumbnail');
            if (!File::exists($path)) {
                File::makeDirectory($path, 0777, true, true);
            }

            $imageName = UploadHelper::uniqueName($path, $request->file('category_image')->getClientOriginalName());
            $request->file('category_image')->move($path, $imageName);
        }

        $aiImageFilterCategory->update([
            'category_name'  => $newName,
            'category_image' => $imageName,
            'status'         => $request->has('status') ? 1 : 0,
        ]);

        return redirect()->route('ai-image-filter-categories.index')
                         ->with('success', 'Category updated successfully.');
    }

    public function destroy(AiImageFilterCategory $aiImageFilterCategory)
    {
        $folder = public_path('upload/ai_image_filter/' . $aiImageFilterCategory->category_name);
        if (File::exists($folder)) {
            File::deleteDirectory($folder);
        }

        $aiImageFilterCategory->filters()->delete();
        $aiImageFilterCategory->delete();

        return response()->json(['success' => true]);
    }

    public function updateStatus(Request $request)
    {
        $category = AiImageFilterCategory::findOrFail($request->id);
        $category->status = $request->status;
        $category->save();

        return response()->json(['success' => true]);
    }

    public function orderList()
    {
        $categories = AiImageFilterCategory::orderBy('sort_order', 'asc')
                                           ->orderBy('id', 'desc')
                                           ->get(['id', 'category_name', 'sort_order']);

        return response()->json(['success' => true, 'categories' => $categories]);
    }

    public function updateOrder(Request $request)
    {
        $request->validate([
            'order'              => 'required|array',
            'order.*.id'         => 'required|integer|exists:ai_image_filter_categories,id',
            'order.*.sort_order' => 'required|integer',
        ]);

        foreach ($request->order as $item) {
            AiImageFilterCategory::where('id', $item['id'])->update(['sort_order' => $item['sort_order']]);
        }

        return response()->json(['success' => true]);
    }
}
