@extends('layouts.app')

@section('title', 'Doodle Management')

@section('content')

<div class="page-header">
  <h4 class="page-title">Doodle Management</h4>
  <ul class="breadcrumbs">
    <li class="nav-home"><a href="{{ route('dashboard') }}"><i class="icon-home"></i></a></li>
    <li class="separator"><i class="icon-arrow-right"></i></li>
    <li class="nav-item"><a href="#">Doodle Management</a></li>
  </ul>
</div>

<div class="row">
  <div class="col-lg-12">
    <div class="card" style="border-radius:14px; overflow:hidden;">

      {{-- Gradient Header --}}
      <div style="background: linear-gradient(135deg, #6c3fc5 0%, #4f8ef7 100%); padding: 1.2rem 1.5rem;">
        <div class="d-flex justify-content-between align-items-center">
          <div class="d-flex align-items-center gap-3">
            <div style="background:rgba(255,255,255,.18); border-radius:10px; padding:.5rem .7rem;">
              <i class="fas fa-pen-nib" style="color:#fff; font-size:1.3rem;"></i>
            </div>
            <div>
              <h5 class="mb-0 fw-bold" style="color:#fff;">Doodle Management</h5>
              <small style="color:rgba(255,255,255,.75); font-size:.8rem;">Manage doodles available in your app</small>
            </div>
          </div>
          <div class="d-flex align-items-center gap-2">
            <span class="badge" style="background:#e8a020; color:#fff; font-size:.8rem; padding:.45rem .9rem; border-radius:20px; font-weight:600;">
              <i class="fas fa-layer-group me-1"></i> Total {{ $doodles->total() }}
            </span>
            <button type="button" id="open-index-modal"
              style="background:#e8a020; color:#fff; border:none; border-radius:20px; padding:.4rem 1rem; font-size:.85rem; font-weight:600; cursor:pointer;">
              <i class="fas fa-sort me-1"></i> Reorder
            </button>
            <a href="{{ route('doodles.create') }}"
              style="background:#fff; color:#5b4fcf; border:none; border-radius:20px; padding:.4rem 1rem; font-size:.85rem; font-weight:700; text-decoration:none;">
              <i class="fas fa-plus me-1"></i> Add Doodle
            </a>
          </div>
        </div>
      </div>

      <div class="card-body">

        @if(session('success'))
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>
        @endif

        {{-- Controls --}}
        <div class="d-flex justify-content-between align-items-center mb-3 mt-2">
          <label class="d-flex align-items-center mb-0 gap-2">
            Show
            <select id="per_page" class="form-select form-select-sm" style="width:70px;">
              <option value="10"  {{ (isset($perPage)?$perPage:10)==10  ? 'selected':'' }}>10</option>
              <option value="25"  {{ (isset($perPage)?$perPage:10)==25  ? 'selected':'' }}>25</option>
              <option value="50"  {{ (isset($perPage)?$perPage:10)==50  ? 'selected':'' }}>50</option>
              <option value="100" {{ (isset($perPage)?$perPage:10)==100 ? 'selected':'' }}>100</option>
            </select>
            entries
          </label>
          <div class="d-flex align-items-center gap-2">
            <input type="search" id="search-input" class="form-control form-control-sm"
              placeholder="Search doodles..." value="{{ isset($search)?$search:'' }}" style="width:220px;">
            <button type="button" id="clear-search" class="btn btn-sm btn-light border">&times;</button>
          </div>
        </div>

        <div id="table-data">
          <div class="table-responsive">
            <table class="table table-hover align-middle" style="border-collapse:separate; border-spacing:0;">
              <thead>
                <tr style="background:#f8f7ff;">
                  <th style="border-bottom:2px solid #e8e4ff; padding:.75rem 1rem; font-weight:700; color:#333;">Name</th>
                  <th style="border-bottom:2px solid #e8e4ff; padding:.75rem 1rem; font-weight:700; color:#333;">Doodle Image</th>
                  <th style="border-bottom:2px solid #e8e4ff; padding:.75rem 1rem; font-weight:700; color:#333;">Doodle Type</th>
                  <th style="border-bottom:2px solid #e8e4ff; padding:.75rem 1rem; font-weight:700; color:#333;">Type</th>
                  <th style="border-bottom:2px solid #e8e4ff; padding:.75rem 1rem; font-weight:700; color:#333; text-align:right;">Action</th>
                </tr>
              </thead>
              <tbody>
                @forelse($doodles as $doodle)
                  <tr style="border-bottom:1px solid #f0f0f0;">
                    <td style="padding:.7rem 1rem; font-weight:600; color:#222;">{{ $doodle->name }}</td>
                    <td style="padding:.7rem 1rem;">
                      @if($doodle->image)
                        <img src="{{ asset('upload/doodle/'.rawurlencode($doodle->name).'/'.rawurlencode($doodle->image)) }}"
                          alt="{{ $doodle->name }}"
                          style="width:44px; height:44px; object-fit:contain; border-radius:8px; border:1px solid #eee; background:#fafafa; padding:3px;">
                      @else
                        <span class="text-muted">—</span>
                      @endif
                    </td>
                    <td style="padding:.7rem 1rem;">
                      <span style="background:#ede9ff; color:#6c3fc5; font-size:.72rem; font-weight:700;
                        padding:3px 12px; border-radius:6px; letter-spacing:.5px; text-transform:uppercase;">
                        {{ $doodle->doodle_type }}
                      </span>
                    </td>
                    <td style="padding:.7rem 1rem;">
                      @if($doodle->is_premium)
                        <span class="badge bg-primary" style="font-size:.75rem; border-radius:10px;">
                          <i class="fas fa-check me-1"></i> Pro
                        </span>
                      @else
                        <span class="badge bg-secondary" style="font-size:.75rem; border-radius:10px;">Free</span>
                      @endif
                    </td>
                    <td style="padding:.7rem 1rem; text-align:right;">
                      <a href="{{ route('doodles.edit', $doodle->id) }}"
                        class="btn btn-info btn-sm" style="border-radius:6px;">
                        <i class="fas fa-pencil-alt"></i>
                      </a>
                      <button class="btn btn-danger btn-sm delete-btn ms-1"
                        data-url="{{ route('doodles.destroy', $doodle->id) }}"
                        data-name="{{ $doodle->name }}"
                        style="border-radius:6px;">
                        <i class="fas fa-trash"></i>
                      </button>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="5" class="text-center text-muted py-4">No doodles found.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>

          <div class="row mt-3 align-items-center">
            <div class="col-sm-6 text-muted" style="font-size:.85rem;">
              Showing {{ $doodles->firstItem() ?? 0 }} to {{ $doodles->lastItem() ?? 0 }}
              of {{ $doodles->total() }} entries
            </div>
            <div class="col-sm-6 d-flex justify-content-end">
              {!! $doodles->appends(request()->query())->links('pagination::bootstrap-4') !!}
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>

