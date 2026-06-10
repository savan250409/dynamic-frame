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
@stack('scripts')
</body>
</html>
