@extends('layouts.app')

@section('title', 'Edit Filter')

@section('content')

<div class="page-header">
  <h4 class="page-title">Edit Filter</h4>
  <ul class="breadcrumbs">
    <li class="nav-home"><a href="{{ route('dashboard') }}"><i class="icon-home"></i></a></li>
    <li class="separator"><i class="icon-arrow-right"></i></li>
    <li class="nav-item"><a href="{{ route('filters.index') }}">Filter Management</a></li>
    <li class="separator"><i class="icon-arrow-right"></i></li>
    <li class="nav-item"><a href="#">Edit</a></li>
  </ul>
</div>

<div class="row">
  <div class="col-lg-8 col-md-10">
    <div class="card">
      <div class="card-body">

        <div class="d-flex justify-content-between align-items-center pb-3 mb-4" style="border-bottom:2px solid #f0f0f0;">
          <div class="d-flex align-items-center">
            <i class="fas fa-pencil-alt text-primary" style="font-size:2rem; margin-right:10px;"></i>
            <div>
              <h4 class="mb-0 fw-bold text-primary">Edit Filter</h4>
              <small class="text-muted">Update filter details</small>
            </div>
          </div>
          <a href="{{ route('filters.index') }}" class="btn btn-outline-primary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Back to Filters
          </a>
        </div>

        @if ($errors->any())
          <div class="alert alert-danger mb-3">
            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
          </div>
        @endif

        <form action="{{ route('filters.update', $filter->id) }}" method="POST">
          @csrf
          @method('PUT')

          {{-- Category --}}
          <div class="form-group mb-3">
            <label class="fw-bold">Category <span class="text-danger">*</span></label>
            <select name="filter_category_id"
              class="form-select @error('filter_category_id') is-invalid @enderror">
              <option value="">Select Category</option>
              @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ old('filter_category_id', $filter->filter_category_id) == $cat->id ? 'selected' : '' }}>
                  {{ $cat->name }}
                </option>
              @endforeach
            </select>
            @error('filter_category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>

          {{-- Filter Name --}}
          <div class="form-group mb-3">
            <label class="fw-bold">Filter Name <span class="text-danger">*</span></label>
            <input type="text" name="name"
              class="form-control @error('name') is-invalid @enderror"
              placeholder="Filter Name"
              value="{{ old('name', $filter->name) }}">
            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>

          {{-- Type --}}
          <div class="form-group mb-3">
            <label class="fw-bold d-block">Type</label>
            <div class="form-check d-flex align-items-center gap-2 mt-1">
              <input class="form-check-input" type="checkbox" name="is_premium" id="is-premium-toggle"
                value="1" {{ $filter->is_premium ? 'checked' : '' }}
                style="cursor:pointer; width:18px; height:18px; accent-color:#5b4fcf;">
              <label class="mb-0 d-flex align-items-center gap-1" for="is-premium-toggle">
                <span style="background:#5b4fcf; color:#fff; font-size:.75rem; font-weight:700;
                  padding:2px 10px; border-radius:20px;">Premium (Pro)</span>
              </label>
            </div>
          </div>

          {{-- Active --}}
          <div class="form-group mb-3">
            <div class="form-check form-switch mb-0 d-flex align-items-center gap-2">
              <input class="form-check-input" type="checkbox" name="status" id="status-toggle"
                value="1" {{ $filter->status ? 'checked' : '' }}
                style="cursor:pointer; width:40px; height:22px;">
              <label class="fw-bold mb-0" for="status-toggle">Active</label>
            </div>
          </div>

          {{-- Saturation & Brightness --}}
          <div class="row mb-3">
            <div class="col-md-6">
              <label class="fw-bold">Saturation <span class="text-danger">*</span></label>
              <input type="number" name="saturation" step="0.01"
                class="form-control @error('saturation') is-invalid @enderror"
                value="{{ old('saturation', $filter->saturation) }}">
              @error('saturation')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
              <label class="fw-bold">Brightness <span class="text-danger">*</span></label>
              <input type="number" name="brightness" step="0.01"
                class="form-control @error('brightness') is-invalid @enderror"
                value="{{ old('brightness', $filter->brightness) }}">
              @error('brightness')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
          </div>

          {{-- Contrast --}}
          <div class="form-group mb-3">
            <label class="fw-bold">Contrast <span class="text-danger">*</span></label>
            <input type="number" name="contrast" step="0.01"
              class="form-control @error('contrast') is-invalid @enderror"
              value="{{ old('contrast', $filter->contrast) }}">
            @error('contrast')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>

          {{-- Red, Green, Blue --}}
          <div class="row mb-4">
            <div class="col-md-4">
              <label class="fw-bold">Red <span class="text-danger">*</span></label>
              <input type="number" name="red" step="0.01"
                class="form-control @error('red') is-invalid @enderror"
                value="{{ old('red', $filter->red) }}">
              @error('red')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
              <label class="fw-bold">Green <span class="text-danger">*</span></label>
              <input type="number" name="green" step="0.01"
                class="form-control @error('green') is-invalid @enderror"
                value="{{ old('green', $filter->green) }}">
              @error('green')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
              <label class="fw-bold">Blue <span class="text-danger">*</span></label>
              <input type="number" name="blue" step="0.01"
                class="form-control @error('blue') is-invalid @enderror"
                value="{{ old('blue', $filter->blue) }}">
              @error('blue')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
          </div>

          <button type="submit" class="btn btn-primary w-100" style="font-size:1rem; padding:.7rem;">
            <i class="fas fa-save me-1"></i> Update Filter
          </button>
        </form>

      </div>
    </div>
  </div>
</div>

@endsection
