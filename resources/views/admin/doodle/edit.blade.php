@extends('layouts.app')

@section('title', 'Edit Doodle')

@section('content')

<div class="page-header">
  <h4 class="page-title">Edit Doodle</h4>
  <ul class="breadcrumbs">
    <li class="nav-home"><a href="{{ route('dashboard') }}"><i class="icon-home"></i></a></li>
    <li class="separator"><i class="icon-arrow-right"></i></li>
    <li class="nav-item"><a href="{{ route('doodles.index') }}">Doodle Management</a></li>
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
              <h4 class="mb-0 fw-bold text-primary">Edit Doodle</h4>
              <small class="text-muted">Update doodle details</small>
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

        <form action="{{ route('doodles.update', $doodle->id) }}" method="POST" enctype="multipart/form-data">
          @csrf
          @method('PUT')

          {{-- Name --}}
          <div class="form-group mb-3">
            <label class="fw-bold">Name <span class="text-danger">*</span></label>
            <input type="text" name="name"
              class="form-control @error('name') is-invalid @enderror"
              placeholder="Doodle Name"
              value="{{ old('name', $doodle->name) }}">
            @error('name')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          {{-- Type (Premium) --}}
          <div class="form-group mb-3">
            <label class="fw-bold d-block">Type</label>
            <div class="form-check form-check-inline d-flex align-items-center gap-2" style="margin-top:.3rem;">
              <input class="form-check-input" type="checkbox" name="is_premium" id="is-premium-toggle"
                value="1" {{ $doodle->is_premium ? 'checked' : '' }}
                style="cursor:pointer; width:18px; height:18px; accent-color:#5b4fcf;">
              <label class="mb-0 d-flex align-items-center gap-1" for="is-premium-toggle">
                <span style="background:#5b4fcf; color:#fff; font-size:.75rem; font-weight:700;
                  padding:2px 10px; border-radius:20px;">Premium (Pro)</span>
              </label>
            </div>
          </div>

          {{-- Active status --}}
          <div class="form-group mb-3">
            <div class="form-check form-switch mb-0 d-flex align-items-center gap-2">
              <input class="form-check-input" type="checkbox" name="status" id="status-toggle"
                value="1" {{ $doodle->status ? 'checked' : '' }}
                style="cursor:pointer; width:40px; height:22px;">
              <label class="fw-bold mb-0" for="status-toggle">Active</label>
            </div>
          </div>

          {{-- Doodle Type --}}
          <div class="form-group mb-3">
            <label class="fw-bold d-block">Doodle Type <span class="text-danger">*</span></label>
            @php $currentType = old('doodle_type', $doodle->doodle_type); @endphp
            <div class="d-flex gap-3 mt-1">
              <label class="d-flex align-items-center gap-2 px-4 py-2"
                style="border:2px solid {{ $currentType=='image' ? '#5b4fcf' : '#dee2e6' }};
                  border-radius:8px; cursor:pointer; background:{{ $currentType=='image' ? '#f3f0ff' : '#fff' }};
                  min-width:120px; font-weight:600; color:#333;" id="label-image">
                <input type="radio" name="doodle_type" value="image"
                  {{ $currentType=='image' ? 'checked' : '' }}
                  style="accent-color:#5b4fcf; width:16px; height:16px; cursor:pointer;">
                Image
              </label>
              <label class="d-flex align-items-center gap-2 px-4 py-2"
                style="border:2px solid {{ $currentType=='line' ? '#5b4fcf' : '#dee2e6' }};
                  border-radius:8px; cursor:pointer; background:{{ $currentType=='line' ? '#f3f0ff' : '#fff' }};
                  min-width:120px; font-weight:600; color:#333;" id="label-line">
                <input type="radio" name="doodle_type" value="line"
                  {{ $currentType=='line' ? 'checked' : '' }}
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
            <label class="fw-bold">Doodle Image</label>

            @if($doodle->image)
              <div class="mb-2 d-flex align-items-center gap-3">
                <img id="current-image"
                  src="{{ asset('upload/doodle/'.rawurlencode($doodle->name).'/'.rawurlencode($doodle->image)) }}"
                  alt="{{ $doodle->name }}"
                  style="max-height:60px; max-width:120px; object-fit:contain; border-radius:6px; border:1px solid #ddd; background:#f8f9fa; padding:4px;">
                <small class="text-muted">{{ $doodle->image }}</small>
              </div>
            @endif

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
            <small class="text-muted d-block mt-1">Leave empty to keep current image.</small>
            <small class="text-warning fw-bold d-block">
              <i class="fas fa-exclamation-triangle me-1"></i> Only .webp images are allowed
            </small>
            @error('image')
              <div class="text-danger d-block mt-1" style="font-size:.875rem;">{{ $message }}</div>
            @enderror
          </div>

          <button type="submit" class="btn btn-primary w-100" style="font-size:1rem; padding:.7rem;">
            <i class="fas fa-save me-1"></i> Update Doodle
          </button>
        </form>

      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
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
        $('#current-image').hide();
      };
      reader.readAsDataURL(file);
    }
  });
});
</script>
@endpush