{{-- Reorder Modal --}}
<div class="modal fade" id="indexModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fas fa-sort me-1"></i> Doodle Reorder</h5>
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
        <ul id="doodle-sortable" class="list-group" style="min-height:50px;"></ul>
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

  /* Delete */
  $(document).on('click', '.delete-btn', function () {
    var url        = $(this).data('url');
    var doodleName = $(this).data('name');
    var $row       = $(this).closest('tr');

    Swal.fire({
      title: 'Delete Doodle?',
      html:  'You are about to delete <strong>"' + $('<div>').text(doodleName).html() + '"</strong>.',
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
              '<li>Doodle image file</li>' +
              '<li>Doodle folder from storage</li>' +
              '</ul>' +
              '<p class="mb-0 text-muted" style="font-size:.88rem;">This action <strong>cannot be undone</strong>.</p>' +
              '</div>',
        icon:  'error',
        showCancelButton:    true,
        confirmButtonColor:  '#d33',
        cancelButtonColor:   '#6c757d',
        confirmButtonText:   '<i class="fas fa-trash me-1"></i> Delete Doodle',
        cancelButtonText:    'Cancel',
      }).then(function (step2) {
        if (!step2.isConfirmed) return;

        $.ajax({
          url:  url,
          type: 'DELETE',
          data: { _token: '{{ csrf_token() }}' },
          success: function (res) {
            if (res.success) {
              Swal.fire({ title: 'Deleted!', text: '"' + doodleName + '" has been deleted.', icon: 'success', timer: 2000, showConfirmButton: false });
              $row.remove();
            }
          },
          error: function () { Swal.fire('Error!', 'Failed to delete. Please try again.', 'error'); }
        });
      });
    });
  });

  /* AJAX table */
  function fetchData(page) {
    $.ajax({
      url: "{{ route('doodles.index') }}",
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

  /* Reorder modal */
  var toast = Swal.mixin({ toast:true, position:'top-end', showConfirmButton:false, timer:2500 });

  function renderList(doodles) {
    var $list = $('#doodle-sortable').empty();
    if (!doodles.length) { $list.append('<li class="list-group-item text-center text-muted">No doodles.</li>'); return; }
    $.each(doodles, function (i, doodle) {
      var $li = $('<li>', {
        'class': 'list-group-item d-flex align-items-center justify-content-between mb-1',
        'data-id': doodle.id,
        'style': 'background:#fff; border:1px solid #eee; border-radius:4px; padding:.6rem 1rem;'
      });
      var $left = $('<div class="d-flex align-items-center gap-2 text-dark">');
      $left.append('<div class="drag-handle text-muted"><i class="fas fa-grip-vertical"></i></div>');
      $left.append('<span class="fw-bold" style="min-width:24px;">' + (i+1) + '.</span>');
      $left.append('<span>' + $('<div>').text(doodle.name).html() + '</span>');
      var $badge = $('<span class="badge bg-secondary" style="border-radius:10px;">ID: ' + doodle.id + '</span>');
      $li.append($left).append($badge);
      $list.append($li);
    });
  }

  function saveOrder() {
    var order = [];
    $('#doodle-sortable li[data-id]').each(function (i) {
      order.push({ id: $(this).data('id'), sort_order: i + 1 });
    });
    $.ajax({
      url: "{{ route('doodles.update-order') }}", type: 'POST',
      data: { order: order, _token: '{{ csrf_token() }}' },
      success: function (res) { if (res.success) toast.fire({ icon:'success', title:'Order saved' }); },
      error: function () { toast.fire({ icon:'error', title:'Failed to save order' }); }
    });
  }

  function initSortable() {
    $('#doodle-sortable').sortable({
      handle: '.drag-handle',
      placeholder: 'ui-sortable-placeholder',
      axis: 'y',
      update: function () {
        $('#doodle-sortable li[data-id]').each(function (i) {
          $(this).find('span.fw-bold').text((i+1) + '.');
        });
        saveOrder();
      }
    }).disableSelection();
  }

  $('#open-index-modal').on('click', function () {
    var modal = new bootstrap.Modal(document.getElementById('indexModal'));
    modal.show();
    $('#doodle-sortable').empty();
    $('#index-loader').show();
    $.ajax({
      url: "{{ route('doodles.order-list') }}",
      success: function (res) {
        $('#index-loader').hide();
        if (res.success) { renderList(res.doodles); initSortable(); }
      },
      error: function () {
        $('#index-loader').hide();
        $('#doodle-sortable').html('<li class="list-group-item text-center text-danger">Failed to load.</li>');
      }
    });
  });

  $('#save-order-btn').on('click', function () { saveOrder(); });
});
</script>
@endpush
