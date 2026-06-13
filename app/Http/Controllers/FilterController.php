<?php

namespace App\Http\Controllers;

use App\Models\Filter;
use App\Models\FilterCategory;
use Illuminate\Http\Request;

class FilterController extends Controller
{
    public function index(Request $request)
    {
        $page       = $request->page ?? 1;
        $search     = $request->input('search', '');
        $perPage    = $request->input('per_page', 10);
        $categoryId = $request->input('category_id', '');

        if (!$request->ajax() && session()->has('filter_state')) {
            $state      = session('filter_state');
            $page       = $state['page']        ?? 1;
            $search     = $state['search']      ?? '';
            $perPage    = $state['per_page']    ?? 10;
            $categoryId = $state['category_id'] ?? '';

            \Illuminate\Pagination\Paginator::currentPageResolver(function () use ($page) {
                return $page;
            });
        } elseif ($request->ajax()) {
            session([
                'filter_state' => [
                    'page'        => $request->page,
                    'search'      => $request->search,
                    'per_page'    => $request->per_page,
                    'category_id' => $request->category_id,
                ]
            ]);
        }

        $query = Filter::with('filterCategory');
        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }
        if ($categoryId) {
            $query->where('filter_category_id', $categoryId);
        }

        $filters = $query->orderBy('filter_category_id', 'asc')
                         ->orderBy('sort_order', 'asc')
                         ->orderBy('id', 'asc')
                         ->paginate($perPage);
        $filters->appends(['search' => $search, 'per_page' => $perPage, 'category_id' => $categoryId]);

        $allCategories = FilterCategory::orderBy('name', 'asc')->get(['id', 'name']);

        if ($request->ajax()) {
            return view('admin.filter.index', compact('filters', 'allCategories', 'categoryId'))->render();
        }

