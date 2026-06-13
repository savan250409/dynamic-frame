@extends('layouts.app')

@section('title', 'Import Filters from CSV')

@section('content')

<div class="page-header">
  <h4 class="page-title">Import Filters</h4>
  <ul class="breadcrumbs">
    <li class="nav-home"><a href="{{ route('dashboard') }}"><i class="icon-home"></i></a></li>
    <li class="separator"><i class="icon-arrow-right"></i></li>
    <li class="nav-item"><a href="{{ route('filters.index') }}">Filter Management</a></li>
    <li class="separator"><i class="icon-arrow-right"></i></li>
    <li class="nav-item"><a href="#">Import CSV</a></li>
  </ul>
</div>

<div class="row">
  <div class="col-lg-8 col-md-10">
    <div class="card">
      <div class="card-body">

        <div class="d-flex justify-content-between align-items-center pb-3 mb-4" style="border-bottom:2px solid #f0f0f0;">
          <div>
            <h4 class="mb-0 fw-bold text-primary">Import Filters From CSV</h4>
            <small class="text-muted">Upload a CSV file to import filters.</small>
          </div>
          <a href="{{ route('filters.index') }}" class="btn btn-outline-primary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Back to Filters
          </a>
        </div>

        @if ($errors->any())
          <div class="alert alert-danger mb-3">
            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
          </div>
        @endif

        <form action="{{ route('filters.process-import') }}" method="POST" enctype="multipart/form-data">
          @csrf

          <div class="form-group mb-4">
            <label class="fw-bold">File upload</label>
            <div class="d-flex align-items-center gap-2 mt-1">
              <label for="csv-input" class="btn btn-light border fw-bold mb-0"
                style="cursor:pointer; padding:.45rem 1.1rem; white-space:nowrap;">
                Upload CSV
              </label>
              <input type="text" id="csv-filename-display" class="form-control" placeholder="" readonly
                style="cursor:pointer;" onclick="$('#csv-input').trigger('click');">
              <label for="csv-input"
                style="background:#7c3aed; color:#fff; border:none; border-radius:6px; padding:.45rem 1.2rem;
                  font-weight:700; cursor:pointer; white-space:nowrap; margin:0;">
                Upload
              </label>
              <input type="file" name="csv_file" id="csv-input" accept=".csv,text/csv" style="display:none;">
            </div>
            <small class="d-block mt-2" style="font-size:.8rem; color:#555;">
              Expected columns (any order, case-insensitive):
              <code>category</code>, <code>filter_name</code>, <code>type</code> (pro/free),
              <code>saturation</code>, <code>brightness</code>, <code>contrast</code>,
              <code>red</code>, <code>green</code>, <code>blue</code>.
              Missing categories will be auto-created.
            </small>
            @error('csv_file')<div class="text-danger d-block mt-1" style="font-size:.875rem;">{{ $message }}</div>@enderror
          </div>

          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary" style="font-size:1rem; padding:.65rem 1.5rem;">
              <i class="fas fa-file-import me-1"></i> Import
            </button>
            <a href="{{ route('filters.index') }}" class="btn btn-light border"
              style="font-size:1rem; padding:.65rem 1.5rem;">Cancel</a>
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
  $('#csv-input').on('change', function () {
    if (this.files && this.files[0]) {
      $('#csv-filename-display').val(this.files[0].name);
    }
  });
});
</script>
@endpush
