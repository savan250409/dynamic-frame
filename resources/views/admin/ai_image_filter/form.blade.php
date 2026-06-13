@extends('layouts.app')

@section('title', 'Edit AI Image Filter')

@section('content')

<div class="page-header">
  <h4 class="page-title">Edit AI Image Filter</h4>
  <ul class="breadcrumbs">
    <li class="nav-home"><a href="{{ route('dashboard') }}"><i class="icon-home"></i></a></li>
    <li class="separator"><i class="icon-arrow-right"></i></li>
    <li class="nav-item"><a href="{{ route('ai-image-filters.index') }}">AI Image Filter</a></li>
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
              <h4 class="mb-0 fw-bold text-primary">Edit AI Image Filter</h4>
              <small class="text-muted">Update filter details</small>
            </div>
          </div>
          <a href="{{ route('ai-image-filters.index') }}"
            class="btn btn-outline-primary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Back to Filters
          </a>
        </div>

        <form action="{{ route('ai-image-filters.update', $aiImageFilter->id) }}"
          method="POST" enctype="multipart/form-data">
          @csrf
          @method('PUT')

          <div class="form-group mb-3">
            <label class="fw-bold">Category <span class="text-danger">*</span></label>
            <select name="category_id"
              class="form-control @error('category_id') is-invalid @enderror" required>
              <option value="">Select Category</option>
              @foreach($categories as $cat)
                <option value="{{ $cat->id }}"
                  {{ old('category_id', $aiImageFilter->category_id) == $cat->id ? 'selected' : '' }}>
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
              value="{{ old('name', $aiImageFilter->name) }}" required>
            @error('name')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="form-group mb-3">
            <label class="fw-bold">AI Prompt <span class="text-danger">*</span></label>
            <textarea name="ai_prompt" rows="4"
              class="form-control @error('ai_prompt') is-invalid @enderror"
              placeholder="Enter AI prompt" required>{{ old('ai_prompt', $aiImageFilter->ai_prompt) }}</textarea>
            @error('ai_prompt')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="form-group mb-3">
            <label class="fw-bold">Current Zip File</label>
            <div class="mb-2">
              @if($aiImageFilter->zip_file && $aiImageFilter->category)
                <a href="{{ asset('upload/ai_image_filter/'.rawurlencode($aiImageFilter->category->category_name).'/zip/'.rawurlencode($aiImageFilter->zip_file)) }}"
                  target="_blank" class="d-inline-flex align-items-center gap-2 text-primary" style="font-size:.9rem;">
                  <i class="fas fa-file-archive"></i>
                  <span>{{ $aiImageFilter->zip_file }}</span>
                </a>
              @else
                <span class="text-muted">No zip file uploaded</span>
              @endif
            </div>
            <label class="fw-bold">Replace Zip File</label>
            <input type="file" name="zip_file"
              class="form-control @error('zip_file') is-invalid @enderror"
              accept=".zip,application/zip">
            <small class="text-muted d-block">Leave empty to keep current zip file.</small>
            <small class="text-warning fw-bold d-block">
              <i class="fas fa-exclamation-triangle me-1"></i> Only .zip files are allowed
            </small>
            @error('zip_file')
              <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
          </div>

          <div class="form-group mb-4">
            <label class="fw-bold">Current Image</label>
            <div class="mb-2">
              @if($aiImageFilter->image_path && $aiImageFilter->category)
                <img id="current-img"
                  src="{{ asset('upload/ai_image_filter/'.rawurlencode($aiImageFilter->category->category_name).'/images/'.rawurlencode($aiImageFilter->image_path)) }}"
                  style="width:100px;height:100px;object-fit:cover;border-radius:6px;border:1px solid #ddd;">
              @endif
            </div>

            <label class="fw-bold">Replace Image</label>
            <input type="file" name="image" id="image-input"
              class="form-control @error('image') is-invalid @enderror"
              accept=".webp,image/webp">
            <small class="text-muted d-block">Leave empty to keep current image.</small>
            <small class="text-warning fw-bold d-block">
              <i class="fas fa-exclamation-triangle me-1"></i> Only .webp images are allowed
            </small>
            @error('image')
              <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
            <div class="mt-2">
              <img id="new-img-preview" src="" alt=""
                style="width:100px;height:100px;object-fit:cover;border-radius:6px;border:1px solid #ddd;display:none;">
            </div>
          </div>

          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary flex-grow-1" style="font-size:1rem; padding:.7rem;">
              <i class="fas fa-save me-1"></i> Update Filter
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
        $('#new-img-preview').attr('src', e.target.result).show();
        $('#current-img').hide();
      };
      reader.readAsDataURL(this.files[0]);
    }
  });
</script>
@endpush