        return view('admin.filter.index', compact('filters', 'allCategories', 'search', 'perPage', 'categoryId'));
    }

    public function create()
    {
        $categories = FilterCategory::orderBy('name', 'asc')->get(['id', 'name']);
        return view('admin.filter.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'filter_category_id' => 'required|exists:filter_categories,id',
            'name'               => 'required|string|max:255',
            'saturation'         => 'required|numeric',
            'brightness'         => 'required|numeric',
            'contrast'           => 'required|numeric',
            'red'                => 'required|numeric',
            'green'              => 'required|numeric',
            'blue'               => 'required|numeric',
        ]);

        Filter::create([
            'filter_category_id' => $request->filter_category_id,
            'name'               => $request->name,
            'saturation'         => $request->saturation,
            'brightness'         => $request->brightness,
            'contrast'           => $request->contrast,
            'red'                => $request->red,
            'green'              => $request->green,
            'blue'               => $request->blue,
            'is_premium'         => $request->has('is_premium') ? 1 : 0,
            'sort_order'         => 0,
            'status'             => 1,
        ]);

        return redirect()->route('filters.index')
                         ->with('success', 'Filter added successfully.');
    }

    public function edit(Filter $filter)
    {
        $categories = FilterCategory::orderBy('name', 'asc')->get(['id', 'name']);
        return view('admin.filter.edit', compact('filter', 'categories'));
    }

    public function update(Request $request, Filter $filter)
    {
        $request->validate([
            'filter_category_id' => 'required|exists:filter_categories,id',
            'name'               => 'required|string|max:255',
            'saturation'         => 'required|numeric',
            'brightness'         => 'required|numeric',
            'contrast'           => 'required|numeric',
            'red'                => 'required|numeric',
            'green'              => 'required|numeric',
            'blue'               => 'required|numeric',
        ]);

        $filter->update([
            'filter_category_id' => $request->filter_category_id,
            'name'               => $request->name,
            'saturation'         => $request->saturation,
            'brightness'         => $request->brightness,
            'contrast'           => $request->contrast,
            'red'                => $request->red,
            'green'              => $request->green,
            'blue'               => $request->blue,
            'is_premium'         => $request->has('is_premium') ? 1 : 0,
            'status'             => $request->has('status') ? 1 : 0,
        ]);

        return redirect()->route('filters.index')
                         ->with('success', 'Filter updated successfully.');
    }

    public function destroy(Filter $filter)
    {
        $filter->delete();
        return response()->json(['success' => true]);
    }

    public function importCsv()
    {
        return view('admin.filter.import');
    }

    public function processImport(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt',
        ], [
            'csv_file.required' => 'Please upload a CSV file.',
            'csv_file.mimes'    => 'Only CSV files are allowed.',
        ]);

        $file   = $request->file('csv_file');
        $handle = fopen($file->getRealPath(), 'r');

        // Read header row
        $rawHeader = fgetcsv($handle);
        if (!$rawHeader) {
            fclose($handle);
            return back()->withErrors(['csv_file' => 'CSV file is empty or unreadable.']);
        }

        // Normalize headers: lowercase + trim
        $header = array_map(fn($h) => strtolower(trim($h)), $rawHeader);

        // Map expected columns (case-insensitive, flexible names)
        $colMap = [];
        $expectedMap = [
            'category'    => ['category'],
            'filter_name' => ['filter_name', 'filter name', 'name'],
            'saturation'  => ['saturation'],
            'brightness'  => ['brightness'],
            'contrast'    => ['contrast'],
            'red'         => ['red'],
            'green'       => ['green'],
            'blue'        => ['blue'],
            'type'        => ['type'],
        ];

        foreach ($expectedMap as $key => $aliases) {
            foreach ($header as $idx => $col) {
                if (in_array($col, $aliases)) {
                    $colMap[$key] = $idx;
                    break;
                }
            }
        }

        $required = ['category', 'filter_name', 'saturation', 'brightness', 'contrast', 'red', 'green', 'blue'];
        foreach ($required as $col) {
            if (!isset($colMap[$col])) {
                fclose($handle);
                return back()->withErrors(['csv_file' => "Required column \"{$col}\" not found in CSV."]);
            }
        }

        // Cache categories by name to avoid repeated DB queries
        $categoryCache = FilterCategory::pluck('id', 'name')->toArray();

        $inserted = 0;
        $skipped  = 0;

        while (($row = fgetcsv($handle)) !== false) {
            // Skip empty rows
            if (empty(array_filter($row))) {
                continue;
            }

            $categoryName = trim($row[$colMap['category']] ?? '');
            $filterName   = trim($row[$colMap['filter_name']] ?? '');

            if (!$categoryName || !$filterName) {
                $skipped++;
                continue;
            }

            // Auto-create missing category
            if (!isset($categoryCache[$categoryName])) {
                $cat = FilterCategory::firstOrCreate(
                    ['name' => $categoryName],
                    ['sort_order' => 0, 'status' => 1]
                );
                $categoryCache[$categoryName] = $cat->id;
            }

            $typeRaw  = isset($colMap['type']) ? strtolower(trim($row[$colMap['type']] ?? '')) : 'pro';
            $isPremium = ($typeRaw === 'pro') ? 1 : 0;

            Filter::create([
                'filter_category_id' => $categoryCache[$categoryName],
                'name'               => $filterName,
                'saturation'         => (float) ($row[$colMap['saturation']] ?? 1),
                'brightness'         => (float) ($row[$colMap['brightness']] ?? 0),
                'contrast'           => (float) ($row[$colMap['contrast']] ?? 1),
                'red'                => (float) ($row[$colMap['red']] ?? 1),
                'green'              => (float) ($row[$colMap['green']] ?? 1),
                'blue'               => (float) ($row[$colMap['blue']] ?? 1),
                'is_premium'         => $isPremium,
                'sort_order'         => 0,
                'status'             => 1,
            ]);

            $inserted++;
        }

        fclose($handle);

        return redirect()->route('filters.index')
                         ->with('success', "CSV imported successfully. {$inserted} filters added" . ($skipped ? ", {$skipped} rows skipped." : '.'));
    }
}
