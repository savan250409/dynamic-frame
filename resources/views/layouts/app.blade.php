<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Dashboard') – NGD Admin</title>

  @php $cssVer = file_exists(public_path('assets/css/bundle.min.css')) ? filemtime(public_path('assets/css/bundle.min.css')) : '1'; @endphp
  <link rel="stylesheet" href="{{ asset('assets/css/bundle.min.css') }}?v={{ $cssVer }}">

  <style>
    .ngd-logo-wrap {
      display: flex;
      align-items: center;
      gap: 8px;
      text-decoration: none !important;
    }
    .ngd-logo-wrap img {
      width: 34px;
      height: 34px;
      object-fit: contain;
      flex-shrink: 0;
    }
    .ngd-logo-wrap .ngd-brand {
      font-size: 16px;
      font-weight: 800;
      color: #fff;
      letter-spacing: .5px;
      line-height: 1;
    }
    .ngd-logo-wrap .ngd-brand span {
      color: #E8A020;
    }
  </style>
</head>
<body>
<div class="wrapper">
<script>
/* Restore desktop sidebar minimize state BEFORE kaiadmin reads .wrapper on DOMReady */
(function () {
  var el = document.currentScript
    ? document.currentScript.parentElement
    : document.querySelector('.wrapper');
  if (el && localStorage.getItem('kai_sidebar_mini') === '1') {
    el.classList.add('sidebar_minimize');
  }
}());
</script>

  {{-- =================== SIDEBAR =================== --}}
  <div class="sidebar" data-background-color="dark">
    <div class="sidebar-logo">
      <div class="logo-header" data-background-color="dark">
        <a href="{{ route('dashboard') }}" class="logo ngd-logo-wrap">
          <img src="{{ asset('assets/img/ngd-logo.svg') }}" alt="NGD Logo">
          <div class="ngd-brand">NGD <span>Admin</span></div>
        </a>
        <div class="nav-toggle">
          <button class="btn btn-toggle toggle-sidebar"><i class="gg-menu-right"></i></button>
          <button class="btn btn-toggle sidenav-toggler"><i class="gg-menu-left"></i></button>
        </div>
        <button class="topbar-toggler more"><i class="gg-more-vertical-alt"></i></button>
      </div>
    </div>

    <div class="sidebar-wrapper scrollbar scrollbar-inner">
      <div class="sidebar-content">
        <ul class="nav nav-secondary">

          <li class="nav-section">
            <span class="sidebar-mini-icon"><i class="fa fa-ellipsis-h"></i></span>
            <h4 class="text-section">Main</h4>
          </li>

          <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <a href="{{ route('dashboard') }}">
              <i class="fas fa-home"></i>
              <p>Dashboard</p>
            </a>
          </li>

          <li class="nav-section">
            <span class="sidebar-mini-icon"><i class="fa fa-ellipsis-h"></i></span>
            <h4 class="text-section">Dynamic Frame</h4>
          </li>

          <li class="nav-item {{ request()->routeIs('ai-image-filter-categories.*') ? 'active' : '' }}">
            <a href="{{ route('ai-image-filter-categories.index') }}">
              <i class="fas fa-tags"></i>
              <p>Category</p>
            </a>
          </li>

          <li class="nav-item {{ request()->routeIs('ai-image-filters.*') ? 'active' : '' }}">
            <a href="{{ route('ai-image-filters.index') }}">
              <i class="fas fa-magic"></i>
              <p>Dynamic Frames</p>
            </a>
          </li>

          <li class="nav-section">
            <span class="sidebar-mini-icon"><i class="fa fa-ellipsis-h"></i></span>
            <h4 class="text-section">Sticker</h4>
          </li>

          <li class="nav-item {{ request()->routeIs('sticker-categories.*') ? 'active' : '' }}">
            <a href="{{ route('sticker-categories.index') }}">
              <i class="fas fa-sticky-note"></i>
              <p>Category</p>
            </a>
          </li>

          <li class="nav-item {{ request()->routeIs('stickers.*') ? 'active' : '' }}">
            <a href="{{ route('stickers.index') }}">
              <i class="fas fa-images"></i>
              <p>Stickers</p>
            </a>
          </li>

          <li class="nav-section">
            <span class="sidebar-mini-icon"><i class="fa fa-ellipsis-h"></i></span>
            <h4 class="text-section">Filter</h4>
          </li>

          <li class="nav-item {{ request()->routeIs('filter-categories.*') ? 'active' : '' }}">
            <a href="{{ route('filter-categories.index') }}">
              <i class="fas fa-filter"></i>
              <p>Category</p>
            </a>
          </li>

          <li class="nav-item {{ request()->routeIs('filters.*') ? 'active' : '' }}">
            <a href="{{ route('filters.index') }}">
              <i class="fas fa-sliders-h"></i>
              <p>Filters</p>
            </a>
          </li>

          <li class="nav-section">
            <span class="sidebar-mini-icon"><i class="fa fa-ellipsis-h"></i></span>
            <h4 class="text-section">Doodle</h4>
          </li>

          <li class="nav-item {{ request()->routeIs('doodles.*') ? 'active' : '' }}">
            <a href="{{ route('doodles.index') }}">
              <i class="fas fa-pen-nib"></i>
              <p>Doodles</p>
            </a>
          </li>

          <li class="nav-section">
            <span class="sidebar-mini-icon"><i class="fa fa-ellipsis-h"></i></span>
            <h4 class="text-section">Font</h4>
          </li>

          <li class="nav-item {{ request()->routeIs('fonts.*') ? 'active' : '' }}">
            <a href="{{ route('fonts.index') }}">
              <i class="fas fa-font"></i>
              <p>Fonts</p>
            </a>
          </li>

          <li class="nav-section">
            <span class="sidebar-mini-icon"><i class="fa fa-ellipsis-h"></i></span>
            <h4 class="text-section">Developer</h4>
          </li>

          <li class="nav-item {{ request()->routeIs('api-list') ? 'active' : '' }}">
            <a href="{{ route('api-list') }}">
              <i class="fas fa-code"></i>
              <p>API List</p>
            </a>
          </li>

        </ul>
      </div>
    </div>
  </div>
  {{-- =================== END SIDEBAR =================== --}}

  <div class="main-panel">

    {{-- =================== NAVBAR =================== --}}
    <div class="main-header">
      <div class="main-header-logo">
        <div class="logo-header" data-background-color="dark">
          <a href="{{ route('dashboard') }}" class="logo ngd-logo-wrap">
            <img src="{{ asset('assets/img/ngd-logo.svg') }}" alt="NGD Logo">
            <div class="ngd-brand">NGD <span>Admin</span></div>
          </a>
          <div class="nav-toggle">
            <button class="btn btn-toggle toggle-sidebar"><i class="gg-menu-right"></i></button>
            <button class="btn btn-toggle sidenav-toggler"><i class="gg-menu-left"></i></button>
          </div>
          <button class="topbar-toggler more"><i class="gg-more-vertical-alt"></i></button>
        </div>
      </div>

      <nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">
        <div class="container-fluid">
          <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">

            <li class="nav-item topbar-user dropdown hidden-caret">
              <a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#" aria-expanded="false">
                <div class="avatar-sm">
                  <span class="avatar-title rounded-circle" style="background:#E8A020;">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                  </span>
                </div>
                <span class="profile-username ms-2">
                  <span class="op-7">Hi,</span>
                  <span class="fw-bold">{{ Auth::user()->name }}</span>
                </span>
              </a>
              <ul class="dropdown-menu dropdown-user animated fadeIn">
                <li class="dropdown-item">
                  <small class="text-muted">Signed in as</small><br>
                  <strong>{{ Auth::user()->email }}</strong>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                  <form method="POST" action="{{ route('logout') }}" class="m-0">
                    @csrf
                    <button type="submit" class="dropdown-item text-danger">
                      <i class="fas fa-sign-out-alt me-1"></i> Logout
                    </button>
                  </form>
                </li>
              </ul>
            </li>

          </ul>
        </div>
      </nav>
    </div>
    {{-- =================== END NAVBAR =================== --}}

    <div class="container">
      <div class="page-inner">
        @yield('content')
      </div>
    </div>

    <footer class="footer">
      <div class="container-fluid d-flex justify-content-between">
        <div class="copyright">
          &copy; {{ date('Y') }} <strong style="color:#E8A020;">NGD</strong>. All rights reserved.
        </div>
      </div>
    </footer>

  </div>
