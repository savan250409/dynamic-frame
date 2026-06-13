@extends('layouts.app')

@section('title', 'Sticker Category')

@section('content')

<div class="page-header">
  <h4 class="page-title">Sticker Category</h4>
  <ul class="breadcrumbs">
    <li class="nav-home"><a href="{{ route('dashboard') }}"><i class="icon-home"></i></a></li>
    <li class="separator"><i class="icon-arrow-right"></i></li>
    <li class="nav-item"><a href="#">Sticker</a></li>
    <li class="separator"><i class="icon-arrow-right"></i></li>
    <li class="nav-item"><a href="#">Category</a></li>
  </ul>
</div>

<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-body">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center pb-3 mb-4" style="border-bottom:2px solid #f0f0f0;">
          <div class="d-flex align-items-center">
            <i class="fas fa-sticky-note text-primary" style="font-size:2rem; margin-right:12px;"></i>
            <div>
              <h4 class="mb-0 fw-bold text-primary">Sticker Category Management</h4>
              <small class="text-muted">Create, organize and curate the sticker categories shown in your app</small>
            </div>
          </div>
          <div class="d-flex align-items-center gap-2">
            <span class="badge bg-light text-dark border px-3 py-2" style="font-size:.85rem;">
              <i class="fas fa-layer-group me-1"></i> Total: {{ $categories->total() }} Categories
            </span>
            <button type="button" id="open-index-modal" class="btn btn-warning btn-sm ms-1">
              <i class="fas fa-sort me-1"></i> Reorder
            </button>
            <a href="{{ route('sticker-categories.create') }}" class="btn btn-primary btn-sm ms-1">
              <i class="fas fa-plus me-1"></i> Add Category
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
        <div class="d-flex justify-content-between align-items-center mb-3">
          <label class="d-flex align-items-center mb-0 gap-2">
            Show
            <select id="per_page" class="form-select form-select-sm" style="width:70px;">
              <option value="10" {{ (isset($perPage)?$perPage:10)==10?'selected':'' }}>10</option>
              <option value="25" {{ (isset($perPage)?$perPage:10)==25?'selected':'' }}>25</option>
              <option value="50" {{ (isset($perPage)?$perPage:10)==50?'selected':'' }}>50</option>
              <option value="100" {{ (isset($perPage)?$perPage:10)==100?'selected':'' }}>100</option>
            </select>
            entries
          </label>
          <div class="d-flex align-items-center gap-2">
            <input type="search" id="search-input" class="form-control form-control-sm"
              placeholder="Search categories..." value="{{ isset($search)?$search:'' }}" style="width:220px;">
            <button type="button" id="clear-search" class="btn btn-sm btn-light border">&times;</button>
          </div>
        </div>

        <div id="table-data">
          <div class="table-responsive">
            <table class="table table-striped table-hover">
              <thead class="table-light">
                <tr>
                  <th>Category Name</th>
                  <th>Thumbnail</th>
                  <th>Premium</th>
                  <th>Status</th>
                  <th class="text-end">Action</th>
                </tr>
              </thead>
              <tbody>
                @forelse($categories as $category)
                  <tr>
                    <td><strong>{{ $category->category_name }}</strong></td>
                    <td>
                      @if($category->image)
                        <img src="{{ asset('upload/sticker/'.rawurlencode($category->category_name).'/category image/'.rawurlencode($category->image)) }}"
                          alt="thumb" style="width:60px;height:60px;object-fit:cover;border-radius:6px;border:1px solid #ddd;">
                      @else
                        <span class="text-muted">—</span>
                      @endif
                    </td>
                    <td>
                      @if($category->is_premium)
                        <span class="badge bg-primary" style="font-size:.75rem;">
                          <i class="fas fa-check me-1"></i> Pro
                        </span>
                      @else
                        <span class="badge bg-secondary" style="font-size:.75rem;">Free</span>
                      @endif
                    </td>
                    <td>
                      <div class="d-flex align-items-center gap-2">
                        <div class="form-check form-switch mb-0">
                          <input class="form-check-input status-toggle" type="checkbox"
                            data-id="{{ $category->id }}"
                            {{ $category->status ? 'checked' : '' }}
                            style="cursor:pointer;">
                        </div>
                        <span class="badge {{ $category->status ? 'bg-success' : 'bg-secondary' }}"
                          style="font-size:.75rem;">
                          {{ $category->status ? 'Active' : 'Inactive' }}
                        </span>
                      </div>
                    </td>
                    <td class="text-end">
                      <a href="{{ route('sticker-categories.edit', $category->id) }}"
                        class="btn btn-info btn-sm" style="border-radius:6px;">
                        <i class="fas fa-pencil-alt"></i>
                      </a>
                      <button class="btn btn-danger btn-sm delete-btn ms-1"
                        data-url="{{ route('sticker-categories.destroy', $category->id) }}"
                        data-name="{{ $category->category_name }}"
                        style="border-radius:6px;">
                        <i class="fas fa-trash"></i>
                      </button>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="5" class="text-center text-muted py-4">No categories found.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>

          <div class="row mt-3 align-items-center">
            <div class="col-sm-6 text-muted" style="font-size:.85rem;">
              Showing {{ $categories->firstItem() ?? 0 }} to {{ $categories->lastItem() ?? 0 }}
              of {{ $categories->total() }} entries
            </div>
            <div class="col-sm-6 d-flex justify-content-end">
              {!! $categories->appends(request()->query())->links('pagination::bootstrap-4') !!}
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>

