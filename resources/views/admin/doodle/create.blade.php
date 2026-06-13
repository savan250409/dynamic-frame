@extends('layouts.app')

@section('title', 'Add New Doodle')

@section('content')

<div class="page-header">
  <h4 class="page-title">Add New Doodle</h4>
  <ul class="breadcrumbs">
    <li class="nav-home"><a href="{{ route('dashboard') }}"><i class="icon-home"></i></a></li>
    <li class="separator"><i class="icon-arrow-right"></i></li>
    <li class="nav-item"><a href="{{ route('doodles.index') }}">Doodle Management</a></li>
    <li class="separator"><i class="icon-arrow-right"></i></li>
    <li class="nav-item"><a href="#">Add</a></li>
  </ul>
</div>

<div class="row">
  <div class="col-lg-8 col-md-10">
    <div class="card">
      <div class="card-body">

        <div class="d-flex justify-content-between align-items-center pb-3 mb-4" style="border-bottom:2px solid #f0f0f0;">
          <div class="d-flex align-items-center">
            <i class="fas fa-plus-circle text-primary" style="font-size:2rem; margin-right:10px;"></i>
            <div>
              <h4 class="mb-0 fw-bold text-primary">Add New Doodle</h4>
              <small class="text-muted">Create a new Doodle</small>
            </div>
          </div>
          <a href="{{ route('doodles.index') }}" class="btn btn-outline-primary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Back to Doodles
          </a>
        </div>

        @if ($errors->any())
          <div class="alert alert-danger mb-3">
            <ul class="mb-0">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <form action="{{ route('doodles.store') }}" method="POST" enctype="multipart/form-data">
          @csrf

          {{-- Name --}}
          <div class="form-group mb-3">
            <label class="fw-bold">Name <span class="text-danger">*</span></label>
            <input type="text" name="name"
              class="form-control @error('name') is-invalid @enderror"
              placeholder="Doodle Name"
              value="{{ old('name') }}">
            @error('name')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          {{-- Type (Premium) --}}
          <div class="form-group mb-3">
            <label class="fw-bold d-block">Type</label>
            <div class="form-check form-check-inline d-flex align-items-center gap-2" style="margin-top:.3rem;">
              <input class="form-check-input" type="checkbox" name="is_premium" id="is-premium-toggle"
                value="1" checked style="cursor:pointer; width:18px; height:18px; accent-color:#5b4fcf;">
              <label class="mb-0 d-flex align-items-center gap-1" for="is-premium-toggle">
                <span style="background:#5b4fcf; color:#fff; font-size:.75rem; font-weight:700;
                  padding:2px 10px; border-radius:20px;">Premium (Pro)</span>
              </label>
            </div>
          </div>

          {{-- Doodle Type --}}
          <div class="form-group mb-3">
            <label class="fw-bold d-block">Doodle Type <span class="text-danger">*</span></label>
            <div class="d-flex gap-3 mt-1">
              <label class="d-flex align-items-center gap-2 px-4 py-2 @error('doodle_type') border border-danger @enderror"
                style="border:2px solid {{ old('doodle_type','image')=='image' ? '#5b4fcf' : '#dee2e6' }};
                  border-radius:8px; cursor:pointer; background:{{ old('doodle_type','image')=='image' ? '#f3f0ff' : '#fff' }};
                  min-width:120px; font-weight:600; color:#333;" id="label-image">
                <input type="radio" name="doodle_type" value="image"
                  {{ old('doodle_type','image')=='image' ? 'checked' : '' }}
                  style="accent-color:#5b4fcf; width:16px; height:16px; cursor:pointer;">
                Image
              </label>
              <label class="d-flex align-items-center gap-2 px-4 py-2"
                style="border:2px solid {{ old('doodle_type')=='line' ? '#5b4fcf' : '#dee2e6' }};
                  border-radius:8px; cursor:pointer; background:{{ old('doodle_type')=='line' ? '#f3f0ff' : '#fff' }};
                  min-width:120px; font-weight:600; color:#333;" id="label-line">
                <input type="radio" name="doodle_type" value="line"
                  {{ old('doodle_type')=='line' ? 'checked' : '' }}
                  style="accent-color:#5b4fcf; width:16px; height:16px; cursor:pointer;">
                Line
              </label>
            </div>
            @error('doodle_type')
              <div class="text-danger mt-1" style="font-size:.875rem;">{{ $message }}</div>
            @enderror
          </div>

          {{-- Doodle Image --}}
          <div class="form-group mb-4">
            <label class="fw-bold">Doodle Image <span class="text-danger">*</span></label>
            <div class="d-flex align-items-center gap-3 mt-1">
              <label for="image-input" class="btn btn-light border fw-bold mb-0"
                style="cursor:pointer; padding:.45rem 1.1rem;">
                <i class="fas fa-folder-open me-1"></i> Choose Image
              </label>
              <span id="image-filename" style="font-size:.88rem; color:#555;">No file chosen</span>
              <input type="file" name="image" id="image-input" accept=".webp,image/webp" style="display:none;">
            </div>
            <div id="image-preview" class="mt-2" style="display:none;">
              <img id="preview-img" src="" alt=""
                style="max-height:80px; max-width:180px; object-fit:contain; border-radius:8px; border:1px solid #ddd; background:#f8f9fa; padding:4px;">
            </div>
            <small class="text-warning fw-bold d-block mt-1">
              <i class="fas fa-exclamation-triangle me-1"></i> Only .webp images are allowed
            </small>
            @error('image')
              <div class="text-danger d-block mt-1" style="font-size:.875rem;">{{ $message }}</div>
            @enderror
          </div>

          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary flex-grow-1" style="font-size:1rem; padding:.7rem;">
              <i class="fas fa-plus me-1"></i> Submit
            </button>
            <a href="{{ route('doodles.index') }}" class="btn btn-light border"
              style="font-size:1rem; padding:.7rem;">Cancel</a>
          </div>
        </form>

      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<style>
  input[type="radio"]:checked ~ label { border-color: #5b4fcf !important; background: #f3f0ff !important; }
</style>
<script>
$(document).ready(function () {

  /* Doodle type radio styling */
  $('input[name="doodle_type"]').on('change', function () {
    $('#label-image, #label-line').css({ 'border-color': '#dee2e6', 'background': '#fff' });
    $(this).closest('label').css({ 'border-color': '#5b4fcf', 'background': '#f3f0ff' });
  });

  /* Image file input */
  $('#image-input').on('change', function () {
    if (this.files && this.files[0]) {
      var file = this.files[0];
      $('#image-filename').text(file.name);
      var reader = new FileReader();
      reader.onload = function (e) {
        $('#preview-img').attr('src', e.target.result);
        $('#image-preview').show();
      };
      reader.readAsDataURL(file);
    }
  });
});
</script>
@endpush
