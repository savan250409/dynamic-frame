@extends('layouts.app')

@section('title', 'Sticker Management')

@section('content')

<div class="page-header">
  <h4 class="page-title">Sticker Management</h4>
  <ul class="breadcrumbs">
    <li class="nav-home"><a href="{{ route('dashboard') }}"><i class="icon-home"></i></a></li>
    <li class="separator"><i class="icon-arrow-right"></i></li>
    <li class="nav-item"><a href="#">Sticker</a></li>
    <li class="separator"><i class="icon-arrow-right"></i></li>
    <li class="nav-item"><a href="#">Stickers</a></li>
  </ul>
</div>

<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-body">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center pb-3 mb-4" style="border-bottom:2px solid #f0f0f0;">
          <div class="d-flex align-items-center">
            <i class="fas fa-images text-primary" style="font-size:2rem; margin-right:12px;"></i>
            <div>
              <h4 class="mb-0 fw-bold text-primary">Sticker Management</h4>
              <small class="text-muted">Manage stickers</small>
            </div>
          </div>
          <div class="d-flex align-items-center gap-2">
            <span class="badge bg-light text-dark border px-3 py-2" style="font-size:.85rem;">
              <i class="fas fa-images me-1"></i> Total: {{ $totalStickers }}Stickers
            </span>
            <a href="{{ route('stickers.create') }}" class="btn btn-primary btn-sm ms-1">
              <i class="fas fa-plus me-1"></i> Add Sticker
            </a>
          </div>
        </div>

        @if(session('success'))
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>
        @endif

        {{-- Controls --}}
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
          <label class="d-flex align-items-center mb-0 gap-2">
            Show
            <select id="per_page" class="form-select form-select-sm" style="width:70px;">
              <option value="10" {{ (isset($perPage)?$perPage:10)==10?'selected':'' }}>10</option>
              <option value="25" {{ (isset($perPage)?$perPage:10)==25?'selected':'' }}>25</option>
              <option value="50" {{ (isset($perPage)?$perPage:10)==50?'selected':'' }}>50</option>
            </select>
            entries
          </label>
          <div class="d-flex align-items-center gap-2">
            <select id="category-filter" class="form-select form-select-sm" style="width:170px;">
              <option value="">All Categories</option>
              @foreach($categories as $cat)
                <option value="{{ $cat->id }}"
                  {{ (isset($categoryId) && $categoryId == $cat->id) ? 'selected' : '' }}>
                  {{ $cat->category_name }}
                </option>
              @endforeach
            </select>
            <input type="search" id="search-input" class="form-control form-control-sm"
              placeholder="Search categories..."
              value="{{ isset($search)?$search:'' }}" style="width:220px;">
          </div>
        </div>

        <div id="table-data">
          <div class="table-responsive">
            <table class="table table-striped table-hover">
              <thead class="table-light">
                <tr>
                  <th>Category Name</th>
                  <th>Sticker Images</th>
                  <th class="text-end">Action</th>
                </tr>
              </thead>
              <tbody>
                @forelse($stickers as $category)
                  @php
                    $stickerList = is_array($category->stickers) ? $category->stickers : [];
                    $preview     = array_slice($stickerList, 0, 3);
                    $remaining   = count($stickerList) - count($preview);
                  @endphp
                  <tr>
                    <td><strong>{{ $category->category_name }}</strong></td>
                    <td>
                      <div class="d-flex align-items-center gap-2 flex-wrap">
                        @forelse($preview as $sticker)
                          <img src="{{ asset('upload/sticker/'.rawurlencode($category->category_name).'/stickers/'.rawurlencode($sticker)) }}"
                            alt="sticker"
                            style="width:50px;height:50px;object-fit:cover;border-radius:6px;border:1px solid #ddd;">
                        @empty
                          <span class="text-muted" style="font-size:.85rem;">No stickers yet</span>
                        @endforelse
                        @if($remaining > 0)
                          <button type="button"
                            class="btn btn-primary btn-sm view-more-btn"
                            data-id="{{ $category->id }}"
                            data-name="{{ $category->category_name }}"
                            style="font-size:.75rem; padding:.25rem .6rem; border-radius:20px;">
                            View More +{{ $remaining }}
                          </button>
                        @endif
                      </div>
                    </td>
                    <td class="text-end">
                      <a href="{{ route('stickers.edit', $category->id) }}"
                        class="btn btn-info btn-sm" style="border-radius:6px;">
                        <i class="fas fa-pencil-alt"></i>
                      </a>
                      <button class="btn btn-danger btn-sm delete-btn ms-1"
                        data-url="{{ route('stickers.destroy', $category->id) }}"
                        data-name="{{ $category->category_name }}"
                        style="border-radius:6px;">
                        <i class="fas fa-trash"></i>
                      </button>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="3" class="text-center text-muted py-4">No stickers found.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>

          <div class="row mt-3 align-items-center">
            <div class="col-sm-6 text-muted" style="font-size:.85rem;">
              Showing {{ $stickers->firstItem() ?? 0 }} to {{ $stickers->lastItem() ?? 0 }}
              of {{ $stickers->total() }} entries
            </div>
            <div class="col-sm-6 d-flex justify-content-end">
              {!! $stickers->appends(request()->query())->links('pagination::bootstrap-4') !!}
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>

