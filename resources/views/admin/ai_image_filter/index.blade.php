@extends('layouts.app')

@section('title', 'Dynamic Frame Management')

@section('content')

<div class="page-header">
  <h4 class="page-title">Dynamic Frame Management</h4>
  <ul class="breadcrumbs">
    <li class="nav-home"><a href="{{ route('dashboard') }}"><i class="icon-home"></i></a></li>
    <li class="separator"><i class="icon-arrow-right"></i></li>
    <li class="nav-item"><a href="#">Dynamic Frame</a></li>
  </ul>
</div>

<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-body">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center pb-3 mb-4" style="border-bottom:2px solid #f0f0f0;">
          <div class="d-flex align-items-center">
            <i class="fas fa-magic text-primary" style="font-size:2rem; margin-right:12px;"></i>
            <div>
              <h4 class="mb-0 fw-bold text-primary">Dynamic Frame Management</h4>
              <small class="text-muted">Manage AI image filters</small>
            </div>
          </div>
          <div class="d-flex align-items-center gap-2">
            <button type="button" id="open-index-modal" class="btn btn-warning btn-sm">
              <i class="fas fa-sort me-1"></i> Indexing
            </button>
            <span class="badge bg-light text-dark border px-3 py-2" style="font-size:.85rem;">
              <i class="fas fa-images me-1"></i> Total: {{ $filters->total() }} Filters
            </span>
            <a href="{{ route('ai-image-filters.create') }}" class="btn btn-primary btn-sm">
              <i class="fas fa-plus me-1"></i> Add Filter
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
            <select id="category-filter" class="form-select form-select-sm" style="width:200px;">
              <option value="">All Categories</option>
              @foreach($categories as $cat)
                <option value="{{ $cat->id }}"
                  {{ (isset($categoryId) && $categoryId == $cat->id) ? 'selected' : '' }}>
                  {{ $cat->category_name }}
                </option>
              @endforeach
            </select>
          </div>
        </div>

        <div id="table-data">
          <div class="table-responsive">
            <table class="table table-striped table-hover">
              <thead class="table-light">
                <tr>
                  <th>Category</th>
                  <th>Input Count</th>
                  <th>Zip File</th>
                  <th>Thumbnail</th>
                  <th class="text-end">Action</th>
                </tr>
              </thead>
              <tbody>
                @forelse($filters as $filter)
                  <tr>
                    <td>
                      <span class="badge bg-primary" style="border-radius:12px; padding:.3rem .65rem; font-size:.8rem;">
                        {{ $filter->category->category_name ?? '—' }}
                      </span>
                    </td>
                    <td>
                      <span class="badge bg-secondary" style="font-size:.85rem; padding:.35rem .75rem;">
                        {{ $filter->input_count ?? 1 }}
                      </span>
                    </td>
                    <td>
                      @if($filter->zip_file && $filter->category)
                        <a href="{{ asset('upload/ai_image_filter/'.rawurlencode($filter->category->category_name).'/zip/'.rawurlencode($filter->zip_file)) }}"
                          target="_blank" class="text-primary" style="font-size:.82rem; word-break:break-all;">
                          <i class="fas fa-file-archive me-1"></i>{{ $filter->zip_file }}
                        </a>
                      @else
                        <span class="text-muted">—</span>
                      @endif
                    </td>
                    <td>
                      @if($filter->image_path && $filter->category)
                        <img src="{{ asset('upload/ai_image_filter/'.rawurlencode($filter->category->category_name).'/images/'.rawurlencode($filter->image_path)) }}"
                          style="width:60px;height:60px;object-fit:cover;border-radius:4px;border:1px solid #ddd;">
                      @else
                        <span class="text-muted">—</span>
                      @endif
                    </td>
                    <td class="text-end">
                      <a href="{{ route('ai-image-filters.edit', $filter->id) }}"
                        class="btn btn-info btn-sm" style="border-radius:6px;">
                        <i class="fas fa-pencil-alt"></i>
                      </a>
                      <button class="btn btn-danger btn-sm delete-btn ms-1"
                        data-url="{{ route('ai-image-filters.destroy', $filter->id) }}"
                        style="border-radius:6px;">
                        <i class="fas fa-trash"></i>
                      </button>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="5" class="text-center text-muted py-4">No filters found.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>

          <div class="row mt-3 align-items-center">
            <div class="col-sm-6 text-muted" style="font-size:.85rem;">
              Showing {{ $filters->firstItem() ?? 0 }} to {{ $filters->lastItem() ?? 0 }}
              of {{ $filters->total() }} entries
            </div>
            <div class="col-sm-6 d-flex justify-content-end">
              {!! $filters->appends(request()->query())->links('pagination::bootstrap-4') !!}
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>

