<?php

namespace App\Http\Controllers;

use App\Models\AiImageFilterCategory;
use App\Models\AiImageFilter;
use App\Support\UploadHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class AiImageFilterController extends Controller
{
    public function index(Request $request)
    {
        $page       = $request->page ?? 1;
        $search     = $request->input('search', '');
        $categoryId = $request->input('category_id', '');
        $perPage    = $request->input('per_page', 10);

        if (!$request->ajax() && session()->has('ai_filter_state')) {
            $state      = session('ai_filter_state');
            $page       = $state['page']        ?? 1;
            $search     = $state['search']      ?? '';
            $perPage    = $state['per_page']    ?? 10;
            $categoryId = $state['category_id'] ?? '';

            \Illuminate\Pagination\Paginator::currentPageResolver(function () use ($page) {
                return $page;
            });
        } elseif ($request->ajax()) {
            session([
                'ai_filter_state' => [
                    'page'        => $request->page,
                    'search'      => $request->search,
                    'per_page'    => $request->per_page,
                    'category_id' => $request->category_id,
                ]
            ]);
        }

        $query = AiImageFilter::with('category');
        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        $filters = $query->orderBy('id', 'desc')->paginate($perPage);
        $filters->appends(['search' => $search, 'per_page' => $perPage, 'category_id' => $categoryId]);

        $categories = AiImageFilterCategory::orderBy('sort_order', 'asc')->orderBy('id', 'desc')->get();

        if ($request->ajax()) {
            return view('admin.ai_image_filter.index', compact('filters', 'categories'))->render();
        }

        return view('admin.ai_image_filter.index', compact('filters', 'categories', 'search', 'perPage', 'categoryId'));
    }

    public function create()
    {
        $categories = AiImageFilterCategory::orderBy('sort_order', 'asc')->orderBy('id', 'desc')->get();
        return view('admin.ai_image_filter.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:ai_image_filter_categories,id',
            'input_count' => 'required|integer|min:1',
            'image'       => 'required|image|mimes:webp',
            'zip_file'    => 'required|file|mimes:zip',
        ], [
            'image.mimes'    => 'Only .webp images are allowed.',
            'zip_file.mimes' => 'Only .zip files are allowed.',
        ]);

        $category  = AiImageFilterCategory::findOrFail($request->category_id);
        $imgPath   = public_path('upload/ai_image_filter/' . $category->category_name . '/images');
        $zipPath   = public_path('upload/ai_image_filter/' . $category->category_name . '/zip');

        foreach ([$imgPath, $zipPath] as $dir) {
            if (!File::exists($dir)) {
                File::makeDirectory($dir, 0777, true, true);
            }
        }

        $imageName = UploadHelper::uniqueName($imgPath, $request->file('image')->getClientOriginalName());
        $request->file('image')->move($imgPath, $imageName);

        $zipName = UploadHelper::uniqueName($zipPath, $request->file('zip_file')->getClientOriginalName());
        $request->file('zip_file')->move($zipPath, $zipName);

        AiImageFilter::create([
            'category_id' => $request->category_id,
            'input_count' => $request->input_count,
            'image_path'  => $imageName,
            'zip_file'    => $zipName,
            'sort_order'  => 0,
        ]);

        return redirect()->route('ai-image-filters.index')
                         ->with('success', 'AI Image Filter added successfully.');
    }

    public function edit(AiImageFilter $aiImageFilter)
    {
        $categories = AiImageFilterCategory::orderBy('sort_order', 'asc')->orderBy('id', 'desc')->get();
        return view('admin.ai_image_filter.form', compact('aiImageFilter', 'categories'));
    }

    public function update(Request $request, AiImageFilter $aiImageFilter)
    {
        $request->validate([
            'category_id' => 'required|exists:ai_image_filter_categories,id',
            'input_count' => 'required|integer|min:1',
            'image'       => 'nullable|image|mimes:webp',
            'zip_file'    => 'nullable|file|mimes:zip',
        ], [
            'image.mimes'    => 'Only .webp images are allowed.',
            'zip_file.mimes' => 'Only .zip files are allowed.',
        ]);

        $newCategory = AiImageFilterCategory::findOrFail($request->category_id);
        $oldCategory = $aiImageFilter->category;
        $imageName   = $aiImageFilter->image_path;
        $zipName     = $aiImageFilter->zip_file;

        $categoryChanged = $oldCategory && $newCategory->id !== $oldCategory->id;

        // ── Handle IMAGE ────────────────────────────────────────────────────
        if ($request->hasFile('image')) {
            // Delete old image
            if ($oldCategory && $aiImageFilter->image_path) {
                $old = public_path('upload/ai_image_filter/' . $oldCategory->category_name . '/images/' . $aiImageFilter->image_path);
                if (File::exists($old)) File::delete($old);
            }
            $newImgDir = public_path('upload/ai_image_filter/' . $newCategory->category_name . '/images');
            if (!File::exists($newImgDir)) File::makeDirectory($newImgDir, 0777, true, true);

            $imageName = UploadHelper::uniqueName($newImgDir, $request->file('image')->getClientOriginalName());
            $request->file('image')->move($newImgDir, $imageName);

        } elseif ($categoryChanged && $aiImageFilter->image_path) {
            // Move image to new category folder
            $oldFile   = public_path('upload/ai_image_filter/' . $oldCategory->category_name . '/images/' . $aiImageFilter->image_path);
            $newImgDir = public_path('upload/ai_image_filter/' . $newCategory->category_name . '/images');
            if (!File::exists($newImgDir)) File::makeDirectory($newImgDir, 0777, true, true);
            if (File::exists($oldFile)) {
                $imageName = UploadHelper::uniqueName($newImgDir, $aiImageFilter->image_path);
                File::move($oldFile, $newImgDir . '/' . $imageName);
            }
        }

        // ── Handle ZIP ──────────────────────────────────────────────────────
        if ($request->hasFile('zip_file')) {
            // Delete old zip
            if ($oldCategory && $aiImageFilter->zip_file) {
                $old = public_path('upload/ai_image_filter/' . $oldCategory->category_name . '/zip/' . $aiImageFilter->zip_file);
                if (File::exists($old)) File::delete($old);
            }
            $newZipDir = public_path('upload/ai_image_filter/' . $newCategory->category_name . '/zip');
            if (!File::exists($newZipDir)) File::makeDirectory($newZipDir, 0777, true, true);

            $zipName = UploadHelper::uniqueName($newZipDir, $request->file('zip_file')->getClientOriginalName());
            $request->file('zip_file')->move($newZipDir, $zipName);

        } elseif ($categoryChanged && $aiImageFilter->zip_file) {
            // Move zip to new category folder
            $oldFile   = public_path('upload/ai_image_filter/' . $oldCategory->category_name . '/zip/' . $aiImageFilter->zip_file);
            $newZipDir = public_path('upload/ai_image_filter/' . $newCategory->category_name . '/zip');
            if (!File::exists($newZipDir)) File::makeDirectory($newZipDir, 0777, true, true);
            if (File::exists($oldFile)) {
                $zipName = UploadHelper::uniqueName($newZipDir, $aiImageFilter->zip_file);
                File::move($oldFile, $newZipDir . '/' . $zipName);
            }
        }

        $aiImageFilter->update([
            'category_id' => $request->category_id,
            'input_count' => $request->input_count,
            'image_path'  => $imageName,
            'zip_file'    => $zipName,
        ]);

        return redirect()->route('ai-image-filters.index')
                         ->with('success', 'AI Image Filter updated successfully.');
    }

    public function destroy(AiImageFilter $aiImageFilter)
    {
        $category = $aiImageFilter->category;

        if ($category) {
            $catBase = public_path('upload/ai_image_filter' . DIRECTORY_SEPARATOR . $category->category_name);

            foreach ([
                'images' => $aiImageFilter->image_path,
                'zip'    => $aiImageFilter->zip_file,
            ] as $sub => $filename) {
                // Delete the individual file
                if ($filename) {
                    $filePath = $catBase . DIRECTORY_SEPARATOR . $sub . DIRECTORY_SEPARATOR . $filename;
                    if (File::exists($filePath)) {
                        File::delete($filePath);
                    }
                }

                // Auto-delete the sub-folder when it has no files left
                $subDir = $catBase . DIRECTORY_SEPARATOR . $sub;
                if (File::isDirectory($subDir) && count(File::allFiles($subDir)) === 0) {
                    File::deleteDirectory($subDir);
                }
            }
        }

        $aiImageFilter->delete();
        return response()->json(['success' => true]);
    }

    public function orderList(Request $request)
    {
        $categoryId = $request->category_id;
        $query      = AiImageFilter::with('category');

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        $filters = $query->orderBy('sort_order', 'asc')
                         ->orderBy('id', 'asc')
                         ->get(['id', 'category_id', 'input_count', 'image_path', 'sort_order']);

        $filters->transform(function ($filter) {
            $cat = $filter->category;
            return [
                'id'          => $filter->id,
                'input_count' => $filter->input_count ?? 1,
                'image_url'   => ($cat && $filter->image_path)
                    ? asset('upload/ai_image_filter/' . rawurlencode($cat->category_name) . '/images/' . rawurlencode($filter->image_path))
                    : null,
            ];
        });

        return response()->json(['success' => true, 'filters' => $filters]);
    }

    public function updateOrder(Request $request)
    {
        $request->validate([
            'order'              => 'required|array',
            'order.*.id'         => 'required|integer|exists:ai_image_filters,id',
            'order.*.sort_order' => 'required|integer',
        ]);

        foreach ($request->order as $item) {
            AiImageFilter::where('id', $item['id'])->update(['sort_order' => $item['sort_order']]);
        }

        return response()->json(['success' => true]);
    }
}
