@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<style>
@keyframes fadeInUp   { from { opacity:0; transform:translateY(24px); } to { opacity:1; transform:translateY(0); } }
@keyframes fadeInLeft { from { opacity:0; transform:translateX(-24px); } to { opacity:1; transform:translateX(0); } }
@keyframes fadeInRight{ from { opacity:0; transform:translateX(24px); } to { opacity:1; transform:translateX(0); } }
@keyframes pulse-ring {
  0%   { box-shadow: 0 0 0 0 rgba(232,160,32,.45); }
  70%  { box-shadow: 0 0 0 12px rgba(232,160,32,0); }
  100% { box-shadow: 0 0 0 0 rgba(232,160,32,0); }
}
@keyframes float {
  0%,100% { transform:translateY(0); }
  50%      { transform:translateY(-8px); }
}
@keyframes spin-slow { to { transform:rotate(360deg); } }

.anim-up    { animation: fadeInUp   .55s ease both; }
.anim-left  { animation: fadeInLeft .55s ease both; }
.anim-right { animation: fadeInRight .55s ease both; }
.d1{animation-delay:.05s} .d2{animation-delay:.12s} .d3{animation-delay:.19s}
.d4{animation-delay:.26s} .d5{animation-delay:.33s} .d6{animation-delay:.40s}
.d7{animation-delay:.47s} .d8{animation-delay:.54s} .d9{animation-delay:.61s}

