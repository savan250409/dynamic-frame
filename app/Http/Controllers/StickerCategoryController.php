<?php

namespace App\Http\Controllers;

use App\Models\StickerCategory;
use App\Support\UploadHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class StickerCategoryController extends Controller
{
    public function index(Request $request)
    {
        $page    = $request->page ?? 1;
        $search  = $request->input('search', '');
        $perPage = $request->input('per_page', 10);

        if (!$request->ajax() && session()->has('sticker_cat_state')) {
            $state   = session('sticker_cat_state');
            $page    = $state['page']     ?? 1;
            $search  = $state['search']   ?? '';
            $perPage = $state['per_page'] ?? 10;

            \Illuminate\Pagination\Paginator::currentPageResolver(function () use ($page) {
                return $page;
            });
        } elseif ($request->ajax()) {
            session([
                'sticker_cat_state' => [
                    'page'     => $request->page,
                    'search'   => $request->search,
                    'per_page' => $request->per_page,
                ]
            ]);
        }

        $query = StickerCategory::query();
        if ($search) {
            $query->where('category_name', 'like', '%' . $search . '%');
        }
        $categories = $query->orderBy('sort_order', 'asc')
                            ->orderBy('id', 'desc')
                            ->paginate($perPage);
        $categories->appends(['search' => $search, 'per_page' => $perPage]);

        if ($request->ajax()) {
            return view('admin.sticker_category.index', compact('categories'))->render();
        }

        return view('admin.sticker_category.index', compact('categories', 'search', 'perPage'));
    }

    public function create()
    {
        return view('admin.sticker_category.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_name' => 'required|string|max:255|unique:sticker_categories,category_name',
            'image'         => 'required|image|mimes:webp',
        ], [
            'image.required' => 'Please upload a thumbnail image.',
            'image.mimes'    => 'Only .webp images are allowed.',
        ]);

        $categoryName = $request->category_name;
        $path = public_path('upload/sticker/' . $categoryName . '/category image');

        if (!File::exists($path)) {
            File::makeDirectory($path, 0777, true, true);
        }

        $imageName = UploadHelper::uniqueName($path, $request->file('image')->getClientOriginalName());
        $request->file('image')->move($path, $imageName);

        StickerCategory::create([
            'category_name' => $categoryName,
            'image'         => $imageName,
            'stickers'      => [],
            'is_premium'    => $request->has('is_premium') ? 1 : 0,
            'sort_order'    => 0,
            'status'        => $request->has('status') ? 1 : 0,
        ]);

        return redirect()->route('sticker-categories.index')
                         ->with('success', 'Sticker Category created successfully.');
    }

    public function edit(StickerCategory $stickerCategory)
    {
        return view('admin.sticker_category.edit', compact('stickerCategory'));
    }

    public function update(Request $request, StickerCategory $stickerCategory)
    {
        $request->validate([
            'category_name' => 'required|string|max:255|unique:sticker_categories,category_name,' . $stickerCategory->id,
            'image'         => 'nullable|image|mimes:webp',
        ], [
            'image.mimes' => 'Only .webp images are allowed.',
        ]);

        $oldName   = $stickerCategory->category_name;
        $newName   = $request->category_name;
        $imageName = $stickerCategory->image;

        if ($oldName !== $newName) {
            $oldFolder = public_path('upload/sticker/' . $oldName);
            $newFolder = public_path('upload/sticker/' . $newName);
            if (File::exists($oldFolder) && !File::exists($newFolder)) {
                File::move($oldFolder, $newFolder);
            }
        }

        if ($request->hasFile('image')) {
            if ($stickerCategory->image) {
                $oldFile = public_path('upload/sticker/' . $newName . '/category image/' . $stickerCategory->image);
                if (File::exists($oldFile)) {
                    File::delete($oldFile);
                }
            }

            $path = public_path('upload/sticker/' . $newName . '/category image');
            if (!File::exists($path)) {
                File::makeDirectory($path, 0777, true, true);
            }

            $imageName = UploadHelper::uniqueName($path, $request->file('image')->getClientOriginalName());
            $request->file('image')->move($path, $imageName);
        }

        $stickerCategory->update([
            'category_name' => $newName,
            'image'         => $imageName,
            'is_premium'    => $request->has('is_premium') ? 1 : 0,
            'status'        => $request->has('status') ? 1 : 0,
        ]);

        return redirect()->route('sticker-categories.index')
                         ->with('success', 'Sticker Category updated successfully.');
    }

    public function destroy(StickerCategory $stickerCategory)
    {
        $folder = public_path('upload/sticker/' . $stickerCategory->category_name);
        if (File::exists($folder)) {
            File::deleteDirectory($folder);
        }

        $stickerCategory->delete();

        return response()->json(['success' => true]);
    }

    public function updateStatus(Request $request)
    {
        $category = StickerCategory::findOrFail($request->id);
        $category->status = $request->status;
        $category->save();

        return response()->json(['success' => true]);
    }

    public function orderList()
    {
        $categories = StickerCategory::orderBy('sort_order', 'asc')
                                     ->orderBy('id', 'desc')
                                     ->get(['id', 'category_name', 'sort_order']);

        return response()->json(['success' => true, 'categories' => $categories]);
    }

    public function updateOrder(Request $request)
    {
        $request->validate([
            'order'              => 'required|array',
            'order.*.id'         => 'required|integer|exists:sticker_categories,id',
            'order.*.sort_order' => 'required|integer',
        ]);

        foreach ($request->order as $item) {
            StickerCategory::where('id', $item['id'])->update(['sort_order' => $item['sort_order']]);
        }

        return response()->json(['success' => true]);
    }
}