{{-- Indexing Modal --}}
<div class="modal fade" id="indexModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fas fa-sort me-1"></i> Category Reorder</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="alert alert-info py-2 mb-3" style="font-size:.85rem;">
          <i class="fas fa-info-circle me-1"></i>
          Drag and drop to reorder. Order saves automatically.
        </div>
        <div id="index-loader" class="text-center py-4" style="display:none;">
          <i class="fas fa-spinner fa-spin me-1"></i> Loading...
        </div>
        <ul id="cat-sortable" class="list-group" style="min-height:50px;"></ul>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" id="save-order-btn" class="btn btn-primary">
          <i class="fas fa-save me-1"></i> Save Order
        </button>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<style>
  .drag-handle { cursor: grab; }
  .drag-handle:active { cursor: grabbing; }
  .ui-sortable-helper { box-shadow: 0 4px 12px rgba(0,0,0,.12) !important; background:#fff !important; opacity:.95; }
  .ui-sortable-placeholder { visibility:visible !important; background:#f8f9fa !important; border:1px dashed #ccc !important; height:50px; border-radius:4px; margin-bottom:.4rem; }
</style>
<script>
$(document).ready(function () {

  /* Status toggle */
  $(document).on('change', '.status-toggle', function () {
    var id     = $(this).data('id');
    var status = $(this).is(':checked') ? 1 : 0;
    var badge  = $(this).closest('td').find('.badge');
    $.ajax({
      url: "{{ route('sticker-categories.update-status') }}",
      type: 'POST',
      data: { _token: '{{ csrf_token() }}', id: id, status: status },
      success: function (res) {
        if (res.success) {
          badge.removeClass('bg-success bg-secondary')
               .addClass(status ? 'bg-success' : 'bg-secondary')
               .text(status ? 'Active' : 'Inactive');
          Swal.mixin({ toast:true, position:'top-end', showConfirmButton:false, timer:2000 })
              .fire({ icon:'success', title:'Status updated' });
        }
      }
    });
  });

  /* Delete — 2-step confirmation */
  $(document).on('click', '.delete-btn', function () {
    var url     = $(this).data('url');
    var catName = $(this).data('name');
    var $row    = $(this).closest('tr');

    Swal.fire({
      title: 'Delete Category?',
      html:  'You are about to delete <strong>"' + $('<div>').text(catName).html() + '"</strong>.',
      icon:  'warning',
      showCancelButton:    true,
      confirmButtonColor:  '#e8a020',
      cancelButtonColor:   '#6c757d',
      confirmButtonText:   'Yes, continue &rarr;',
      cancelButtonText:    'Cancel',
    }).then(function (step1) {
      if (!step1.isConfirmed) return;

      Swal.fire({
        title: 'Final Confirmation',
        html: '<div style="text-align:left;">' +
              '<p class="text-danger fw-bold mb-2">&#9888; This will permanently delete:</p>' +
              '<ul class="mb-3">' +
              '<li>Category thumbnail image</li>' +
              '<li>All sticker images in this category</li>' +
              '</ul>' +
              '<p class="mb-0 text-muted" style="font-size:.88rem;">This action <strong>cannot be undone</strong>.</p>' +
              '</div>',
        icon:  'error',
        showCancelButton:    true,
        confirmButtonColor:  '#d33',
        cancelButtonColor:   '#6c757d',
        confirmButtonText:   '<i class="fas fa-trash me-1"></i> Delete Everything',
        cancelButtonText:    'Cancel',
      }).then(function (step2) {
        if (!step2.isConfirmed) return;

        $.ajax({
          url:  url,
          type: 'DELETE',
          data: { _token: '{{ csrf_token() }}' },
          success: function (res) {
            if (res.success) {
              Swal.fire({
                title: 'Deleted!',
                text:  'Category "' + catName + '" and all its stickers have been deleted.',
                icon:  'success',
                timer: 2500,
                showConfirmButton: false,
              });
              $row.remove();
            }
          },
          error: function () {
            Swal.fire('Error!', 'Failed to delete. Please try again.', 'error');
          }
        });
      });
    });
  });

  /* AJAX table */
  function fetchData(page) {
    $.ajax({
      url: "{{ route('sticker-categories.index') }}",
      data: { page: page, search: $('#search-input').val(), per_page: $('#per_page').val() },
      success: function (data) { $('#table-data').html($(data).find('#table-data').html()); }
    });
  }
  $(document).on('click', '.pagination a', function (e) {
    e.preventDefault();
    var url = new URL($(this).attr('href'), window.location.origin);
    fetchData(url.searchParams.get('page'));
  });
  $(document).on('keyup', '#search-input', function () { fetchData(1); });
  $(document).on('change', '#per_page', function () { fetchData(1); });
  $('#clear-search').on('click', function () { $('#search-input').val(''); fetchData(1); });

  /* Indexing modal */
  var toast = Swal.mixin({ toast:true, position:'top-end', showConfirmButton:false, timer:2500 });

  function renderList(categories) {
    var $list = $('#cat-sortable').empty();
    if (!categories.length) { $list.append('<li class="list-group-item text-center text-muted">No categories.</li>'); return; }
    $.each(categories, function (i, cat) {
      var $li = $('<li>', {
        'class': 'list-group-item d-flex align-items-center justify-content-between mb-1',
        'data-id': cat.id,
        'style': 'background:#fff; border:1px solid #eee; border-radius:4px; padding:.6rem 1rem;'
      });
      var $left = $('<div class="d-flex align-items-center gap-2 text-dark">');
      $left.append('<div class="drag-handle text-muted"><i class="fas fa-grip-vertical"></i></div>');
      $left.append('<span class="fw-bold" style="min-width:24px;">' + (i+1) + '.</span>');
      $left.append('<span>' + $('<div>').text(cat.category_name).html() + '</span>');
      var $badge = $('<span class="badge bg-secondary" style="border-radius:10px;">ID: ' + cat.id + '</span>');
      $li.append($left).append($badge);
      $list.append($li);
    });
  }

  function saveOrder() {
    var order = [];
    $('#cat-sortable li[data-id]').each(function (i) {
      order.push({ id: $(this).data('id'), sort_order: i + 1 });
    });
    $.ajax({
      url: "{{ route('sticker-categories.update-order') }}", type: 'POST',
      data: { order: order, _token: '{{ csrf_token() }}' },
      success: function (res) { if (res.success) toast.fire({ icon:'success', title:'Order saved' }); },
      error: function () { toast.fire({ icon:'error', title:'Failed to save order' }); }
    });
  }

  function initSortable() {
    $('#cat-sortable').sortable({
      handle: '.drag-handle',
      placeholder: 'ui-sortable-placeholder',
      axis: 'y',
      update: function () {
        $('#cat-sortable li[data-id]').each(function (i) {
          $(this).find('span.fw-bold').text((i+1) + '.');
        });
        saveOrder();
      }
    }).disableSelection();
  }

  $('#open-index-modal').on('click', function () {
    var modal = new bootstrap.Modal(document.getElementById('indexModal'));
    modal.show();
    $('#cat-sortable').empty();
    $('#index-loader').show();
    $.ajax({
      url: "{{ route('sticker-categories.order-list') }}",
      success: function (res) {
        $('#index-loader').hide();
        if (res.success) { renderList(res.categories); initSortable(); }
      },
      error: function () {
        $('#index-loader').hide();
        $('#cat-sortable').html('<li class="list-group-item text-center text-danger">Failed to load.</li>');
      }
    });
  });

  $('#save-order-btn').on('click', function () { saveOrder(); });
});
</script>
@endpush
