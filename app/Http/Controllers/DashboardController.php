<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AiImageFilterCategory;
use App\Models\AiImageFilter;
use App\Models\FilterCategory;
use App\Models\Filter;
use App\Models\StickerCategory;
use App\Models\Doodle;
use App\Models\Font;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $stickerCategories = StickerCategory::all();

        return view('dashboard', [
            'user' => Auth::user(),

            // AI Image Filter
            'totalAiFilterCategories'  => AiImageFilterCategory::count(),
            'activeAiFilterCategories' => AiImageFilterCategory::where('status', 1)->count(),
            'totalAiFilters'           => AiImageFilter::count(),

            // Filter
            'totalFilterCategories' => FilterCategory::count(),
            'totalFilters'          => Filter::count(),

            // Sticker
            'totalStickerCategories' => $stickerCategories->count(),
            'totalStickerImages'     => $stickerCategories->sum(fn ($c) => count($c->stickers ?? [])),

            // Doodle
            'totalDoodles' => Doodle::count(),

            // Font
            'totalFonts' => Font::count(),
        ]);
    }
}
