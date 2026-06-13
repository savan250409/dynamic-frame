<?php

namespace App\Http\Controllers;

use App\Models\Doodle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class DoodleController extends Controller
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

        if (!$request->ajax() && session()->has('doodle_state')) {
            $state   = session('doodle_state');
            $page    = $state['page']     ?? 1;
            $search  = $state['search']   ?? '';
            $perPage = $state['per_page'] ?? 10;

            \Illuminate\Pagination\Paginator::currentPageResolver(function () use ($page) {
                return $page;
            });
        } elseif ($request->ajax()) {
            session([
                'doodle_state' => [
                    'page'     => $request->page,
                    'search'   => $request->search,
                    'per_page' => $request->per_page,
                ]
            ]);
        }

        $query = Doodle::query();
        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }
        $doodles = $query->orderBy('sort_order', 'asc')
                         ->orderBy('id', 'desc')
                         ->paginate($perPage);
        $doodles->appends(['search' => $search, 'per_page' => $perPage]);

        if ($request->ajax()) {
            return view('admin.doodle.index', compact('doodles'))->render();
        }

        return view('admin.doodle.index', compact('doodles', 'search', 'perPage'));
    }

    public function create()
    {
        return view('admin.doodle.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'doodle_type' => 'required|in:image,line',
            'image'       => 'required|image|mimes:webp',
        ], [
            'image.required' => 'Please upload a doodle image.',
            'image.mimes'    => 'Only .webp images are allowed.',
        ]);

        $name = $request->name;
        $dir  = public_path('upload/doodle/' . $name);

        if (!File::exists($dir)) {
            File::makeDirectory($dir, 0777, true, true);
        }

        $imageName = $this->generateFileName($dir, $request->file('image')->getClientOriginalName());
        $request->file('image')->move($dir, $imageName);

        Doodle::create([
            'name'        => $name,
            'image'       => $imageName,
            'doodle_type' => $request->doodle_type,
            'is_premium'  => $request->has('is_premium') ? 1 : 0,
            'sort_order'  => 0,
            'status'      => $request->has('status') ? 1 : 0,
        ]);

        return redirect()->route('doodles.index')
                         ->with('success', 'Doodle added successfully.');
    }

    public function edit(Doodle $doodle)
    {
        return view('admin.doodle.edit', compact('doodle'));
    }

    public function update(Request $request, Doodle $doodle)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'doodle_type' => 'required|in:image,line',
            'image'       => 'nullable|image|mimes:webp',
        ], [
            'image.mimes' => 'Only .webp images are allowed.',
        ]);

        $oldName   = $doodle->name;
        $newName   = $request->name;
        $imageName = $doodle->image;

        // Rename folder when name changes
        if ($oldName !== $newName) {
            $oldDir = public_path('upload/doodle/' . $oldName);
            $newDir = public_path('upload/doodle/' . $newName);
            if (File::exists($oldDir) && !File::exists($newDir)) {
                File::move($oldDir, $newDir);
            }
        }

        $dir = public_path('upload/doodle/' . $newName);

        // Replace image
        if ($request->hasFile('image')) {
            if ($doodle->image) {
                $old = $dir . DIRECTORY_SEPARATOR . $doodle->image;
                if (File::exists($old)) {
                    File::delete($old);
                }
            }
            if (!File::exists($dir)) {
                File::makeDirectory($dir, 0777, true, true);
            }
            $imageName = $this->generateFileName($dir, $request->file('image')->getClientOriginalName());
            $request->file('image')->move($dir, $imageName);
        }

        $this->cleanupEmptyFolder($dir);

        $doodle->update([
            'name'        => $newName,
            'image'       => $imageName,
            'doodle_type' => $request->doodle_type,
            'is_premium'  => $request->has('is_premium') ? 1 : 0,
            'status'      => $request->has('status') ? 1 : 0,
        ]);

        return redirect()->route('doodles.index')
                         ->with('success', 'Doodle updated successfully.');
    }

    public function destroy(Doodle $doodle)
    {
        $dir = public_path('upload/doodle/' . $doodle->name);
        if (File::exists($dir)) {
            File::deleteDirectory($dir);
        }

        $doodle->delete();

        return response()->json(['success' => true]);
    }

    public function updateStatus(Request $request)
    {
        $doodle = Doodle::findOrFail($request->id);
        $doodle->update(['status' => $request->status]);
        return response()->json(['success' => true]);
    }

    public function orderList()
    {
        $doodles = Doodle::orderBy('sort_order', 'asc')
                         ->orderBy('id', 'desc')
                         ->get(['id', 'name', 'sort_order']);

        return response()->json(['success' => true, 'doodles' => $doodles]);
    }

    public function updateOrder(Request $request)
    {
        $request->validate([
            'order'              => 'required|array',
            'order.*.id'         => 'required|integer|exists:doodles,id',
            'order.*.sort_order' => 'required|integer',
        ]);

        foreach ($request->order as $item) {
            Doodle::where('id', $item['id'])->update(['sort_order' => $item['sort_order']]);
        }

        return response()->json(['success' => true]);
    }
}
