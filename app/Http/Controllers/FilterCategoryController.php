<?php

namespace App\Http\Controllers;

use App\Models\FilterCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class FilterCategoryController extends Controller
{
    private function generateFileName(string $dir, string $originalName): string
    {
        $ext  = pathinfo($originalName, PATHINFO_EXTENSION);
        $base = pathinfo($originalName, PATHINFO_FILENAME);
        $name = $base . '_' . time() . ($ext ? '.' . $ext : '');

        $i = 1;
        while (File::exists($dir . DIRECTORY_SEPARATOR . $name)) {
            $name = $base . '_' . time() . '_' . $i++ . ($ext ? '.' . $ext : '');
        }
        return $name;
    }

    private function cleanupEmptyFolder(string $dir): void
    {
        if (File::isDirectory($dir) && count(File::allFiles($dir)) === 0) {
            File::deleteDirectory($dir);
        }
    }

    public function index(Request $request)
    {
        $page    = $request->page ?? 1;
        $search  = $request->input('search', '');
        $perPage = $request->input('per_page', 10);

        if (!$request->ajax() && session()->has('filter_cat_state')) {
            $state   = session('filter_cat_state');
            $page    = $state['page']     ?? 1;
            $search  = $state['search']   ?? '';
            $perPage = $state['per_page'] ?? 10;

            \Illuminate\Pagination\Paginator::currentPageResolver(function () use ($page) {
                return $page;
            });
        } elseif ($request->ajax()) {
            session([
                'filter_cat_state' => [
                    'page'     => $request->page,
                    'search'   => $request->search,
                    'per_page' => $request->per_page,
                ]
            ]);
        }

        $query = FilterCategory::withCount('filters');
        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }
        $categories = $query->orderBy('sort_order', 'asc')
                            ->orderBy('id', 'desc')
                            ->paginate($perPage);
        $categories->appends(['search' => $search, 'per_page' => $perPage]);

        if ($request->ajax()) {
            return view('admin.filter_category.index', compact('categories'))->render();
        }

        return view('admin.filter_category.index', compact('categories', 'search', 'perPage'));
    }

    public function create()
    {
        return view('admin.filter_category.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'image' => 'nullable|image|mimes:webp',
        ], [
            'image.mimes' => 'Only .webp images are allowed.',
        ]);

        $name      = $request->name;
        $imageName = null;

        if ($request->hasFile('image')) {
            $dir = public_path('upload/filter/' . $name . '/category image');
            if (!File::exists($dir)) {
                File::makeDirectory($dir, 0777, true, true);
            }
            $imageName = $this->generateFileName($dir, $request->file('image')->getClientOriginalName());
            $request->file('image')->move($dir, $imageName);
        }

        FilterCategory::create([
            'name'       => $name,
            'image'      => $imageName,
            'sort_order' => 0,
            'status'     => $request->has('status') ? 1 : 0,
        ]);

        return redirect()->route('filter-categories.index')
                         ->with('success', 'Filter category added successfully.');
    }

    public function edit(FilterCategory $filterCategory)
    {
        return view('admin.filter_category.edit', compact('filterCategory'));
    }

    public function update(Request $request, FilterCategory $filterCategory)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'image' => 'nullable|image|mimes:webp',
        ], [
            'image.mimes' => 'Only .webp images are allowed.',
        ]);

        $oldName   = $filterCategory->name;
        $newName   = $request->name;
        $imageName = $filterCategory->image;

        // Rename root folder when name changes
        if ($oldName !== $newName) {
            $oldDir = public_path('upload/filter/' . $oldName);
            $newDir = public_path('upload/filter/' . $newName);
            if (File::exists($oldDir) && !File::exists($newDir)) {
                File::move($oldDir, $newDir);
            }
        }

        $imageDir = public_path('upload/filter/' . $newName . '/category image');

        // Replace image
        if ($request->hasFile('image')) {
            if ($filterCategory->image) {
                $old = $imageDir . DIRECTORY_SEPARATOR . $filterCategory->image;
                if (File::exists($old)) {
                    File::delete($old);
                }
            }
            if (!File::exists($imageDir)) {
                File::makeDirectory($imageDir, 0777, true, true);
            }
            $imageName = $this->generateFileName($imageDir, $request->file('image')->getClientOriginalName());
            $request->file('image')->move($imageDir, $imageName);
        }

        $this->cleanupEmptyFolder($imageDir);

        $filterCategory->update([
            'name'   => $newName,
            'image'  => $imageName,
            'status' => $request->has('status') ? 1 : 0,
        ]);

        return redirect()->route('filter-categories.index')
                         ->with('success', 'Filter category updated successfully.');
    }

    public function destroy(FilterCategory $filterCategory)
    {
        // Delete all child Filter records from DB
        $filterCategory->filters()->delete();

        // Delete category folder (thumbnail image + any other files)
        $dir = public_path('upload/filter/' . $filterCategory->name);
        if (File::exists($dir)) {
            File::deleteDirectory($dir);
        }

        $filterCategory->delete();

        return response()->json(['success' => true]);
    }

    public function updateStatus(Request $request)
    {
        $category = FilterCategory::findOrFail($request->id);
        $category->update(['status' => $request->status]);
        return response()->json(['success' => true]);
    }

    public function orderList()
    {
        $categories = FilterCategory::orderBy('sort_order', 'asc')
                                    ->orderBy('id', 'desc')
                                    ->get(['id', 'name', 'sort_order']);

        return response()->json(['success' => true, 'categories' => $categories]);
    }

    public function updateOrder(Request $request)
    {
        $request->validate([
            'order'              => 'required|array',
            'order.*.id'         => 'required|integer|exists:filter_categories,id',
            'order.*.sort_order' => 'required|integer',
        ]);

        foreach ($request->order as $item) {
            FilterCategory::where('id', $item['id'])->update(['sort_order' => $item['sort_order']]);
        }

        return response()->json(['success' => true]);
    }
}
