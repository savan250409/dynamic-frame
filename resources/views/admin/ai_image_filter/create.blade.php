@extends('layouts.app')

@section('title', 'Add AI Image Filter')

@section('content')

<div class="page-header">
  <h4 class="page-title">Add AI Image Filter</h4>
  <ul class="breadcrumbs">
    <li class="nav-home"><a href="{{ route('dashboard') }}"><i class="icon-home"></i></a></li>
    <li class="separator"><i class="icon-arrow-right"></i></li>
    <li class="nav-item"><a href="{{ route('ai-image-filters.index') }}">AI Image Filter</a></li>
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
              <h4 class="mb-0 fw-bold text-primary">Add New AI Image Filter</h4>
              <small class="text-muted">Add a new filter to a category</small>
            </div>
          </div>
          <a href="{{ route('ai-image-filters.index') }}"
            class="btn btn-outline-primary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Back to Filters
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

        <form action="{{ route('ai-image-filters.store') }}" method="POST" enctype="multipart/form-data">
          @csrf

          <div class="form-group mb-3">
            <label class="fw-bold">Category <span class="text-danger">*</span></label>
            <select name="category_id"
              class="form-control @error('category_id') is-invalid @enderror" required>
              <option value="">Select Category</option>
              @foreach($categories as $cat)
                <option value="{{ $cat->id }}"
                  {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                  {{ $cat->category_name }}
                </option>
              @endforeach
            </select>
            @error('category_id')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="form-group mb-3">
            <label class="fw-bold">Name <span class="text-danger">*</span></label>
            <input type="text" name="name"
              class="form-control @error('name') is-invalid @enderror"
              placeholder="Enter filter name"
              value="{{ old('name') }}" required>
            @error('name')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="form-group mb-3">
            <label class="fw-bold">AI Prompt <span class="text-danger">*</span></label>
            <textarea name="ai_prompt" rows="4"
              class="form-control @error('ai_prompt') is-invalid @enderror"
              placeholder="Enter AI prompt for this filter" required>{{ old('ai_prompt') }}</textarea>
            @error('ai_prompt')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="form-group mb-3">
            <label class="fw-bold">Zip File <span class="text-danger">*</span></label>
            <input type="file" name="zip_file"
              class="form-control @error('zip_file') is-invalid @enderror"
              accept=".zip,application/zip" required>
            <small class="text-warning fw-bold d-block mt-1">
              <i class="fas fa-exclamation-triangle me-1"></i> Upload Zip File (.zip only)
            </small>
            @error('zip_file')
              <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
          </div>

          <div class="form-group mb-4">
            <label class="fw-bold">Thumbnail Image <span class="text-danger">*</span></label>
            <input type="file" name="image" id="image-input"
              class="form-control @error('image') is-invalid @enderror"
              accept=".webp,image/webp" required>
            <small class="text-warning fw-bold d-block mt-1">
              <i class="fas fa-exclamation-triangle me-1"></i> Upload Image (.webp only)
            </small>
            @error('image')
              <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
            <div class="mt-2">
              <img id="image-preview" src="" alt=""
                style="width:120px;height:120px;object-fit:cover;border-radius:6px;border:1px solid #ddd;display:none;">
            </div>
          </div>

          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary flex-grow-1" style="font-size:1rem; padding:.7rem;">
              <i class="fas fa-plus me-1"></i> Submit
            </button>
            <a href="{{ route('ai-image-filters.index') }}" class="btn btn-light border"
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
  $('#image-input').on('change', function () {
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
