@extends('layouts.app')

@section('title', 'Add New Filter Category')

@section('content')

<div class="page-header">
  <h4 class="page-title">Add New Filter Category</h4>
  <ul class="breadcrumbs">
    <li class="nav-home"><a href="{{ route('dashboard') }}"><i class="icon-home"></i></a></li>
    <li class="separator"><i class="icon-arrow-right"></i></li>
    <li class="nav-item"><a href="{{ route('filter-categories.index') }}">Filter Category Management</a></li>
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
              <h4 class="mb-0 fw-bold text-primary">Add New Filter Category</h4>
              <small class="text-muted">Create a new Filter Category</small>
            </div>
          </div>
          <a href="{{ route('filter-categories.index') }}" class="btn btn-outline-primary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Back to Categories
          </a>
        </div>

        @if ($errors->any())
          <div class="alert alert-danger mb-3">
            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
          </div>
        @endif

        <form action="{{ route('filter-categories.store') }}" method="POST" enctype="multipart/form-data">
          @csrf

          {{-- Category Name --}}
          <div class="form-group mb-3">
            <label class="fw-bold">Category Name <span class="text-danger">*</span></label>
            <input type="text" name="name"
              class="form-control @error('name') is-invalid @enderror"
              placeholder="Category Name"
              value="{{ old('name') }}">
            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>

          {{-- Thumbnail Image --}}
          <div class="form-group mb-3">
            <label class="fw-bold">Thumbnail Image</label>
            <div class="d-flex align-items-center gap-2 mt-1">
              <label for="image-input" class="btn btn-light border fw-bold mb-0"
                style="cursor:pointer; padding:.45rem 1.1rem; white-space:nowrap;">
                Upload Image
              </label>
              <input type="text" id="image-filename-display" class="form-control" placeholder="Upload Image" readonly
                style="cursor:pointer;" onclick="$('#image-input').trigger('click');">
              <label for="image-input"
                style="background:#7c3aed; color:#fff; border:none; border-radius:6px; padding:.45rem 1.2rem;
                  font-weight:700; cursor:pointer; white-space:nowrap; margin:0;">
                Upload
              </label>
              <input type="file" name="image" id="image-input" accept=".webp,image/webp" style="display:none;">
            </div>
            <div id="image-preview" class="mt-2" style="display:none;">
              <img id="preview-img" src="" alt=""
                style="width:60px; height:60px; object-fit:cover; border-radius:50%; border:2px solid #e8e4ff;">
            </div>
            <small class="text-warning fw-bold d-block mt-1">
              <i class="fas fa-exclamation-triangle me-1"></i> Only .webp images are allowed
            </small>
            @error('image')<div class="text-danger d-block mt-1" style="font-size:.875rem;">{{ $message }}</div>@enderror
          </div>

          {{-- Active --}}
          <div class="form-group mb-4">
            <div class="form-check d-flex align-items-center gap-2">
              <input class="form-check-input" type="checkbox" name="status" id="status-toggle"
                value="1" checked style="cursor:pointer; width:18px; height:18px; accent-color:#5b4fcf;">
              <label class="fw-bold mb-0 d-flex align-items-center gap-1" for="status-toggle">
                <span style="background:#5b4fcf; color:#fff; font-size:.75rem; font-weight:700;
                  padding:2px 10px; border-radius:20px;">Active</span>
              </label>
            </div>
          </div>

          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary flex-grow-1" style="font-size:1rem; padding:.7rem;">
              <i class="fas fa-plus me-1"></i> Submit
            </button>
            <a href="{{ route('filter-categories.index') }}" class="btn btn-light border"
              style="font-size:1rem; padding:.7rem;">Cancel</a>
          </div>
        </form>

      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function () {
  $('#image-input').on('change', function () {
    if (this.files && this.files[0]) {
      var file = this.files[0];
      $('#image-filename-display').val(file.name);
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
