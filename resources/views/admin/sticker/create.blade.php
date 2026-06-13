@extends('layouts.app')

@section('title', 'Add New Sticker')

@section('content')

<div class="page-header">
  <h4 class="page-title">Add New Sticker</h4>
  <ul class="breadcrumbs">
    <li class="nav-home"><a href="{{ route('dashboard') }}"><i class="icon-home"></i></a></li>
    <li class="separator"><i class="icon-arrow-right"></i></li>
    <li class="nav-item"><a href="{{ route('stickers.index') }}">Sticker Management</a></li>
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
              <h4 class="mb-0 fw-bold text-primary">Add New Sticker</h4>
              <small class="text-muted">Create a new Sticker</small>
            </div>
          </div>
          <a href="{{ route('stickers.index') }}" class="btn btn-outline-primary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Back to Stickers
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

        <form action="{{ route('stickers.store') }}" method="POST" enctype="multipart/form-data">
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
            <label class="fw-bold">Sticker Images <small class="text-muted">(Drag & drop to upload — drag handle to reorder)</small></label>

            <div id="drop-zone"
              style="border:2px dashed #a78bfa; border-radius:10px; padding:2.5rem 1rem;
                     text-align:center; cursor:pointer; background:#faf8ff; transition:background .2s;">
              <div id="drop-placeholder">
                <div style="font-size:3rem; color:#a78bfa; margin-bottom:.5rem;">
                  <i class="fas fa-cloud-upload-alt"></i>
                </div>
                <div style="font-size:1rem; color:#555;">Drag &amp; drop images here</div>
                <div style="font-size:.85rem; color:#888; margin-top:.3rem;">or click anywhere in this area to browse files</div>
              </div>
              <input type="file" name="images[]" id="sticker-file-input"
                accept=".webp,image/webp" multiple
                style="display:none;">
              <div id="image-preview-grid" class="d-flex flex-wrap mt-3" style="gap:10px; justify-content:center;"></div>
            </div>

            <small class="text-warning fw-bold d-block mt-2">
              <i class="fas fa-exclamation-triangle me-1"></i> Upload Images (.webp only)
            </small>
            @error('images')
              <div class="text-danger d-block mt-1" style="font-size:.875rem;">{{ $message }}</div>
            @enderror
            @error('images.*')
              <div class="text-danger d-block mt-1" style="font-size:.875rem;">{{ $message }}</div>
            @enderror
          </div>

          <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-primary flex-grow-1" style="font-size:1rem; padding:.7rem;">
              <i class="fas fa-plus me-1"></i> Submit
            </button>
            <a href="{{ route('stickers.index') }}" class="btn btn-light border"
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
  #drop-zone.drag-over { background: #ede9fe !important; border-color: #7c3aed !important; }
  .preview-thumb {
    position: relative; width: 80px; height: 80px; border-radius: 8px;
    overflow: hidden; border: 1px solid #ddd; flex-shrink: 0;
  }
  .preview-thumb img { width: 100%; height: 100%; object-fit: cover; display: block; }
  .preview-thumb .remove-thumb {
    position: absolute; top: 2px; right: 2px;
    background: rgba(0,0,0,.55); color: #fff; border: none;
    border-radius: 50%; width: 20px; height: 20px;
    font-size: .65rem; cursor: pointer; line-height: 20px; text-align: center; padding: 0;
  }
</style>
<script>
$(document).ready(function () {
  var selectedFiles = [];

  var $dropZone   = $('#drop-zone');
  var $fileInput  = $('#sticker-file-input');
  var $grid       = $('#image-preview-grid');
  var $placeholder = $('#drop-placeholder');

  $dropZone.on('click', function (e) {
    if (!$(e.target).hasClass('remove-thumb') && !$(e.target).closest('.remove-thumb').length) {
      $fileInput.trigger('click');
    }
  });

  $dropZone.on('dragover', function (e) { e.preventDefault(); $dropZone.addClass('drag-over'); });
  $dropZone.on('dragleave drop', function () { $dropZone.removeClass('drag-over'); });
  $dropZone.on('drop', function (e) {
    e.preventDefault();
    handleFiles(e.originalEvent.dataTransfer.files);
  });

  $fileInput.on('change', function () {
    handleFiles(this.files);
    this.value = '';
  });

  function handleFiles(files) {
    $.each(files, function (i, file) {
      if (file.type !== 'image/webp') return;
      selectedFiles.push(file);
      renderPreviews();
    });
    syncInput();
  }

  function renderPreviews() {
    $grid.empty();
    if (selectedFiles.length > 0) {
      $placeholder.hide();
    } else {
      $placeholder.show();
    }
    $.each(selectedFiles, function (i, file) {
      var reader = new FileReader();
      reader.onload = function (e) {
        var $thumb = $('<div class="preview-thumb">');
        $thumb.append('<img src="' + e.target.result + '" alt="">');
        var $rm = $('<button type="button" class="remove-thumb" data-index="' + i + '"><i class="fas fa-times"></i></button>');
        $thumb.append($rm);
        $grid.append($thumb);
      };
      reader.readAsDataURL(file);
    });
  }

  $(document).on('click', '.remove-thumb', function (e) {
    e.stopPropagation();
    var idx = parseInt($(this).data('index'));
    selectedFiles.splice(idx, 1);
    renderPreviews();
    syncInput();
  });

  function syncInput() {
    var dt = new DataTransfer();
    $.each(selectedFiles, function (i, file) { dt.items.add(file); });
    $fileInput[0].files = dt.files;
  }
});
</script>
@endpush
