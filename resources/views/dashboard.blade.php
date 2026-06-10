@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<div class="page-header">
  <h4 class="page-title">Dashboard</h4>
  <ul class="breadcrumbs">
    <li class="nav-home">
      <a href="{{ route('dashboard') }}"><i class="icon-home"></i></a>
    </li>
    <li class="separator"><i class="icon-arrow-right"></i></li>
    <li class="nav-item"><a href="#">Dashboard</a></li>
  </ul>
</div>

{{-- ── STAT CARDS ── --}}
<div class="row">

  <div class="col-sm-6 col-md-3">
    <div class="card card-stats card-round">
      <div class="card-body">
        <div class="row align-items-center">
          <div class="col-icon">
            <div class="icon-big text-center icon-primary bubble-shadow-small">
              <i class="fas fa-users"></i>
            </div>
          </div>
          <div class="col col-stats ms-3 ms-sm-0">
            <div class="numbers">
              <p class="card-category">Total Users</p>
              <h4 class="card-title">1</h4>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-sm-6 col-md-3">
    <div class="card card-stats card-round">
      <div class="card-body">
        <div class="row align-items-center">
          <div class="col-icon">
            <div class="icon-big text-center icon-info bubble-shadow-small">
              <i class="fas fa-check-circle"></i>
            </div>
          </div>
          <div class="col col-stats ms-3 ms-sm-0">
            <div class="numbers">
              <p class="card-category">Status</p>
              <h4 class="card-title">Active</h4>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-sm-6 col-md-3">
    <div class="card card-stats card-round">
      <div class="card-body">
        <div class="row align-items-center">
          <div class="col-icon">
            <div class="icon-big text-center icon-success bubble-shadow-small">
              <i class="fas fa-database"></i>
            </div>
          </div>
          <div class="col col-stats ms-3 ms-sm-0">
            <div class="numbers">
              <p class="card-category">Database</p>
              <h4 class="card-title">Connected</h4>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-sm-6 col-md-3">
    <div class="card card-stats card-round">
      <div class="card-body">
        <div class="row align-items-center">
          <div class="col-icon">
            <div class="icon-big text-center icon-secondary bubble-shadow-small">
              <i class="fas fa-code"></i>
            </div>
          </div>
          <div class="col col-stats ms-3 ms-sm-0">
            <div class="numbers">
              <p class="card-category">Framework</p>
              <h4 class="card-title">Laravel 8</h4>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>

{{-- ── WELCOME PANEL ── --}}
<div class="row">
  <div class="col-md-8">
    <div class="card">
      <div class="card-header">
        <div class="d-flex align-items-center gap-2">
          <img src="{{ asset('assets/img/ngd-logo.svg') }}" alt="NGD" width="28" height="28">
          <h4 class="card-title mb-0">Welcome back, {{ $user->name }}!</h4>
        </div>
      </div>
      <div class="card-body">
        <p class="text-muted">
          You are logged in as <strong>{{ $user->email }}</strong>.
        </p>
        <p class="text-muted mb-0">
          Your password is stored securely in the database using <strong>bcrypt</strong> hashing.
        </p>
      </div>
    </div>
  </div>

  <div class="col-md-4">
    <div class="card">
      <div class="card-header">
        <h4 class="card-title">Account Info</h4>
      </div>
      <div class="card-body">
        <ul class="list-group list-group-flush">
          <li class="list-group-item px-0 d-flex justify-content-between">
            <span class="text-muted">Name</span>
            <strong>{{ $user->name }}</strong>
          </li>
          <li class="list-group-item px-0 d-flex justify-content-between">
            <span class="text-muted">Email</span>
            <strong>{{ $user->email }}</strong>
          </li>
          <li class="list-group-item px-0 d-flex justify-content-between">
            <span class="text-muted">Role</span>
            <span class="badge" style="background:#E8A020;">Admin</span>
          </li>
          <li class="list-group-item px-0 d-flex justify-content-between">
            <span class="text-muted">Password</span>
            <span class="badge bg-success">bcrypt hashed</span>
          </li>
          <li class="list-group-item px-0 d-flex justify-content-between">
            <span class="text-muted">Company</span>
            <strong style="color:#E8A020;">NGD</strong>
          </li>
        </ul>

        <form method="POST" action="{{ route('logout') }}" class="mt-3">
          @csrf
          <button type="submit" class="btn btn-danger btn-sm w-100">
            <i class="fas fa-sign-out-alt me-1"></i> Logout
          </button>
        </form>
      </div>
    </div>
  </div>
</div>

@endsection
