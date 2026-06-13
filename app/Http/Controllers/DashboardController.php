<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AiImageFilterCategory;
use App\Models\AiImageFilter;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('dashboard', [
            'user'            => Auth::user(),
            'totalCategories' => AiImageFilterCategory::count(),
            'activeCategories'=> AiImageFilterCategory::where('status', 1)->count(),
            'totalFilters'    => AiImageFilter::count(),
        ]);
    }
}
