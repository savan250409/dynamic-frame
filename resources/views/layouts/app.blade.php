<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Dashboard') – NGD Admin</title>

  <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/plugins.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/kaiadmin.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/fonts.min.css') }}">

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
            <h4 class="text-section">AI Image Filter</h4>
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
              <p>AI Image Filters</p>
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

<script src="{{ asset('assets/js/core/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
<script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/js/kaiadmin.min.js') }}"></script>

<script>
/* ── Sidebar toggle fix ──────────────────────────────────────────────
   kaiadmin tracks state in closure vars (h, f) which reset on every
   page load. We replace those handlers with class-state checks so
   the sidebar behaves correctly and state is persisted via localStorage.
   .off('click') removes kaiadmin's bindings before adding ours,
   preventing double-fire. The hover mini-expand is re-wired the same way.
─────────────────────────────────────────────────────────────────────── */
$(function () {

  /* ── Restore state ── */
  if (localStorage.getItem('kai_sidebar_mini') === '1') {
    $('.wrapper').addClass('sidebar_minimize');
    $('.toggle-sidebar').addClass('toggled').html('<i class="gg-more-vertical-alt"></i>');
  }

  /* ── Desktop: collapse / expand ── */
  $(document).off('click', '.toggle-sidebar')
             .on('click',  '.toggle-sidebar', function () {
    var isMini = $('.wrapper').hasClass('sidebar_minimize');
    if (isMini) {
      $('.wrapper').removeClass('sidebar_minimize sidebar_minimize_hover');
      $('.toggle-sidebar').removeClass('toggled').html('<i class="gg-menu-right"></i>');
      localStorage.setItem('kai_sidebar_mini', '0');
    } else {
      $('.wrapper').addClass('sidebar_minimize');
      $('.toggle-sidebar').addClass('toggled').html('<i class="gg-more-vertical-alt"></i>');
      localStorage.setItem('kai_sidebar_mini', '1');
    }
    $(window).trigger('resize');
  });

  /* ── Mobile: open / close overlay ── */
  $(document).off('click', '.sidenav-toggler')
             .on('click',  '.sidenav-toggler', function () {
    if ($('html').hasClass('nav_open')) {
      $('html').removeClass('nav_open');
      $('.sidenav-toggler').removeClass('toggled');
    } else {
      $('html').addClass('nav_open');
      $('.sidenav-toggler').addClass('toggled');
    }
  });

  /* ── Mobile: top-bar toggle ── */
  $(document).off('click', '.topbar-toggler')
             .on('click',  '.topbar-toggler', function () {
    if ($('html').hasClass('topbar_open')) {
      $('html').removeClass('topbar_open');
      $('.topbar-toggler').removeClass('toggled');
    } else {
      $('html').addClass('topbar_open');
      $('.topbar-toggler').addClass('toggled');
    }
  });

  /* ── Close mobile sidebar when clicking outside ── */
  $(document).on('click', function (e) {
    if (!$('html').hasClass('nav_open')) return;
    var $sb  = $('.sidebar');
    var $tog = $('.sidenav-toggler');
    if (!$sb.is(e.target) && $sb.has(e.target).length === 0 &&
        !$tog.is(e.target) && $tog.has(e.target).length === 0) {
      $('html').removeClass('nav_open');
      $('.sidenav-toggler').removeClass('toggled');
    }
  });

  /* ── Mini-sidebar hover expand ── */
  $('.sidebar')
    .off('mouseenter.kai mouseleave.kai')
    .on('mouseenter.kai', function () {
      if ($('.wrapper').hasClass('sidebar_minimize')) {
        $('.wrapper').addClass('sidebar_minimize_hover');
      }
    })
    .on('mouseleave.kai', function () {
      $('.wrapper').removeClass('sidebar_minimize_hover');
    });

  /* ── Submenu accordion ── */
  $(document).off('click.submenu', '.nav-item a')
             .on('click.submenu',  '.nav-item a', function () {
    var $collapse = $(this).parent().find('.collapse');
    if ($collapse.hasClass('show')) {
      $(this).parent().removeClass('submenu');
    } else {
      $(this).parent().addClass('submenu');
    }
  });

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