/* Hero */
.hero-banner {
  background: linear-gradient(135deg,#1a2744 0%,#1e3a5f 50%,#2c5f8a 100%);
  border-radius:20px; padding:2.2rem 2.5rem 2rem;
  position:relative; overflow:hidden; margin-bottom:1.75rem;
}
.hero-banner::before {
  content:''; position:absolute; top:-60px; right:-60px;
  width:260px; height:260px;
  background:rgba(232,160,32,.1); border-radius:50%;
}
.hero-banner::after {
  content:''; position:absolute; bottom:-80px; left:30%;
  width:320px; height:320px;
  background:rgba(255,255,255,.03); border-radius:50%;
}
.hero-title { font-size:1.75rem; font-weight:800; color:#fff; line-height:1.2; margin-bottom:.35rem; }
.hero-title span { color:#E8A020; }
.hero-sub { color:rgba(255,255,255,.6); font-size:.9rem; margin-bottom:1.3rem; }
.hero-badge {
  display:inline-flex; align-items:center; gap:6px;
  background:rgba(232,160,32,.18); border:1px solid rgba(232,160,32,.35);
  color:#E8A020; border-radius:50px; padding:4px 14px;
  font-size:.78rem; font-weight:700; letter-spacing:.4px; text-transform:uppercase;
}
.hero-logo { animation:float 4s ease-in-out infinite; }
.hero-logo img { width:100px; height:100px; filter:drop-shadow(0 8px 24px rgba(232,160,32,.4)); }
.status-dot { width:9px; height:9px; border-radius:50%; display:inline-block; flex-shrink:0; }
.status-dot.online { background:#22c55e; animation:pulse-ring 2s infinite; }

/* Module Section Labels */
.module-label {
  font-size:.7rem; font-weight:800; text-transform:uppercase;
  letter-spacing:1px; color:#94a3b8; margin-bottom:.75rem;
  display:flex; align-items:center; gap:8px;
}
.module-label::after {
  content:''; flex:1; height:1px; background:#f1f5f9;
}

/* Stat Cards */
.stat-card {
  border:none; border-radius:16px; overflow:hidden;
  transition:transform .28s, box-shadow .28s;
  cursor:default;
}
.stat-card:hover { transform:translateY(-5px); box-shadow:0 14px 36px rgba(0,0,0,.13) !important; }
.stat-card .card-body { padding:1.25rem 1.4rem; }
.stat-icon {
  width:52px; height:52px; border-radius:14px;
  display:flex; align-items:center; justify-content:center;
  font-size:1.3rem; flex-shrink:0;
}
.stat-count { font-size:1.9rem; font-weight:800; line-height:1; color:#1e2a3a; }
.stat-label { font-size:.75rem; font-weight:600; color:#94a3b8; text-transform:uppercase; letter-spacing:.5px; margin-top:3px; }
.stat-pill {
  font-size:.7rem; font-weight:700; padding:2px 9px; border-radius:50px;
}

/* Module Overview Cards */
.module-card {
  border:1.5px solid #f0f4ff; border-radius:16px; padding:1.2rem 1.4rem;
  background:#fff; transition:all .28s; position:relative; overflow:hidden;
}
.module-card::after {
  content:''; position:absolute; inset:0;
  background:linear-gradient(135deg,transparent 60%,rgba(232,160,32,.05));
  opacity:0; transition:opacity .28s;
}
.module-card:hover { border-color:#E8A020; transform:translateY(-3px); box-shadow:0 10px 28px rgba(232,160,32,.12); }
.module-card:hover::after { opacity:1; }
.module-card-icon {
  width:46px; height:46px; border-radius:12px;
  display:flex; align-items:center; justify-content:center;
  font-size:1.2rem; flex-shrink:0;
}
.module-card-title { font-size:.92rem; font-weight:700; color:#1e2a3a; margin-bottom:1px; }
.module-card-sub   { font-size:.75rem; color:#94a3b8; }
.module-card-count { font-size:1.5rem; font-weight:800; color:#1e2a3a; }

/* Quick Actions */
.quick-card {
  border:1.5px solid #f0f4ff; border-radius:14px; padding:1rem 1.2rem;
  display:flex; align-items:center; gap:.85rem;
  text-decoration:none !important; background:#fff; transition:all .28s;
}
.quick-card:hover { border-color:#E8A020; transform:translateY(-3px); box-shadow:0 8px 24px rgba(232,160,32,.13); }
.quick-card:hover .qc-icon { animation:spin-slow .8s linear; }
.qc-icon { width:44px; height:44px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:1.1rem; flex-shrink:0; }
.qc-title { font-size:.88rem; font-weight:700; color:#1e2a3a; margin-bottom:1px; }
.qc-sub   { font-size:.74rem; color:#94a3b8; }
.qc-arrow { margin-left:auto; color:#cbd5e1; font-size:.9rem; transition:transform .28s,color .28s; }
.quick-card:hover .qc-arrow { transform:translateX(3px); color:#E8A020; }

/* Info card */
.info-card { border:none; border-radius:18px; background:#fff; box-shadow:0 2px 16px rgba(0,0,0,.06); }
.section-title { font-size:.95rem; font-weight:800; color:#1e2a3a; letter-spacing:.2px; }

/* API endpoints */
.api-endpoint {
  background:#f8faff; border:1px solid #e8edf8; border-radius:10px;
  padding:.6rem 1rem; margin-bottom:.45rem;
  display:flex; align-items:center; gap:.7rem; font-size:.8rem; transition:background .2s;
}
.api-endpoint:last-child { margin-bottom:0; }
.api-endpoint:hover { background:#eef2ff; }
.method-badge { font-size:.68rem; font-weight:800; padding:2px 8px; border-radius:5px; letter-spacing:.4px; flex-shrink:0; }
.method-badge.get  { background:#dcfce7; color:#16a34a; }
.method-badge.post { background:#dbeafe; color:#1d4ed8; }
.api-url { color:#475569; font-family:monospace; word-break:break-all; font-size:.78rem; }

/* Profile */
.profile-avatar {
  width:64px; height:64px; border-radius:50%;
  background:linear-gradient(135deg,#E8A020,#c8850e);
  display:flex; align-items:center; justify-content:center;
  font-size:1.6rem; font-weight:900; color:#fff; flex-shrink:0;
  box-shadow:0 6px 20px rgba(232,160,32,.35); animation:pulse-ring 3s infinite;
}
.info-row { display:flex; justify-content:space-between; align-items:center; padding:.55rem 0; border-bottom:1px solid #f1f5f9; font-size:.86rem; }
.info-row:last-child { border-bottom:none; }
.info-row .label { color:#94a3b8; font-weight:500; }
.info-row .value { font-weight:700; color:#1e2a3a; }
</style>

{{-- Page Header --}}
<div class="page-header anim-up">
  <h4 class="page-title">Dashboard</h4>
  <ul class="breadcrumbs">
    <li class="nav-home"><a href="{{ route('dashboard') }}"><i class="icon-home"></i></a></li>
    <li class="separator"><i class="icon-arrow-right"></i></li>
    <li class="nav-item"><a href="#">Dashboard</a></li>
  </ul>
</div>

{{-- Hero Banner --}}
<div class="hero-banner anim-up d1">
  <div class="row align-items-center">
    <div class="col-md-8">
      <span class="hero-badge mb-3 d-inline-flex">
        <span class="status-dot online me-1"></span> System Online
      </span>
      <h1 class="hero-title mt-2">
        Welcome back, <span>{{ $user->name }}</span>! 👋
      </h1>
      <p class="hero-sub">
        Manage all your app content — AI Filters, Color Filters, Stickers, Doodles & Fonts — from one place.
      </p>
      <div class="d-flex gap-2 flex-wrap">
        <a href="{{ route('filter-categories.index') }}"
          style="background:#E8A020; color:#fff; border-radius:10px; border:none; padding:.45rem 1.1rem; font-size:.85rem; font-weight:700; text-decoration:none;">
          <i class="fas fa-sliders-h me-1"></i> Manage Filters
        </a>
        <a href="{{ route('api-list') }}"
          style="background:rgba(255,255,255,.12); color:#fff; border-radius:10px; border:1px solid rgba(255,255,255,.22); padding:.45rem 1.1rem; font-size:.85rem; font-weight:700; text-decoration:none;">
          <i class="fas fa-code me-1"></i> API Docs
        </a>
      </div>
    </div>
    <div class="col-md-4 text-center mt-4 mt-md-0">
      <div class="hero-logo">
        <img src="{{ asset('assets/img/ngd-logo.svg') }}" alt="NGD">
      </div>
    </div>
  </div>
</div>

{{-- ── STAT CARDS ROW 1 — AI Image Filter ── --}}
<div class="module-label anim-up d2 mt-1">
  <i class="fas fa-magic" style="color:#E8A020;"></i> AI Image Filter
</div>
<div class="row g-3 mb-4">
  <div class="col-sm-6 col-xl-3 anim-up d2">
    <div class="stat-card card shadow-sm h-100">
      <div class="card-body">
        <div class="d-flex align-items-center justify-content-between mb-3">
          <div class="stat-icon" style="background:rgba(232,160,32,.12); color:#E8A020;"><i class="fas fa-tags"></i></div>
          <span class="stat-pill" style="background:rgba(232,160,32,.1); color:#c87a00;">Total</span>
        </div>
        <div class="stat-count count-up" data-target="{{ $totalAiFilterCategories }}">0</div>
        <div class="stat-label">AI Filter Categories</div>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-xl-3 anim-up d3">
    <div class="stat-card card shadow-sm h-100">
      <div class="card-body">
        <div class="d-flex align-items-center justify-content-between mb-3">
          <div class="stat-icon" style="background:rgba(34,197,94,.12); color:#22c55e;"><i class="fas fa-check-circle"></i></div>
          <span class="stat-pill" style="background:rgba(34,197,94,.1); color:#15803d;">Active</span>
        </div>
        <div class="stat-count count-up" data-target="{{ $activeAiFilterCategories }}">0</div>
        <div class="stat-label">Active AI Categories</div>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-xl-3 anim-up d4">
    <div class="stat-card card shadow-sm h-100">
      <div class="card-body">
        <div class="d-flex align-items-center justify-content-between mb-3">
          <div class="stat-icon" style="background:rgba(63,130,247,.12); color:#3f82f7;"><i class="fas fa-magic"></i></div>
          <span class="stat-pill" style="background:rgba(63,130,247,.1); color:#1d4ed8;">Total</span>
        </div>
        <div class="stat-count count-up" data-target="{{ $totalAiFilters }}">0</div>
        <div class="stat-label">AI Image Filters</div>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-xl-3 anim-up d4">
    <div class="stat-card card shadow-sm h-100">
      <div class="card-body">
        <div class="d-flex align-items-center justify-content-between mb-3">
          <div class="stat-icon" style="background:rgba(139,92,246,.12); color:#8b5cf6;"><i class="fas fa-layer-group"></i></div>
          <span class="stat-pill" style="background:rgba(139,92,246,.1); color:#7c3aed;">Total</span>
        </div>
        <div class="stat-count count-up" data-target="{{ $totalAiFilterCategories + $totalAiFilters }}">0</div>
        <div class="stat-label">AI Total Records</div>
      </div>
    </div>
  </div>
</div>

{{-- ── STAT CARDS ROW 2 — Filter / Sticker / Doodle / Font ── --}}
<div class="module-label anim-up d3">
  <i class="fas fa-sliders-h" style="color:#06b6d4;"></i> Filter &nbsp;·&nbsp;
  <i class="fas fa-sticky-note" style="color:#f59e0b;"></i> Sticker &nbsp;·&nbsp;
  <i class="fas fa-pen-nib" style="color:#10b981;"></i> Doodle &nbsp;·&nbsp;
  <i class="fas fa-font" style="color:#8b5cf6;"></i> Font
</div>
<div class="row g-3 mb-4">
  {{-- Filter Categories --}}
  <div class="col-sm-6 col-xl-3 anim-up d3">
    <div class="stat-card card shadow-sm h-100">
      <div class="card-body">
        <div class="d-flex align-items-center justify-content-between mb-3">
          <div class="stat-icon" style="background:rgba(6,182,212,.12); color:#06b6d4;"><i class="fas fa-filter"></i></div>
          <span class="stat-pill" style="background:rgba(6,182,212,.1); color:#0e7490;">Category</span>
        </div>
        <div class="stat-count count-up" data-target="{{ $totalFilterCategories }}">0</div>
        <div class="stat-label">Filter Categories</div>
      </div>
    </div>
  </div>
  {{-- Filters --}}
  <div class="col-sm-6 col-xl-3 anim-up d4">
    <div class="stat-card card shadow-sm h-100">
      <div class="card-body">
        <div class="d-flex align-items-center justify-content-between mb-3">
          <div class="stat-icon" style="background:rgba(6,182,212,.12); color:#06b6d4;"><i class="fas fa-sliders-h"></i></div>
          <span class="stat-pill" style="background:rgba(6,182,212,.1); color:#0e7490;">Total</span>
        </div>
        <div class="stat-count count-up" data-target="{{ $totalFilters }}">0</div>
        <div class="stat-label">Total Filters</div>
      </div>
    </div>
  </div>
  {{-- Sticker Categories --}}
  <div class="col-sm-6 col-xl-3 anim-up d5">
    <div class="stat-card card shadow-sm h-100">
      <div class="card-body">
        <div class="d-flex align-items-center justify-content-between mb-3">
          <div class="stat-icon" style="background:rgba(245,158,11,.12); color:#f59e0b;"><i class="fas fa-sticky-note"></i></div>
          <span class="stat-pill" style="background:rgba(245,158,11,.1); color:#b45309;">Category</span>
        </div>
        <div class="stat-count count-up" data-target="{{ $totalStickerCategories }}">0</div>
        <div class="stat-label">Sticker Categories</div>
      </div>
    </div>
  </div>
  {{-- Sticker Images --}}
  <div class="col-sm-6 col-xl-3 anim-up d5">
    <div class="stat-card card shadow-sm h-100">
      <div class="card-body">
        <div class="d-flex align-items-center justify-content-between mb-3">
          <div class="stat-icon" style="background:rgba(245,158,11,.12); color:#f59e0b;"><i class="fas fa-images"></i></div>
          <span class="stat-pill" style="background:rgba(245,158,11,.1); color:#b45309;">Total</span>
        </div>
        <div class="stat-count count-up" data-target="{{ $totalStickerImages }}">0</div>
        <div class="stat-label">Sticker Images</div>
      </div>
    </div>
  </div>
  {{-- Doodles --}}
  <div class="col-sm-6 col-xl-3 anim-up d6">
    <div class="stat-card card shadow-sm h-100">
      <div class="card-body">
        <div class="d-flex align-items-center justify-content-between mb-3">
          <div class="stat-icon" style="background:rgba(16,185,129,.12); color:#10b981;"><i class="fas fa-pen-nib"></i></div>
          <span class="stat-pill" style="background:rgba(16,185,129,.1); color:#047857;">Total</span>
        </div>
        <div class="stat-count count-up" data-target="{{ $totalDoodles }}">0</div>
        <div class="stat-label">Doodles</div>
      </div>
    </div>
  </div>
  {{-- Fonts --}}
  <div class="col-sm-6 col-xl-3 anim-up d6">
    <div class="stat-card card shadow-sm h-100">
      <div class="card-body">
        <div class="d-flex align-items-center justify-content-between mb-3">
          <div class="stat-icon" style="background:rgba(139,92,246,.12); color:#8b5cf6;"><i class="fas fa-font"></i></div>
          <span class="stat-pill" style="background:rgba(139,92,246,.1); color:#7c3aed;">Total</span>
        </div>
        <div class="stat-count count-up" data-target="{{ $totalFonts }}">0</div>
        <div class="stat-label">Fonts</div>
      </div>
    </div>
  </div>
  {{-- Grand Total --}}
  <div class="col-sm-6 col-xl-3 anim-up d7">
    <div class="stat-card card shadow-sm h-100" style="background:linear-gradient(135deg,#1a2744,#2c5f8a); border:none;">
      <div class="card-body">
        <div class="d-flex align-items-center justify-content-between mb-3">
          <div class="stat-icon" style="background:rgba(255,255,255,.15); color:#E8A020;"><i class="fas fa-database"></i></div>
          <span class="stat-pill" style="background:rgba(232,160,32,.2); color:#E8A020;">All</span>
        </div>
        <div class="stat-count count-up" style="color:#fff;" data-target="{{ $totalAiFilterCategories + $totalAiFilters + $totalFilterCategories + $totalFilters + $totalStickerCategories + $totalStickerImages + $totalDoodles + $totalFonts }}">0</div>
        <div class="stat-label" style="color:rgba(255,255,255,.55);">Total Records</div>
      </div>
    </div>
  </div>
</div>

{{-- ── QUICK ACTIONS + API ENDPOINTS ── --}}
<div class="row g-3 mb-4">

  {{-- Quick Actions --}}
  <div class="col-lg-5 anim-left d4">
    <div class="info-card p-3 h-100">
      <div class="section-title mb-3">
        <i class="fas fa-bolt text-warning me-2"></i>Quick Actions
      </div>

      <a href="{{ route('ai-image-filter-categories.index') }}" class="quick-card mb-2">
        <div class="qc-icon" style="background:rgba(232,160,32,.12); color:#E8A020;"><i class="fas fa-tags"></i></div>
        <div><div class="qc-title">AI Filter Categories</div><div class="qc-sub">Manage AI image filter categories</div></div>
        <i class="fas fa-chevron-right qc-arrow"></i>
      </a>
      <a href="{{ route('ai-image-filters.index') }}" class="quick-card mb-2">
        <div class="qc-icon" style="background:rgba(63,130,247,.12); color:#3f82f7;"><i class="fas fa-magic"></i></div>
        <div><div class="qc-title">AI Image Filters</div><div class="qc-sub">Upload filter images & assets</div></div>
        <i class="fas fa-chevron-right qc-arrow"></i>
      </a>
      <a href="{{ route('filter-categories.index') }}" class="quick-card mb-2">
        <div class="qc-icon" style="background:rgba(6,182,212,.12); color:#06b6d4;"><i class="fas fa-filter"></i></div>
        <div><div class="qc-title">Filter Categories</div><div class="qc-sub">Manage color filter categories</div></div>
        <i class="fas fa-chevron-right qc-arrow"></i>
      </a>
      <a href="{{ route('filters.index') }}" class="quick-card mb-2">
        <div class="qc-icon" style="background:rgba(6,182,212,.12); color:#0891b2;"><i class="fas fa-sliders-h"></i></div>
        <div><div class="qc-title">Filters</div><div class="qc-sub">Manage color filter values (CSV import)</div></div>
        <i class="fas fa-chevron-right qc-arrow"></i>
      </a>
      <a href="{{ route('sticker-categories.index') }}" class="quick-card mb-2">
        <div class="qc-icon" style="background:rgba(245,158,11,.12); color:#f59e0b;"><i class="fas fa-sticky-note"></i></div>
        <div><div class="qc-title">Sticker Categories</div><div class="qc-sub">Manage sticker category thumbnails</div></div>
        <i class="fas fa-chevron-right qc-arrow"></i>
      </a>
      <a href="{{ route('stickers.index') }}" class="quick-card mb-2">
        <div class="qc-icon" style="background:rgba(245,158,11,.12); color:#d97706;"><i class="fas fa-images"></i></div>
        <div><div class="qc-title">Stickers</div><div class="qc-sub">Upload & manage sticker images</div></div>
        <i class="fas fa-chevron-right qc-arrow"></i>
      </a>
      <a href="{{ route('doodles.index') }}" class="quick-card mb-2">
        <div class="qc-icon" style="background:rgba(16,185,129,.12); color:#10b981;"><i class="fas fa-pen-nib"></i></div>
        <div><div class="qc-title">Doodles</div><div class="qc-sub">Manage doodle images</div></div>
        <i class="fas fa-chevron-right qc-arrow"></i>
      </a>
      <a href="{{ route('fonts.index') }}" class="quick-card mb-2">
        <div class="qc-icon" style="background:rgba(139,92,246,.12); color:#8b5cf6;"><i class="fas fa-font"></i></div>
        <div><div class="qc-title">Fonts</div><div class="qc-sub">Manage font files & previews</div></div>
        <i class="fas fa-chevron-right qc-arrow"></i>
      </a>
      <a href="{{ route('api-list') }}" class="quick-card">
        <div class="qc-icon" style="background:rgba(139,92,246,.12); color:#7c3aed;"><i class="fas fa-code"></i></div>
        <div><div class="qc-title">API Documentation</div><div class="qc-sub">View all available API endpoints</div></div>
        <i class="fas fa-chevron-right qc-arrow"></i>
      </a>
    </div>
  </div>

  {{-- API Endpoints --}}
  <div class="col-lg-7 anim-right d4">
    <div class="info-card p-3 h-100">
      <div class="section-title mb-3">
        <i class="fas fa-satellite-dish text-primary me-2"></i>API Endpoints
      </div>

      @php
        $endpoints = [
          ['method'=>'GET',  'url'=>'/api/ai-image-filter/categories',       'label'=>'AI Filter Categories'],
          ['method'=>'POST', 'url'=>'/api/ai-image-filter/get-by-category',  'label'=>'AI Filters By Category'],
          ['method'=>'GET',  'url'=>'/api/filter/get-all-filters',            'label'=>'Get All Filters'],
          ['method'=>'GET',  'url'=>'/api/sticker/get-stickers',              'label'=>'Get Stickers'],
          ['method'=>'GET',  'url'=>'/api/doodle/get-doodles',                'label'=>'Get Doodles'],
          ['method'=>'GET',  'url'=>'/api/font/get-fonts',                    'label'=>'Get Fonts'],
        ];
      @endphp

      @foreach($endpoints as $ep)
        <div class="api-endpoint">
          <span class="method-badge {{ strtolower($ep['method']) }}">{{ $ep['method'] }}</span>
          <span class="api-url">{{ $ep['url'] }}</span>
          <span class="ms-auto badge bg-success" style="font-size:.68rem; white-space:nowrap;">Live</span>
        </div>
      @endforeach

      <div class="mt-3 pt-3" style="border-top:1px dashed #e2e8f0;">
        <div class="d-flex align-items-center justify-content-between">
          <small class="text-muted" style="font-size:.78rem;">All endpoints require <code>Authorization: Bearer &lt;token&gt;</code></small>
          <a href="{{ route('api-list') }}" class="btn btn-sm"
            style="background:#f0f4ff; color:#5b4fcf; border:1px solid #dde5ff; border-radius:8px; font-size:.78rem; font-weight:700;">
            View Full Docs →
          </a>
        </div>
      </div>
    </div>
  </div>

</div>

{{-- ── BOTTOM ROW — Account + Module Overview ── --}}
<div class="row g-3">

  {{-- Account Info --}}
  <div class="col-md-4 anim-up d7">
    <div class="info-card p-4 h-100">
      <div class="d-flex align-items-center gap-3 mb-4">
        <div class="profile-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
        <div>
          <div style="font-size:1rem; font-weight:800; color:#1e2a3a;">{{ $user->name }}</div>
          <div style="font-size:.78rem; color:#94a3b8;">{{ $user->email }}</div>
          <span class="badge mt-1" style="background:#E8A020; font-size:.7rem; border-radius:8px;">
            <i class="fas fa-crown me-1"></i>Super Admin
          </span>
        </div>
      </div>
      <div class="info-row">
        <span class="label"><i class="fas fa-user me-2 text-warning"></i>Name</span>
        <span class="value">{{ $user->name }}</span>
      </div>
      <div class="info-row">
        <span class="label"><i class="fas fa-envelope me-2 text-warning"></i>Email</span>
        <span class="value" style="font-size:.78rem;">{{ $user->email }}</span>
      </div>
      <div class="info-row">
        <span class="label"><i class="fas fa-building me-2 text-warning"></i>Company</span>
        <span class="value" style="color:#E8A020;">NGD</span>
      </div>
      <div class="info-row">
        <span class="label"><i class="fas fa-lock me-2 text-warning"></i>Password</span>
        <span class="badge bg-success" style="font-size:.7rem;">bcrypt hashed</span>
      </div>
      <form method="POST" action="{{ route('logout') }}" class="mt-4">
        @csrf
        <button type="submit" class="btn btn-danger w-100 fw-bold" style="border-radius:12px; padding:.65rem;">
          <i class="fas fa-sign-out-alt me-2"></i>Logout
        </button>
      </form>
    </div>
  </div>

  {{-- Module Overview --}}
  <div class="col-md-8 anim-up d8">
    <div class="info-card p-3 h-100">
      <div class="section-title mb-3">
        <i class="fas fa-th-large text-primary me-2"></i>Module Overview
      </div>
      <div class="row g-2">
        @php
          $modules = [
            ['icon'=>'fas fa-tags',         'color'=>'#E8A020', 'bg'=>'rgba(232,160,32,.1)',   'title'=>'AI Filter Categories', 'count'=>$totalAiFilterCategories,  'route'=>'ai-image-filter-categories.index'],
            ['icon'=>'fas fa-magic',         'color'=>'#3f82f7', 'bg'=>'rgba(63,130,247,.1)',   'title'=>'AI Filters',           'count'=>$totalAiFilters,            'route'=>'ai-image-filters.index'],
            ['icon'=>'fas fa-filter',        'color'=>'#06b6d4', 'bg'=>'rgba(6,182,212,.1)',    'title'=>'Filter Categories',    'count'=>$totalFilterCategories,     'route'=>'filter-categories.index'],
            ['icon'=>'fas fa-sliders-h',     'color'=>'#0891b2', 'bg'=>'rgba(6,182,212,.1)',    'title'=>'Filters',              'count'=>$totalFilters,              'route'=>'filters.index'],
            ['icon'=>'fas fa-sticky-note',   'color'=>'#f59e0b', 'bg'=>'rgba(245,158,11,.1)',   'title'=>'Sticker Categories',   'count'=>$totalStickerCategories,    'route'=>'sticker-categories.index'],
            ['icon'=>'fas fa-images',        'color'=>'#d97706', 'bg'=>'rgba(245,158,11,.1)',   'title'=>'Sticker Images',       'count'=>$totalStickerImages,        'route'=>'stickers.index'],
            ['icon'=>'fas fa-pen-nib',       'color'=>'#10b981', 'bg'=>'rgba(16,185,129,.1)',   'title'=>'Doodles',              'count'=>$totalDoodles,              'route'=>'doodles.index'],
            ['icon'=>'fas fa-font',          'color'=>'#8b5cf6', 'bg'=>'rgba(139,92,246,.1)',   'title'=>'Fonts',                'count'=>$totalFonts,                'route'=>'fonts.index'],
            ['icon'=>'fas fa-code',          'color'=>'#7c3aed', 'bg'=>'rgba(139,92,246,.1)',   'title'=>'API Documentation',    'count'=>6,                          'route'=>'api-list'],
          ];
        @endphp

        @foreach($modules as $mod)
          <div class="col-sm-6 col-lg-4">
            <a href="{{ route($mod['route']) }}" class="module-card d-flex align-items-center gap-2" style="text-decoration:none;">
              <div class="module-card-icon" style="background:{{ $mod['bg'] }}; color:{{ $mod['color'] }};">
                <i class="{{ $mod['icon'] }}"></i>
              </div>
              <div class="flex-grow-1">
                <div class="module-card-title">{{ $mod['title'] }}</div>
                <div class="module-card-sub">{{ $mod['count'] }} records</div>
              </div>
              <div class="module-card-count" style="color:{{ $mod['color'] }};">{{ $mod['count'] }}</div>
            </a>
          </div>
        @endforeach
      </div>
    </div>
  </div>

</div>

@endsection

@push('scripts')
<script>
function animateCount(el) {
  var target = parseInt(el.dataset.target, 10);
  if (isNaN(target) || target === 0) { el.textContent = '0'; return; }
  var duration = 1100, start = null;
  function step(ts) {
    if (!start) start = ts;
    var progress = Math.min((ts - start) / duration, 1);
    var ease = 1 - Math.pow(1 - progress, 3);
    el.textContent = Math.floor(ease * target);
    if (progress < 1) requestAnimationFrame(step);
    else el.textContent = target;
  }
  requestAnimationFrame(step);
}

var observer = new IntersectionObserver(function (entries) {
  entries.forEach(function (entry) {
    if (entry.isIntersecting) { animateCount(entry.target); observer.unobserve(entry.target); }
  });
}, { threshold: 0.3 });

document.querySelectorAll('.count-up').forEach(function (el) { observer.observe(el); });
</script>
@endpush
