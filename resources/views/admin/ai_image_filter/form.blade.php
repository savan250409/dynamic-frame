@extends('layouts.app')

@section('title', 'Edit Dynamic Frame')

@section('content')

<div class="page-header">
  <h4 class="page-title">Edit Dynamic Frame</h4>
  <ul class="breadcrumbs">
    <li class="nav-home"><a href="{{ route('dashboard') }}"><i class="icon-home"></i></a></li>
    <li class="separator"><i class="icon-arrow-right"></i></li>
    <li class="nav-item"><a href="{{ route('ai-image-filters.index') }}">Dynamic Frame</a></li>
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
              <h4 class="mb-0 fw-bold text-primary">Edit Dynamic Frame</h4>
              <small class="text-muted">Update filter details</small>
            </div>
          </div>
          <a href="{{ route('ai-image-filters.index') }}" class="btn btn-outline-primary btn-sm">
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
            <label class="fw-bold">Zip File</label>
            @if($aiImageFilter->zip_file && $aiImageFilter->category)
              <div class="mb-2 p-2 bg-light rounded d-flex align-items-center gap-2">
                <i class="fas fa-file-archive text-primary"></i>
                <a href="{{ asset('upload/ai_image_filter/'.rawurlencode($aiImageFilter->category->category_name).'/zip/'.rawurlencode($aiImageFilter->zip_file)) }}"
                  target="_blank" class="text-primary" style="font-size:.85rem; word-break:break-all;">
                  {{ $aiImageFilter->zip_file }}
                </a>
              </div>
            @endif
            <input type="file" name="zip_file"
              class="form-control @error('zip_file') is-invalid @enderror"
              accept=".zip,application/zip">
            <small class="text-muted d-block mt-1">Leave empty to keep current zip file.</small>
            <small class="text-warning fw-bold d-block">
              <i class="fas fa-exclamation-triangle me-1"></i> Upload Zip File (.zip only)
            </small>
            @error('zip_file')
              <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
          </div>

          <div class="form-group mb-3">
            <label class="fw-bold">No. of Input Count <span class="text-danger">*</span></label>
            <input type="number" name="input_count"
              class="form-control @error('input_count') is-invalid @enderror"
              value="{{ old('input_count', $aiImageFilter->input_count ?? 1) }}" min="1" required>
            @error('input_count')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="form-group mb-4">
            <label class="fw-bold">Thumbnail</label>
            @if($aiImageFilter->image_path && $aiImageFilter->category)
              <div class="mb-2">
                <img id="current-img"
                  src="{{ asset('upload/ai_image_filter/'.rawurlencode($aiImageFilter->category->category_name).'/images/'.rawurlencode($aiImageFilter->image_path)) }}"
                  style="width:100px;height:100px;object-fit:cover;border-radius:6px;border:1px solid #ddd;">
              </div>
            @endif
            <input type="file" name="image" id="image-input"
              class="form-control @error('image') is-invalid @enderror"
              accept=".webp,image/webp">
            <small class="text-muted d-block mt-1">Leave empty to keep current thumbnail.</small>
            <small class="text-warning fw-bold d-block">
              <i class="fas fa-exclamation-triangle me-1"></i> Upload Thumbnail (.webp only)
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
            <button type="submit" class="btn btn-primary" style="font-size:1rem; padding:.7rem 2rem;">
              <i class="fas fa-save me-1"></i> Update Filter
            </button>
            <a href="{{ route('ai-image-filters.index') }}" class="btn btn-light border"
              style="font-size:1rem; padding:.7rem 2rem;">Cancel</a>
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
