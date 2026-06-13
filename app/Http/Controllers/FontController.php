<?php

namespace App\Http\Controllers;

use App\Models\Font;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class FontController extends Controller
{
    // Always timestamp filename; appends counter on same-second conflicts.
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

        if (!$request->ajax() && session()->has('font_state')) {
            $state   = session('font_state');
            $page    = $state['page']     ?? 1;
            $search  = $state['search']   ?? '';
            $perPage = $state['per_page'] ?? 10;

            \Illuminate\Pagination\Paginator::currentPageResolver(function () use ($page) {
                return $page;
            });
        } elseif ($request->ajax()) {
            session([
                'font_state' => [
                    'page'     => $request->page,
                    'search'   => $request->search,
                    'per_page' => $request->per_page,
                ]
            ]);
        }

        $query = Font::query();
        if ($search) {
            $query->where('font_name', 'like', '%' . $search . '%');
        }
        $fonts = $query->orderBy('sort_order', 'asc')
                       ->orderBy('id', 'desc')
                       ->paginate($perPage);
        $fonts->appends(['search' => $search, 'per_page' => $perPage]);

        if ($request->ajax()) {
            return view('admin.font.index', compact('fonts'))->render();
        }

        return view('admin.font.index', compact('fonts', 'search', 'perPage'));
    }

    public function create()
    {
        return view('admin.font.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'font_name'     => 'required|string|max:255|unique:fonts,font_name',
            'preview_image' => 'required|image|mimes:webp',
            'font_file'     => 'required|file|mimes:ttf,otf',
        ], [
            'preview_image.required' => 'Please upload a preview image.',
            'preview_image.mimes'    => 'Only .webp images are allowed.',
            'font_file.required'     => 'Please upload a font file.',
            'font_file.mimes'        => 'Only .ttf or .otf files are allowed.',
        ]);

        $fontName = $request->font_name;
        $dir      = public_path('upload/font/' . $fontName);

        if (!File::exists($dir)) {
            File::makeDirectory($dir, 0777, true, true);
        }

        // Save preview image with timestamp
        $previewName = $this->generateFileName($dir, $request->file('preview_image')->getClientOriginalName());
        $request->file('preview_image')->move($dir, $previewName);

        // Save font file with timestamp
        $fontFileName = $this->generateFileName($dir, $request->file('font_file')->getClientOriginalName());
        $request->file('font_file')->move($dir, $fontFileName);

        Font::create([
            'font_name'     => $fontName,
            'font_file'     => $fontFileName,
            'preview_image' => $previewName,
            'is_premium'    => $request->has('is_premium') ? 1 : 0,
            'sort_order'    => 0,
            'status'        => $request->has('status') ? 1 : 0,
        ]);

        return redirect()->route('fonts.index')
                         ->with('success', 'Font added successfully.');
    }

    public function edit(Font $font)
    {
        return view('admin.font.edit', compact('font'));
    }

    public function update(Request $request, Font $font)
    {
        $request->validate([
            'font_name'     => 'required|string|max:255|unique:fonts,font_name,' . $font->id,
            'preview_image' => 'nullable|image|mimes:webp',
            'font_file'     => 'nullable|file|mimes:ttf,otf',
        ], [
            'preview_image.mimes' => 'Only .webp images are allowed.',
            'font_file.mimes'     => 'Only .ttf or .otf files are allowed.',
        ]);

        $oldName      = $font->font_name;
        $newName      = $request->font_name;
        $previewName  = $font->preview_image;
        $fontFileName = $font->font_file;

        // Rename folder when font_name changes
        if ($oldName !== $newName) {
            $oldDir = public_path('upload/font/' . $oldName);
            $newDir = public_path('upload/font/' . $newName);
            if (File::exists($oldDir) && !File::exists($newDir)) {
                File::move($oldDir, $newDir);
            }
        }

        $dir = public_path('upload/font/' . $newName);

        // Replace preview image
        if ($request->hasFile('preview_image')) {
            if ($font->preview_image) {
                $old = $dir . DIRECTORY_SEPARATOR . $font->preview_image;
                if (File::exists($old)) {
                    File::delete($old);
                }
            }
            if (!File::exists($dir)) {
                File::makeDirectory($dir, 0777, true, true);
            }
            $previewName = $this->generateFileName($dir, $request->file('preview_image')->getClientOriginalName());
            $request->file('preview_image')->move($dir, $previewName);
        }

        // Replace font file
        if ($request->hasFile('font_file')) {
            if ($font->font_file) {
                $old = $dir . DIRECTORY_SEPARATOR . $font->font_file;
                if (File::exists($old)) {
                    File::delete($old);
                }
            }
            if (!File::exists($dir)) {
                File::makeDirectory($dir, 0777, true, true);
            }
            $fontFileName = $this->generateFileName($dir, $request->file('font_file')->getClientOriginalName());
            $request->file('font_file')->move($dir, $fontFileName);
        }

        // Cleanup folder if somehow empty after updates
        $this->cleanupEmptyFolder($dir);

        $font->update([
            'font_name'     => $newName,
            'font_file'     => $fontFileName,
            'preview_image' => $previewName,
            'is_premium'    => $request->has('is_premium') ? 1 : 0,
            'status'        => $request->has('status') ? 1 : 0,
        ]);

        return redirect()->route('fonts.index')
                         ->with('success', 'Font updated successfully.');
    }

    public function destroy(Font $font)
    {
        $dir = public_path('upload/font/' . $font->font_name);
        if (File::exists($dir)) {
            File::deleteDirectory($dir);
        }

        $font->delete();

        return response()->json(['success' => true]);
    }

    public function orderList()
    {
        $fonts = Font::orderBy('sort_order', 'asc')
                     ->orderBy('id', 'desc')
                     ->get(['id', 'font_name', 'sort_order']);

        return response()->json(['success' => true, 'fonts' => $fonts]);
    }

    public function updateOrder(Request $request)
    {
        $request->validate([
            'order'              => 'required|array',
            'order.*.id'         => 'required|integer|exists:fonts,id',
            'order.*.sort_order' => 'required|integer',
        ]);

        foreach ($request->order as $item) {
            Font::where('id', $item['id'])->update(['sort_order' => $item['sort_order']]);
        }

        return response()->json(['success' => true]);
    }
}
