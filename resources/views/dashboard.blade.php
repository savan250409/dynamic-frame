@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<style>
/* ── Animations ─────────────────────────────────── */
@keyframes fadeInUp {
  from { opacity: 0; transform: translateY(28px); }
  to   { opacity: 1; transform: translateY(0); }
}
@keyframes fadeInLeft {
  from { opacity: 0; transform: translateX(-28px); }
  to   { opacity: 1; transform: translateX(0); }
}
@keyframes fadeInRight {
  from { opacity: 0; transform: translateX(28px); }
  to   { opacity: 1; transform: translateX(0); }
}
@keyframes pulse-ring {
  0%   { transform: scale(.85); box-shadow: 0 0 0 0 rgba(232,160,32,.5); }
  70%  { transform: scale(1);   box-shadow: 0 0 0 14px rgba(232,160,32,0); }
  100% { transform: scale(.85); box-shadow: 0 0 0 0 rgba(232,160,32,0); }
}
@keyframes float {
  0%, 100% { transform: translateY(0); }
  50%       { transform: translateY(-8px); }
}
@keyframes shimmer {
  0%   { background-position: -400px 0; }
  100% { background-position: 400px 0; }
}
@keyframes countUp { from { opacity: 0; } to { opacity: 1; } }
@keyframes spin-slow { to { transform: rotate(360deg); } }

.anim-fade-up  { animation: fadeInUp   .6s ease both; }
.anim-fade-left  { animation: fadeInLeft  .6s ease both; }
.anim-fade-right { animation: fadeInRight .6s ease both; }
.d1 { animation-delay: .05s; }
.d2 { animation-delay: .15s; }
.d3 { animation-delay: .25s; }
.d4 { animation-delay: .35s; }
.d5 { animation-delay: .45s; }
.d6 { animation-delay: .55s; }