</div>

@php $jsVer = file_exists(public_path('assets/js/bundle.min.js')) ? filemtime(public_path('assets/js/bundle.min.js')) : '1'; @endphp
<script src="{{ asset('assets/js/bundle.min.js') }}?v={{ $jsVer }}"></script>

<script>
$(function () {

  /*
   * DESKTOP — MutationObserver saves state whenever kaiadmin toggles
   * sidebar_minimize on .wrapper. Zero click-handler conflict.
   */
  var _w = document.querySelector('.wrapper');
  if (_w) {
    new MutationObserver(function (ms) {
      ms.forEach(function (m) {
        if (m.attributeName === 'class') {
          localStorage.setItem('kai_sidebar_mini',
            m.target.classList.contains('sidebar_minimize') ? '1' : '0');
        }
      });
    }).observe(_w, { attributes: true, attributeFilter: ['class'] });
  }

  /*
   * MOBILE — Transparent overlay (z-index 1000) appears when the mobile
   * sidebar opens (html.nav_open). Tapping the overlay triggers kaiadmin's
   * .sidenav-toggler so its internal nav_open counter stays in sync.
   *
   * z-index reference (from kaiadmin.min.css):
   *   .sidebar       → 1002  (above overlay — sidebar remains clickable)
   *   .main-header   → 1001  (above overlay — header stays interactive)
   *   overlay        → 1000  (catches taps on translated main-panel)
   */
  var $ov = $('<div id="ngd-sb-ov"></div>').css({
    display  : 'none',
    position : 'fixed',
    top: 0, left: 0, width: '100%', height: '100%',
    zIndex   : 1000
  }).appendTo('body');

  $ov.on('click', function () {
    $('.sidenav-toggler').first().trigger('click');
  });

  /* Watch html.nav_open class — show overlay when sidebar is open */
  new MutationObserver(function (ms) {
    ms.forEach(function (m) {
      if (m.attributeName === 'class') {
        $ov.toggle($('html').hasClass('nav_open'));
      }
    });
  }).observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });

});
</script>

@stack('scripts')
<script>
/* Auto-dismiss Bootstrap alerts after 5 seconds */
$(function () {
  setTimeout(function () {
    $('.alert-dismissible.fade.show').fadeOut(400, function () {
      $(this).remove();
    });
  }, 5000);
});
</script>
</body>
</html>
