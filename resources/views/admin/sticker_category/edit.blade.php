@extends('layouts.app')

@section('title', 'Edit Sticker Category')

@section('content')

<div class="page-header">
  <h4 class="page-title">Edit Sticker Category</h4>
  <ul class="breadcrumbs">
    <li class="nav-home"><a href="{{ route('dashboard') }}"><i class="icon-home"></i></a></li>
    <li class="separator"><i class="icon-arrow-right"></i></li>
    <li class="nav-item"><a href="{{ route('sticker-categories.index') }}">Sticker Category</a></li>
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
              <h4 class="mb-0 fw-bold text-primary">Edit Sticker Category</h4>
              <small class="text-muted">Update sticker category details</small>
            </div>
          </div>
          <a href="{{ route('sticker-categories.index') }}" class="btn btn-outline-primary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Back to Categories
          </a>
        </div>

        <form action="{{ route('sticker-categories.update', $stickerCategory->id) }}"
          method="POST" enctype="multipart/form-data">
          @csrf
          @method('PUT')

          <div class="form-group mb-3">
            <label class="fw-bold">Category Name</label>
            <input type="text" name="category_name"
              class="form-control @error('category_name') is-invalid @enderror"
              placeholder="Category Name"
              value="{{ old('category_name', $stickerCategory->category_name) }}">
            @error('category_name')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="form-group mb-3">
            <label class="fw-bold">Current Thumbnail</label>
            <div class="mb-2">
              @if($stickerCategory->image)
                <img id="current-preview"
                  src="{{ asset('upload/sticker/'.rawurlencode($stickerCategory->category_name).'/category image/'.rawurlencode($stickerCategory->image)) }}"
                  alt="thumbnail" style="width:100px;height:100px;object-fit:cover;border-radius:6px;border:1px solid #ddd;">
              @else
                <span class="text-muted">No image uploaded</span>
              @endif
            </div>

            <label class="fw-bold">Replace Thumbnail</label>
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
              <img id="new-preview" src="" alt=""
                style="width:100px;height:100px;object-fit:cover;border-radius:6px;border:1px solid #ddd;display:none;">
            </div>
          </div>

          <div class="form-group mb-3">
            <div class="d-flex align-items-center gap-3">
              <div class="d-flex align-items-center gap-2">
                <div class="form-check form-switch mb-0">
                  <input class="form-check-input" type="checkbox" name="is_premium" id="is-premium-toggle"
                    value="1" {{ $stickerCategory->is_premium ? 'checked' : '' }}
                    style="cursor:pointer; width:40px; height:22px;">
                </div>
                <div>
                  <div class="fw-bold">Premium (Pro)</div>
                </div>
              </div>
              <div class="d-flex align-items-center gap-2">
                <div class="form-check form-switch mb-0">
                  <input class="form-check-input" type="checkbox" name="status" id="status-toggle"
                    value="1" {{ $stickerCategory->status ? 'checked' : '' }}
                    style="cursor:pointer; width:40px; height:22px;">
                </div>
                <div>
                  <div class="fw-bold">Active</div>
                </div>
              </div>
            </div>
          </div>

          <button type="submit" class="btn btn-primary w-100 mt-3" style="font-size:1rem; padding:.7rem;">
            <i class="fas fa-save me-1"></i> Update Category
          </button>
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
        $('#new-preview').attr('src', e.target.result).show();
        $('#current-preview').hide();
      };
      reader.readAsDataURL(this.files[0]);
    }
  });
</script>
@endpush