{{-- Indexing Modal --}}
<div class="modal fade" id="indexModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fas fa-sort me-1"></i> Filter Indexing</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="fw-bold">Filter by Category</label>
          <select id="index-category-select" class="form-control">
            <option value="">All Categories</option>
            @foreach($categories as $cat)
              <option value="{{ $cat->id }}">{{ $cat->category_name }}</option>
            @endforeach
          </select>
        </div>
        <div class="alert alert-info py-2 mb-3" style="font-size:.85rem;">
          <i class="fas fa-info-circle me-1"></i>
          Drag and drop to reorder. Order saves automatically.
        </div>
        <div id="img-index-loader" class="text-center py-4" style="display:none;">
          <i class="fas fa-spinner fa-spin me-1"></i> Loading...
        </div>
        <div id="img-sortable" class="d-flex flex-wrap" style="gap:12px; min-height:80px;"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" id="save-img-order-btn" class="btn btn-primary">
          <i class="fas fa-save me-1"></i> Save Order
        </button>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script src="{{ asset('assets/js/plugin/sweetalert2.all.min.js') }}"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<style>
  .img-sort-card {
    width: 130px; border: 1px solid #ddd; border-radius: 8px;
    overflow: hidden; background: #fff; cursor: grab; position: relative;
  }
  .img-sort-card:active { cursor: grabbing; }
  .img-sort-card img { width:100%; height:100px; object-fit:cover; display:block; }
  .img-sort-card .card-label { padding: 4px 6px; font-size: .72rem; background: #f8f9fa; border-top: 1px solid #eee; }
  .img-sort-card .card-id-badge {
    position:absolute; top:4px; right:4px;
    background:rgba(0,0,0,.55); color:#fff; font-size:.65rem;
    padding:2px 6px; border-radius:10px;
  }
  .ui-sortable-helper.img-sort-card { box-shadow: 0 6px 20px rgba(0,0,0,.15) !important; opacity:.9; }
  .ui-sortable-placeholder { width:130px; height:168px; border:2px dashed #ccc !important;
    border-radius:8px; background:#f8f9fa !important; display:inline-block !important; }
</style>
<script>
$(document).ready(function () {

  /* Delete */
  $(document).on('click', '.delete-btn', function () {
    var url = $(this).data('url');
    var row = $(this).closest('tr');
    Swal.fire({
      title: 'Are you sure?', text: "This cannot be undone!", icon: 'warning',
      showCancelButton: true, confirmButtonColor: '#d33', confirmButtonText: 'Yes, delete!'
    }).then(function (result) {
      if (result.isConfirmed) {
        $.ajax({
          url: url, type: 'DELETE',
          data: { _token: '{{ csrf_token() }}' },
          success: function (res) {
            if (res.success) {
              Swal.fire('Deleted!', 'Filter deleted.', 'success');
              row.remove();
            }
          }
        });
      }
    });
  });

  /* AJAX table */
  function fetchData(page) {
    $.ajax({
      url: "{{ route('ai-image-filters.index') }}",
      data: {
        page: page,
        per_page: $('#per_page').val(),
        category_id: $('#category-filter').val()
      },
      success: function (data) { $('#table-data').html($(data).find('#table-data').html()); }
    });
  }
  $(document).on('click', '.pagination a', function (e) {
    e.preventDefault();
    var url = new URL($(this).attr('href'), window.location.origin);
    fetchData(url.searchParams.get('page'));
  });
  $(document).on('change', '#per_page, #category-filter', function () { fetchData(1); });

  /* Indexing modal */
  var toast = Swal.mixin({ toast:true, position:'top-end', showConfirmButton:false, timer:2500 });

  function renderImageGrid(filters) {
    var $grid = $('#img-sortable').empty();
    if (!filters.length) {
      $grid.html('<p class="text-muted w-100 text-center py-3">No filters found for this category.</p>');
      return;
    }
    $.each(filters, function (i, img) {
      var $card = $('<div>', { 'class': 'img-sort-card', 'data-id': img.id });
      $card.append('<span class="card-id-badge">ID: ' + img.id + '</span>');
      if (img.image_url) {
        $card.append('<img src="' + img.image_url + '" alt="' + $('<div>').text(img.name).html() + '">');
      } else {
        $card.append('<div style="width:100%;height:100px;background:#f0f0f0;display:flex;align-items:center;justify-content:center;"><i class="fas fa-image" style="font-size:2rem;color:#ccc;"></i></div>');
      }
      var $label = $('<div class="card-label">');
      $label.append('<div class="text-muted text-truncate" style="font-size:.72rem;">Input Count: <strong>' + (img.input_count || 1) + '</strong></div>');
      $card.append($label);
      $grid.append($card);
    });
  }

  function initImageSortable() {
    $('#img-sortable').sortable({
      placeholder: 'ui-sortable-placeholder',
      update: function () { saveImageOrder(); }
    }).disableSelection();
  }

  function loadFilters() {
    var catId = $('#index-category-select').val();
    $('#img-index-loader').show();
    $('#img-sortable').empty();
    $.ajax({
      url: "{{ route('ai-image-filters.order-list') }}",
      data: { category_id: catId },
      success: function (res) {
        $('#img-index-loader').hide();
        if (res.success) { renderImageGrid(res.filters); initImageSortable(); }
      },
      error: function () {
        $('#img-index-loader').hide();
        $('#img-sortable').html('<p class="text-danger w-100 text-center">Failed to load.</p>');
      }
    });
  }

  function saveImageOrder() {
    var order = [];
    $('#img-sortable .img-sort-card[data-id]').each(function (i) {
      order.push({ id: $(this).data('id'), sort_order: i + 1 });
    });
    $.ajax({
      url: "{{ route('ai-image-filters.update-order') }}", type: 'POST',
      data: { order: order, _token: '{{ csrf_token() }}' },
      success: function (res) { if (res.success) toast.fire({ icon:'success', title:'Order updated' }); },
      error: function () { toast.fire({ icon:'error', title:'Failed to update order' }); }
    });
  }

  $('#open-index-modal').on('click', function () {
    var modal = new bootstrap.Modal(document.getElementById('indexModal'));
    modal.show();
    loadFilters();
  });
  $('#index-category-select').on('change', function () { loadFilters(); });
  $('#save-img-order-btn').on('click', function () { saveImageOrder(); });
});
</script>
@endpush
