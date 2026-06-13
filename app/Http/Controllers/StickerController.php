<?php

namespace App\Http\Controllers;

use App\Models\StickerCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class StickerController extends Controller
{
    // Always generates a unique name with timestamp; appends counter on same-second conflicts.
    private function generateStickerName(string $dir, string $originalName): string
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
        $page       = $request->page ?? 1;
        $search     = $request->input('search', '');
        $categoryId = $request->input('category_id', '');
        $perPage    = $request->input('per_page', 10);

        if (!$request->ajax() && session()->has('sticker_state')) {
            $state      = session('sticker_state');
            $page       = $state['page']        ?? 1;
            $search     = $state['search']      ?? '';
            $perPage    = $state['per_page']    ?? 10;
            $categoryId = $state['category_id'] ?? '';

            \Illuminate\Pagination\Paginator::currentPageResolver(function () use ($page) {
                return $page;
            });
        } elseif ($request->ajax()) {
            session([
                'sticker_state' => [
                    'page'        => $request->page,
                    'search'      => $request->search,
                    'per_page'    => $request->per_page,
                    'category_id' => $request->category_id,
                ]
            ]);
        }

        $query = StickerCategory::query();
        if ($search) {
            $query->where('category_name', 'like', '%' . $search . '%');
        }
        if ($categoryId) {
            $query->where('id', $categoryId);
        }

        $stickers = $query->orderBy('sort_order', 'asc')
                          ->orderBy('id', 'desc')
                          ->paginate($perPage);
        $stickers->appends(['search' => $search, 'per_page' => $perPage, 'category_id' => $categoryId]);

        $categories    = StickerCategory::orderBy('sort_order', 'asc')->orderBy('id', 'desc')->get();
        $totalStickers = StickerCategory::all()->sum(fn ($cat) => count($cat->stickers ?? []));

        if ($request->ajax()) {
            return view('admin.sticker.index', compact('stickers', 'categories', 'totalStickers'))->render();
        }

        return view('admin.sticker.index', compact('stickers', 'categories', 'totalStickers', 'search', 'perPage', 'categoryId'));
    }

    public function create()
    {
        $categories = StickerCategory::orderBy('sort_order', 'asc')->orderBy('id', 'desc')->get();
        return view('admin.sticker.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:sticker_categories,id',
            'images'      => 'required|array|min:1',
            'images.*'    => 'required|image|mimes:webp',
        ], [
            'images.required' => 'Please upload at least one sticker image.',
            'images.*.mimes'  => 'Only .webp images are allowed.',
        ]);

        $category     = StickerCategory::findOrFail($request->category_id);
        $stickersPath = public_path('upload/sticker/' . $category->category_name . '/stickers');

        if (!File::exists($stickersPath)) {
            File::makeDirectory($stickersPath, 0777, true, true);
        }

        $existing = is_array($category->stickers) ? $category->stickers : [];

        foreach ($request->file('images') as $image) {
            // Always timestamp the filename to avoid same-name conflicts across uploads
            $imageName  = $this->generateStickerName($stickersPath, $image->getClientOriginalName());
            $image->move($stickersPath, $imageName);
            $existing[] = $imageName;
        }

        $category->update(['stickers' => $existing]);

        return redirect()->route('stickers.index')
                         ->with('success', 'Stickers added successfully.');
    }

    public function edit(StickerCategory $stickerCategory)
    {
        $categories = StickerCategory::orderBy('sort_order', 'asc')->orderBy('id', 'desc')->get();
        return view('admin.sticker.form', compact('stickerCategory', 'categories'));
    }

    public function update(Request $request, StickerCategory $stickerCategory)
    {
        $request->validate([
            'images'   => 'nullable|array',
            'images.*' => 'image|mimes:webp',
        ], [
            'images.*.mimes' => 'Only .webp images are allowed.',
        ]);

        if ($request->hasFile('images')) {
            $stickersPath = public_path('upload/sticker/' . $stickerCategory->category_name . '/stickers');

            if (!File::exists($stickersPath)) {
                File::makeDirectory($stickersPath, 0777, true, true);
            }

            $existing = is_array($stickerCategory->stickers) ? $stickerCategory->stickers : [];

            foreach ($request->file('images') as $image) {
                // Always timestamp the filename to avoid same-name conflicts across uploads
                $imageName  = $this->generateStickerName($stickersPath, $image->getClientOriginalName());
                $image->move($stickersPath, $imageName);
                $existing[] = $imageName;
            }

            $stickerCategory->update(['stickers' => $existing]);
        }

        return redirect()->route('stickers.index')
                         ->with('success', 'Stickers updated successfully.');
    }

    public function destroy(StickerCategory $stickerCategory)
    {
        $stickersPath = public_path('upload/sticker/' . $stickerCategory->category_name . '/stickers');

        if (File::isDirectory($stickersPath)) {
            File::deleteDirectory($stickersPath);
        }

        $stickerCategory->update(['stickers' => []]);

        return response()->json(['success' => true]);
    }

    public function removeSticker(Request $request, StickerCategory $stickerCategory)
    {
        $filename    = $request->filename;
        $stickersDir = public_path('upload/sticker/' . $stickerCategory->category_name . '/stickers');
        $filePath    = $stickersDir . DIRECTORY_SEPARATOR . $filename;

        // Delete the individual sticker file
        if (File::exists($filePath)) {
            File::delete($filePath);
        }

        // Remove filename from the JSON array
        $existing = is_array($stickerCategory->stickers) ? $stickerCategory->stickers : [];
        $updated  = array_values(array_filter($existing, fn ($f) => $f !== $filename));
        $stickerCategory->update(['stickers' => $updated]);

        // Delete the stickers folder when it has no files remaining
        $this->cleanupEmptyFolder($stickersDir);

        return response()->json(['success' => true]);
    }
}