/* ── Hero Banner ─────────────────────────────────── */
.hero-banner {
  background: linear-gradient(135deg, #1a2744 0%, #1e3a5f 45%, #2c5f8a 100%);
  border-radius: 20px;
  padding: 2.5rem 2.5rem 2rem;
  position: relative;
  overflow: hidden;
  margin-bottom: 1.75rem;
}
.hero-banner::before {
  content: '';
  position: absolute;
  top: -60px; right: -60px;
  width: 260px; height: 260px;
  background: rgba(232,160,32,.12);
  border-radius: 50%;
}
.hero-banner::after {
  content: '';
  position: absolute;
  bottom: -80px; left: 30%;
  width: 320px; height: 320px;
  background: rgba(255,255,255,.04);
  border-radius: 50%;
}
.hero-title {
  font-size: 1.8rem;
  font-weight: 800;
  color: #fff;
  line-height: 1.2;
  margin-bottom: .4rem;
}
.hero-title span { color: #E8A020; }
.hero-sub {
  color: rgba(255,255,255,.65);
  font-size: .95rem;
  margin-bottom: 1.4rem;
}
.hero-badge {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  background: rgba(232,160,32,.18);
  border: 1px solid rgba(232,160,32,.35);
  color: #E8A020;
  border-radius: 50px;
  padding: 5px 14px;
  font-size: .8rem;
  font-weight: 700;
  letter-spacing: .5px;
  text-transform: uppercase;
}
.hero-logo-wrap {
  animation: float 4s ease-in-out infinite;
  position: relative; z-index: 1;
}
.hero-logo-wrap img {
  width: 110px;
  height: 110px;
  filter: drop-shadow(0 8px 24px rgba(232,160,32,.4));
}

/* ── Stat Cards ──────────────────────────────────── */
.stat-card {
  border: none;
  border-radius: 18px;
  overflow: hidden;
  transition: transform .3s, box-shadow .3s;
  cursor: default;
  position: relative;
}
.stat-card:hover {
  transform: translateY(-6px);
  box-shadow: 0 16px 40px rgba(0,0,0,.14) !important;
}
.stat-card .card-body { padding: 1.4rem 1.5rem; }
.stat-icon {
  width: 58px; height: 58px;
  border-radius: 16px;
  display: flex; align-items: center; justify-content: center;
  font-size: 1.5rem;
  flex-shrink: 0;
}
.stat-icon.orange  { background: rgba(232,160,32,.15); color: #E8A020; }
.stat-icon.blue    { background: rgba(63,130,247,.15);  color: #3f82f7; }
.stat-icon.green   { background: rgba(34,197,94,.15);   color: #22c55e; }
.stat-icon.purple  { background: rgba(139,92,246,.15);  color: #8b5cf6; } /* quick-action icon */
.stat-count {
  font-size: 2rem;
  font-weight: 800;
  line-height: 1;
  color: #1e2a3a;
}
.stat-label {
  font-size: .8rem;
  font-weight: 600;
  color: #94a3b8;
  text-transform: uppercase;
  letter-spacing: .6px;
  margin-top: 3px;
}
.stat-trend {
  font-size: .78rem;
  font-weight: 600;
  padding: 3px 10px;
  border-radius: 50px;
}
.stat-trend.up   { background: rgba(34,197,94,.12); color: #16a34a; }
.stat-trend.info { background: rgba(63,130,247,.12); color: #2563eb; }

/* ── Quick Action Cards ──────────────────────────── */
.quick-card {
  border: 1.5px solid #f0f4ff;
  border-radius: 16px;
  padding: 1.4rem 1.5rem;
  display: flex;
  align-items: center;
  gap: 1rem;
  text-decoration: none !important;
  background: #fff;
  transition: all .3s;
  position: relative;
  overflow: hidden;
}
.quick-card::after {
  content: '';
  position: absolute;
  inset: 0;
  background: linear-gradient(135deg, transparent 60%, rgba(232,160,32,.06));
  opacity: 0;
  transition: opacity .3s;
}
.quick-card:hover {
  border-color: #E8A020;
  transform: translateY(-4px);
  box-shadow: 0 12px 30px rgba(232,160,32,.15);
}
.quick-card:hover::after { opacity: 1; }
.quick-card:hover .qc-icon { animation: spin-slow 1s linear; }
.qc-icon {
  width: 52px; height: 52px;
  border-radius: 14px;
  display: flex; align-items: center; justify-content: center;
  font-size: 1.3rem;
  flex-shrink: 0;
}
.qc-title {
  font-size: .95rem;
  font-weight: 700;
  color: #1e2a3a;
  margin-bottom: 2px;
}
.qc-sub { font-size: .78rem; color: #94a3b8; }
.qc-arrow {
  margin-left: auto;
  color: #cbd5e1;
  font-size: 1rem;
  transition: transform .3s, color .3s;
}
.quick-card:hover .qc-arrow { transform: translateX(4px); color: #E8A020; }

/* ── Info & Activity ─────────────────────────────── */
.info-card {
  border: none;
  border-radius: 18px;
  background: #fff;
  box-shadow: 0 2px 16px rgba(0,0,0,.06);
}
.section-title {
  font-size: 1rem;
  font-weight: 800;
  color: #1e2a3a;
  letter-spacing: .2px;
}
.status-dot {
  width: 10px; height: 10px;
  border-radius: 50%;
  display: inline-block;
  flex-shrink: 0;
}
.status-dot.online  { background: #22c55e; animation: pulse-ring 2s infinite; }
.status-dot.warning { background: #E8A020; }
.api-endpoint {
  background: #f8faff;
  border: 1px solid #e8edf8;
  border-radius: 10px;
  padding: .65rem 1rem;
  margin-bottom: .5rem;
  display: flex;
  align-items: center;
  gap: .75rem;
  font-size: .82rem;
  transition: background .2s;
}
.api-endpoint:last-child { margin-bottom: 0; }
.api-endpoint:hover { background: #eef2ff; }
.method-badge {
  font-size: .7rem;
  font-weight: 800;
  padding: 3px 9px;
  border-radius: 6px;
  letter-spacing: .4px;
  flex-shrink: 0;
}
.method-badge.get  { background: #dcfce7; color: #16a34a; }
.method-badge.post { background: #dbeafe; color: #1d4ed8; }
.api-url { color: #475569; font-family: monospace; word-break: break-all; }

/* ── Profile Card ────────────────────────────────── */
.profile-avatar {
  width: 70px; height: 70px;
  border-radius: 50%;
  background: linear-gradient(135deg, #E8A020, #c8850e);
  display: flex; align-items: center; justify-content: center;
  font-size: 1.8rem;
  font-weight: 900;
  color: #fff;
  flex-shrink: 0;
  box-shadow: 0 6px 20px rgba(232,160,32,.35);
  animation: pulse-ring 3s infinite;
}
.info-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: .6rem 0;
  border-bottom: 1px solid #f1f5f9;
  font-size: .88rem;
}
.info-row:last-child { border-bottom: none; }
.info-row .label { color: #94a3b8; font-weight: 500; }
.info-row .value { font-weight: 700; color: #1e2a3a; }
</style>

{{-- ── PAGE HEADER ─── --}}
<div class="page-header anim-fade-up">
  <h4 class="page-title">Dashboard</h4>
  <ul class="breadcrumbs">
    <li class="nav-home"><a href="{{ route('dashboard') }}"><i class="icon-home"></i></a></li>
    <li class="separator"><i class="icon-arrow-right"></i></li>
    <li class="nav-item"><a href="#">Dashboard</a></li>
  </ul>
</div>

{{-- ── HERO BANNER ─── --}}
<div class="hero-banner anim-fade-up d1">
  <div class="row align-items-center">
    <div class="col-md-8">
      <span class="hero-badge mb-3 d-inline-flex">
        <span class="status-dot online me-1"></span> System Online
      </span>
      <h1 class="hero-title mt-2">
        Welcome back,<br><span>{{ $user->name }}</span>! 👋
      </h1>
      <p class="hero-sub">
        Manage your AI Image Filter module, monitor stats, and access API documentation — all in one place.
      </p>
      <div class="d-flex gap-2 flex-wrap">
        <a href="{{ route('ai-image-filter-categories.index') }}"
          class="btn btn-sm px-4 py-2 fw-bold"
          style="background:#E8A020; color:#fff; border-radius:10px; border:none;">
          <i class="fas fa-tags me-1"></i> Manage Categories
        </a>
        <a href="{{ route('api-list') }}"
          class="btn btn-sm px-4 py-2 fw-bold"
          style="background:rgba(255,255,255,.12); color:#fff; border-radius:10px; border:1px solid rgba(255,255,255,.2);">
          <i class="fas fa-code me-1"></i> API Docs
        </a>
      </div>
    </div>
    <div class="col-md-4 text-center mt-4 mt-md-0">
      <div class="hero-logo-wrap">
        <img src="{{ asset('assets/img/ngd-logo.svg') }}" alt="NGD">
      </div>
    </div>
  </div>
</div>

{{-- ── STAT CARDS ─── --}}
<div class="row g-3 mb-4">

  <div class="col-sm-6 col-xl-3 anim-fade-up d1">
    <div class="stat-card card shadow-sm h-100">
      <div class="card-body">
        <div class="d-flex align-items-center justify-content-between mb-3">
          <div class="stat-icon orange">
            <i class="fas fa-tags"></i>
          </div>
          <span class="stat-trend up"><i class="fas fa-arrow-up me-1"></i>Total</span>
        </div>
        <div class="stat-count count-up" data-target="{{ $totalCategories }}">0</div>
        <div class="stat-label">AI Filter Categories</div>
      </div>
    </div>
  </div>

  <div class="col-sm-6 col-xl-3 anim-fade-up d2">
    <div class="stat-card card shadow-sm h-100">
      <div class="card-body">
        <div class="d-flex align-items-center justify-content-between mb-3">
          <div class="stat-icon green">
            <i class="fas fa-check-circle"></i>
          </div>
          <span class="stat-trend up"><i class="fas fa-circle me-1" style="font-size:.5rem;"></i>Active</span>
        </div>
        <div class="stat-count count-up" data-target="{{ $activeCategories }}">0</div>
        <div class="stat-label">Active Categories</div>
      </div>
    </div>
  </div>

  <div class="col-sm-6 col-xl-3 anim-fade-up d3">
    <div class="stat-card card shadow-sm h-100">
      <div class="card-body">
        <div class="d-flex align-items-center justify-content-between mb-3">
          <div class="stat-icon blue">
            <i class="fas fa-magic"></i>
          </div>
          <span class="stat-trend info"><i class="fas fa-layer-group me-1"></i>Total</span>
        </div>
        <div class="stat-count count-up" data-target="{{ $totalFilters }}">0</div>
        <div class="stat-label">AI Image Filters</div>
      </div>
    </div>
  </div>


</div>

{{-- ── MIDDLE ROW ─── --}}
<div class="row g-3 mb-4">

  {{-- Quick Actions --}}
  <div class="col-lg-5 anim-fade-left d3">
    <div class="info-card p-3 h-100">
      <div class="section-title mb-3">
        <i class="fas fa-bolt text-warning me-2"></i>Quick Actions
      </div>
      <a href="{{ route('ai-image-filter-categories.index') }}" class="quick-card mb-2 d-flex">
        <div class="qc-icon" style="background:rgba(232,160,32,.12); color:#E8A020;">
          <i class="fas fa-tags"></i>
        </div>
        <div>
          <div class="qc-title">AI Filter Categories</div>
          <div class="qc-sub">Add, edit, manage categories</div>
        </div>
        <i class="fas fa-chevron-right qc-arrow"></i>
      </a>
      <a href="{{ route('ai-image-filters.index') }}" class="quick-card mb-2 d-flex">
        <div class="qc-icon" style="background:rgba(63,130,247,.12); color:#3f82f7;">
          <i class="fas fa-magic"></i>
        </div>
        <div>
          <div class="qc-title">AI Image Filters</div>
          <div class="qc-sub">Upload images, prompts & zip files</div>
        </div>
        <i class="fas fa-chevron-right qc-arrow"></i>
      </a>
      <a href="{{ route('ai-image-filter-categories.create') }}" class="quick-card mb-2 d-flex">
        <div class="qc-icon" style="background:rgba(34,197,94,.12); color:#22c55e;">
          <i class="fas fa-plus-circle"></i>
        </div>
        <div>
          <div class="qc-title">Add New Category</div>
          <div class="qc-sub">Create a new filter category</div>
        </div>
        <i class="fas fa-chevron-right qc-arrow"></i>
      </a>
      <a href="{{ route('api-list') }}" class="quick-card d-flex">
        <div class="qc-icon" style="background:rgba(139,92,246,.12); color:#8b5cf6;">
          <i class="fas fa-code"></i>
        </div>
        <div>
          <div class="qc-title">API Documentation</div>
          <div class="qc-sub">View all API endpoints</div>
        </div>
        <i class="fas fa-chevron-right qc-arrow"></i>
      </a>
    </div>
  </div>

  {{-- API Endpoints --}}
  <div class="col-lg-7 anim-fade-right d3">
    <div class="info-card p-3 h-100">
      <div class="section-title mb-3">
        <i class="fas fa-satellite-dish text-primary me-2"></i>API Endpoints
      </div>
      <div class="api-endpoint">
        <span class="method-badge get">GET</span>
        <span class="api-url">/api/ai-image-filter/categories</span>
        <span class="ms-auto badge bg-success" style="font-size:.7rem;">Live</span>
      </div>
      <div class="api-endpoint">
        <span class="method-badge post">POST</span>
        <span class="api-url">/api/ai-image-filter/get-by-category</span>
        <span class="ms-auto badge bg-success" style="font-size:.7rem;">Live</span>
      </div>
    </div>
  </div>
</div>

{{-- ── BOTTOM ROW — Account & Tips ─── --}}
<div class="row g-3">

  {{-- Account Info --}}
  <div class="col-md-5 anim-fade-up d5">
    <div class="info-card p-4 h-100">
      <div class="d-flex align-items-center gap-3 mb-4">
        <div class="profile-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
        <div>
          <div style="font-size:1.1rem; font-weight:800; color:#1e2a3a;">{{ $user->name }}</div>
          <div style="font-size:.82rem; color:#94a3b8;">{{ $user->email }}</div>
          <span class="badge mt-1" style="background:#E8A020; font-size:.72rem; border-radius:8px;">
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
        <span class="value" style="font-size:.82rem;">{{ $user->email }}</span>
      </div>
      <div class="info-row">
        <span class="label"><i class="fas fa-building me-2 text-warning"></i>Company</span>
        <span class="value" style="color:#E8A020;">NGD</span>
      </div>
      <div class="info-row">
        <span class="label"><i class="fas fa-lock me-2 text-warning"></i>Password</span>
        <span class="badge bg-success" style="font-size:.72rem;">bcrypt hashed</span>
      </div>
      <form method="POST" action="{{ route('logout') }}" class="mt-4">
        @csrf
        <button type="submit" class="btn btn-danger w-100 fw-bold" style="border-radius:12px; padding:.7rem;">
          <i class="fas fa-sign-out-alt me-2"></i>Logout
        </button>
      </form>
    </div>
  </div>

  {{-- Tips / Getting Started --}}
  <div class="col-md-7 anim-fade-up d6">
    <div class="info-card p-4 h-100">
      <div class="section-title mb-4">
        <i class="fas fa-lightbulb text-warning me-2"></i>Getting Started
      </div>
      @php
        $steps = [
          [
            'num'   => '01',
            'color' => '#E8A020',
            'title' => 'Create a Category',
            'desc'  => 'Go to AI Image Filter → Category and add your first category with a .webp thumbnail.',
            'route' => 'ai-image-filter-categories.create',
            'label' => 'Add Category',
          ],
          [
            'num'   => '02',
            'color' => '#3f82f7',
            'title' => 'Upload AI Filters',
            'desc'  => 'Add filters with name, AI prompt, a .webp image and a .zip file containing the filter assets.',
            'route' => 'ai-image-filters.create',
            'label' => 'Add Filter',
          ],
          [
            'num'   => '03',
            'color' => '#22c55e',
            'title' => 'Hit the API',
            'desc'  => 'Use GET /api/ai-image-filter/categories to fetch all active categories with their filters.',
            'route' => 'api-list',
            'label' => 'View API',
          ],
        ];
      @endphp
      @foreach($steps as $i => $step)
        <div class="d-flex gap-3 mb-4 anim-fade-up" style="animation-delay: {{ .55 + $i * .1 }}s;">
          <div style="width:42px;height:42px;border-radius:12px;background:{{ $step['color'] }}1a;
                      display:flex;align-items:center;justify-content:center;flex-shrink:0;
                      font-size:.8rem;font-weight:900;color:{{ $step['color'] }};">
            {{ $step['num'] }}
          </div>
          <div class="flex-grow-1">
            <div style="font-size:.93rem;font-weight:800;color:#1e2a3a;margin-bottom:2px;">{{ $step['title'] }}</div>
            <div style="font-size:.82rem;color:#64748b;line-height:1.5;">{{ $step['desc'] }}</div>
          </div>
          <a href="{{ route($step['route']) }}"
            class="btn btn-sm align-self-center fw-bold"
            style="background:{{ $step['color'] }}1a;color:{{ $step['color'] }};border:1px solid {{ $step['color'] }}33;border-radius:10px;white-space:nowrap;padding:.35rem .9rem;font-size:.78rem;">
            {{ $step['label'] }} →
          </a>
        </div>
        @if(!$loop->last)
          <div style="border-left:2px dashed #e2e8f0;margin-left:21px;height:12px;margin-bottom:4px;"></div>
        @endif
      @endforeach
    </div>
  </div>

</div>

@endsection

@push('scripts')
<script>
/* ── Count-Up Animation ── */
function animateCount(el) {
  var target = parseInt(el.dataset.target, 10);
  if (isNaN(target) || target === 0) { el.textContent = '0'; return; }
  var duration = 1200;
  var start    = null;
  function step(ts) {
    if (!start) start = ts;
    var progress = Math.min((ts - start) / duration, 1);
    var ease     = 1 - Math.pow(1 - progress, 3);
    el.textContent = Math.floor(ease * target);
    if (progress < 1) requestAnimationFrame(step);
    else el.textContent = target;
  }
  requestAnimationFrame(step);
}

var observer = new IntersectionObserver(function (entries) {
  entries.forEach(function (entry) {
    if (entry.isIntersecting) {
      animateCount(entry.target);
      observer.unobserve(entry.target);
    }
  });
}, { threshold: 0.3 });

document.querySelectorAll('.count-up').forEach(function (el) {
  observer.observe(el);
});
</script>
@endpush
