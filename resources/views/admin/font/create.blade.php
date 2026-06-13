@extends('layouts.app')

@section('title', 'Add New Font')

@section('content')

<div class="page-header">
  <h4 class="page-title">Add New Font</h4>
  <ul class="breadcrumbs">
    <li class="nav-home"><a href="{{ route('dashboard') }}"><i class="icon-home"></i></a></li>
    <li class="separator"><i class="icon-arrow-right"></i></li>
    <li class="nav-item"><a href="{{ route('fonts.index') }}">Font Management</a></li>
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
              <h4 class="mb-0 fw-bold text-primary">Add New Font</h4>
              <small class="text-muted">Create a new font</small>
            </div>
          </div>
          <a href="{{ route('fonts.index') }}" class="btn btn-outline-primary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Back to Fonts
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

        <form action="{{ route('fonts.store') }}" method="POST" enctype="multipart/form-data">
          @csrf

          <div class="form-group mb-3">
            <label class="fw-bold">Font Name <span class="text-danger">*</span></label>
            <input type="text" name="font_name"
              class="form-control @error('font_name') is-invalid @enderror"
              placeholder="Enter Font Name"
              value="{{ old('font_name') }}">
            @error('font_name')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="form-group mb-3">
            <div class="form-check form-switch mb-0 d-flex align-items-center gap-2">
              <input class="form-check-input" type="checkbox" name="is_premium" id="is-premium-toggle"
                value="1" checked style="cursor:pointer; width:40px; height:22px;">
              <label class="fw-bold mb-0" for="is-premium-toggle">Premium (Pro)</label>
            </div>
          </div>

          {{-- Preview Image --}}
          <div class="form-group mb-3">
            <label class="fw-bold">Font Preview Image <span class="text-danger">*</span></label>
            <div id="preview-drop-zone"
              style="border:2px dashed #a78bfa; border-radius:10px; padding:2rem 1rem;
                     text-align:center; cursor:pointer; background:#faf8ff; transition:background .2s;">
              <div id="preview-placeholder">
                <div style="font-size:2.5rem; color:#a78bfa; margin-bottom:.4rem;">
                  <i class="fas fa-cloud-upload-alt"></i>
                </div>
                <div style="font-size:.95rem; color:#555;">Drag &amp; drop a file here or click</div>
                <div style="font-size:.8rem; color:#888; margin-top:.2rem;">.webp only</div>
              </div>
              <input type="file" name="preview_image" id="preview-input"
                accept=".webp,image/webp" style="display:none;">
              <div id="preview-thumb" class="mt-2" style="display:none;">
                <img id="preview-img" src="" alt=""
                  style="max-height:80px; max-width:200px; object-fit:contain; border-radius:6px; border:1px solid #ddd;">
                <div id="preview-filename" class="text-muted mt-1" style="font-size:.8rem;"></div>
              </div>
            </div>
            <small class="text-warning fw-bold d-block mt-1">
              <i class="fas fa-exclamation-triangle me-1"></i> Upload Image (.webp only)
            </small>
            @error('preview_image')
              <div class="text-danger d-block mt-1" style="font-size:.875rem;">{{ $message }}</div>
            @enderror
          </div>

          {{-- Font File --}}
          <div class="form-group mb-4">
            <label class="fw-bold">Font File (.ttf, .otf) <span class="text-danger">*</span></label>
            <div id="font-drop-zone"
              style="border:2px dashed #a78bfa; border-radius:10px; padding:2rem 1rem;
                     text-align:center; cursor:pointer; background:#faf8ff; transition:background .2s;">
              <div id="font-placeholder">
                <div style="font-size:2.5rem; color:#a78bfa; margin-bottom:.4rem;">
                  <i class="fas fa-cloud-upload-alt"></i>
                </div>
                <div style="font-size:.95rem; color:#555;">Drag &amp; drop a file here or click</div>
                <div style="font-size:.8rem; color:#888; margin-top:.2rem;">.ttf or .otf only</div>
              </div>
              <input type="file" name="font_file" id="font-input"
                accept=".ttf,.otf" style="display:none;">
              <div id="font-thumb" class="mt-2" style="display:none;">
                <div class="d-inline-flex align-items-center gap-2 px-3 py-2"
                  style="background:#f0ebff; border-radius:8px; border:1px solid #d8b4fe;">
                  <i class="fas fa-file-alt" style="color:#7c3aed; font-size:1.2rem;"></i>
                  <span id="font-filename" style="font-size:.85rem; color:#4c1d95;"></span>
                </div>
              </div>
            </div>
            <small class="text-warning fw-bold d-block mt-1">
              <i class="fas fa-exclamation-triangle me-1"></i> Upload Font File (.ttf or .otf only)
            </small>
            @error('font_file')
              <div class="text-danger d-block mt-1" style="font-size:.875rem;">{{ $message }}</div>
            @enderror
          </div>

          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary flex-grow-1" style="font-size:1rem; padding:.7rem;">
              <i class="fas fa-plus me-1"></i> Submit
            </button>
            <a href="{{ route('fonts.index') }}" class="btn btn-light border"
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
  #preview-drop-zone.drag-over,
  #font-drop-zone.drag-over { background: #ede9fe !important; border-color: #7c3aed !important; }
</style>
<script>
$(document).ready(function () {

  /* Preview image drop zone */
  var $pZone  = $('#preview-drop-zone');
  var $pInput = $('#preview-input');

  $pZone.on('click', function () { $pInput.trigger('click'); });
  $pZone.on('dragover', function (e) { e.preventDefault(); $pZone.addClass('drag-over'); });
  $pZone.on('dragleave drop', function () { $pZone.removeClass('drag-over'); });
  $pZone.on('drop', function (e) {
    e.preventDefault();
    var files = e.originalEvent.dataTransfer.files;
    if (files.length) setPreviewFile(files[0]);
  });
  $pInput.on('change', function () {
    if (this.files && this.files[0]) setPreviewFile(this.files[0]);
  });

  function setPreviewFile(file) {
    var reader = new FileReader();
    reader.onload = function (e) {
      $('#preview-img').attr('src', e.target.result);
      $('#preview-filename').text(file.name);
      $('#preview-placeholder').hide();
      $('#preview-thumb').show();
    };
    reader.readAsDataURL(file);

    var dt = new DataTransfer();
    dt.items.add(file);
    $pInput[0].files = dt.files;
  }

  /* Font file drop zone */
  var $fZone  = $('#font-drop-zone');
  var $fInput = $('#font-input');

  $fZone.on('click', function () { $fInput.trigger('click'); });
  $fZone.on('dragover', function (e) { e.preventDefault(); $fZone.addClass('drag-over'); });
  $fZone.on('dragleave drop', function () { $fZone.removeClass('drag-over'); });
  $fZone.on('drop', function (e) {
    e.preventDefault();
    var files = e.originalEvent.dataTransfer.files;
    if (files.length) setFontFile(files[0]);
  });
  $fInput.on('change', function () {
    if (this.files && this.files[0]) setFontFile(this.files[0]);
  });

  function setFontFile(file) {
    $('#font-filename').text(file.name);
    $('#font-placeholder').hide();
    $('#font-thumb').show();

    var dt = new DataTransfer();
    dt.items.add(file);
    $fInput[0].files = dt.files;
  }
});
</script>
@endpush