{{-- View More Modal --}}
<div class="modal fade" id="viewMoreModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fas fa-images me-1"></i> <span id="modal-cat-name">Stickers</span></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div id="modal-loader" class="text-center py-4" style="display:none;">
          <i class="fas fa-spinner fa-spin me-1"></i> Loading...
        </div>
        <div id="modal-stickers" class="d-flex flex-wrap" style="gap:10px;"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function () {

  /* Delete stickers for a category */
  $(document).on('click', '.delete-btn', function () {
    var url     = $(this).data('url');
    var catName = $(this).data('name');
    var $row    = $(this).closest('tr');

    Swal.fire({
      title: 'Clear All Stickers?',
      html:  'This will remove all sticker images from <strong>"' + $('<div>').text(catName).html() + '"</strong>.',
      icon:  'warning',
      showCancelButton:   true,
      confirmButtonColor: '#d33',
      cancelButtonColor:  '#6c757d',
      confirmButtonText:  '<i class="fas fa-trash me-1"></i> Yes, clear all',
      cancelButtonText:   'Cancel',
    }).then(function (result) {
      if (!result.isConfirmed) return;
      $.ajax({
        url:  url,
        type: 'DELETE',
        data: { _token: '{{ csrf_token() }}' },
        success: function (res) {
          if (res.success) {
            Swal.fire({ title: 'Cleared!', text: 'All stickers removed from "' + catName + '".', icon: 'success', timer: 2000, showConfirmButton: false });
            $row.find('td:nth-child(2)').html('<span class="text-muted" style="font-size:.85rem;">No stickers yet</span>');
          }
        },
        error: function () { Swal.fire('Error!', 'Failed to delete. Please try again.', 'error'); }
      });
    });
  });

  /* AJAX table */
  function fetchData(page) {
    $.ajax({
      url: "{{ route('stickers.index') }}",
      data: { page: page, search: $('#search-input').val(), per_page: $('#per_page').val(), category_id: $('#category-filter').val() },
      success: function (data) { $('#table-data').html($(data).find('#table-data').html()); }
    });
  }
  $(document).on('click', '.pagination a', function (e) {
    e.preventDefault();
    var url = new URL($(this).attr('href'), window.location.origin);
    fetchData(url.searchParams.get('page'));
  });
  $(document).on('keyup', '#search-input', function () { fetchData(1); });
  $(document).on('change', '#per_page, #category-filter', function () { fetchData(1); });

  /* View More modal */
  $(document).on('click', '.view-more-btn', function () {
    var catId   = $(this).data('id');
    var catName = $(this).data('name');
    $('#modal-cat-name').text(catName);
    $('#modal-stickers').empty();
    $('#modal-loader').show();
    var modal = new bootstrap.Modal(document.getElementById('viewMoreModal'));
    modal.show();

    var allStickers = [];
    $(this).closest('tr').find('img').each(function () {
      allStickers.push($(this).attr('src'));
    });

    $.ajax({
      url: "{{ route('stickers.index') }}",
      data: { category_id: catId, per_page: 100 },
      success: function (data) {
        $('#modal-loader').hide();
        var $rows = $(data).find('img[style*="50px"]');
        var $grid = $('#modal-stickers').empty();
        if ($rows.length) {
          $rows.each(function () {
            var src = $(this).attr('src');
            $grid.append(
              '<img src="' + src + '" style="width:80px;height:80px;object-fit:cover;border-radius:6px;border:1px solid #ddd;">'
            );
          });
        } else {
          $grid.html('<p class="text-muted w-100 text-center">No stickers found.</p>');
        }
      },
      error: function () {
        $('#modal-loader').hide();
        $('#modal-stickers').html('<p class="text-danger w-100 text-center">Failed to load.</p>');
      }
    });
  });
});
</script>
@endpush
