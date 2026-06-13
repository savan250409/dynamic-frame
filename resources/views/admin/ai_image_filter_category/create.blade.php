@extends('layouts.app')

@section('title', 'Add Category')

@section('content')

<div class="page-header">
  <h4 class="page-title">Add Category</h4>
  <ul class="breadcrumbs">
    <li class="nav-home"><a href="{{ route('dashboard') }}"><i class="icon-home"></i></a></li>
    <li class="separator"><i class="icon-arrow-right"></i></li>
    <li class="nav-item"><a href="{{ route('ai-image-filter-categories.index') }}">AI Image Filter Category</a></li>
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
              <h4 class="mb-0 fw-bold text-primary">Add New Category</h4>
              <small class="text-muted">Create a new AI image filter category</small>
            </div>
          </div>
          <a href="{{ route('ai-image-filter-categories.index') }}"
            class="btn btn-outline-primary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Back to Categories
          </a>
        </div>

        <form action="{{ route('ai-image-filter-categories.store') }}" method="POST" enctype="multipart/form-data">
          @csrf

          <div class="form-group mb-3">
            <label class="fw-bold">Category Name</label>
            <input type="text" name="category_name"
              class="form-control @error('category_name') is-invalid @enderror"
              placeholder="Enter category name"
              value="{{ old('category_name') }}">
            @error('category_name')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="form-group mb-3">
            <label class="fw-bold">Thumbnail Image</label>
            <input type="file" name="category_image" id="category-image-input"
              class="form-control @error('category_image') is-invalid @enderror"
              accept=".webp,image/webp">
            <small class="text-warning fw-bold d-block mt-1">
              <i class="fas fa-exclamation-triangle me-1"></i> Upload Image (.webp only)
            </small>
            @error('category_image')
              <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
            <div class="mt-2">
              <img id="image-preview" src="" alt=""
                style="width:100px;height:100px;object-fit:cover;border-radius:6px;border:1px solid #ddd;display:none;">
            </div>
          </div>

          <div class="form-group mb-4">
            <div class="d-flex align-items-center gap-2">
              <div class="form-check form-switch mb-0">
                <input class="form-check-input" type="checkbox" name="status" id="status-toggle"
                  value="1" checked style="cursor:pointer; width:40px; height:22px;">
              </div>
              <div>
                <div class="fw-bold">Active Status</div>
                <small class="text-muted">Determines visibility in the mobile app.</small>
              </div>
            </div>
          </div>

          <button type="submit" class="btn btn-primary w-100" style="font-size:1rem; padding:.7rem;">
            <i class="fas fa-plus me-1"></i> Add Category
          </button>
        </form>

      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
  $('#category-image-input').on('change', function () {
    if (this.files && this.files[0]) {
      var reader = new FileReader();
      reader.onload = function (e) {
        $('#image-preview').attr('src', e.target.result).show();
      };
      reader.readAsDataURL(this.files[0]);
    }
  });
</script>
@endpush
