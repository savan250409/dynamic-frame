@extends('layouts.app')

@section('title', 'Filter Category Management')

@section('content')

<div class="page-header">
  <h4 class="page-title">Filter Category Management</h4>
  <ul class="breadcrumbs">
    <li class="nav-home"><a href="{{ route('dashboard') }}"><i class="icon-home"></i></a></li>
    <li class="separator"><i class="icon-arrow-right"></i></li>
    <li class="nav-item"><a href="#">Filter Category Management</a></li>
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
              <i class="fas fa-filter" style="color:#fff; font-size:1.3rem;"></i>
            </div>
            <div>
              <h5 class="mb-0 fw-bold" style="color:#fff;">Filter Category Management</h5>
              <small style="color:rgba(255,255,255,.75); font-size:.8rem;">Manage filter categories</small>
            </div>
          </div>
          <div class="d-flex align-items-center gap-2">
            <span class="badge" style="background:rgba(255,255,255,.2); color:#fff; font-size:.8rem; padding:.45rem .9rem; border-radius:20px; font-weight:600; border:1px solid rgba(255,255,255,.3);">
              <i class="fas fa-layer-group me-1"></i> Total {{ $categories->total() }}
            </span>
            <a href="{{ route('filter-categories.create') }}"
              style="background:#fff; color:#5b4fcf; border:none; border-radius:20px; padding:.45rem 1.1rem; font-size:.85rem; font-weight:700; text-decoration:none;">
              + Add Category
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
              placeholder="Search categories..." value="{{ isset($search)?$search:'' }}" style="width:220px;">
            <button type="button" id="clear-search" class="btn btn-sm btn-light border">&times;</button>
          </div>
        </div>

        <div id="table-data">
          <div class="table-responsive">
            <table class="table table-hover align-middle">
              <thead>
                <tr style="background:#f8f7ff;">
                  <th style="border-bottom:2px solid #e8e4ff; padding:.75rem 1rem; font-weight:700; color:#333;">Name</th>
                  <th style="border-bottom:2px solid #e8e4ff; padding:.75rem 1rem; font-weight:700; color:#333;">Thumbnail</th>
                  <th style="border-bottom:2px solid #e8e4ff; padding:.75rem 1rem; font-weight:700; color:#333;">Status</th>
                  <th style="border-bottom:2px solid #e8e4ff; padding:.75rem 1rem; font-weight:700; color:#333; text-align:right;">Action</th>
                </tr>
              </thead>
              <tbody>
                @forelse($categories as $category)
                  <tr style="border-bottom:1px solid #f0f0f0;">
                    <td style="padding:.7rem 1rem; font-weight:600; color:#222;">{{ $category->name }}</td>
                    <td style="padding:.7rem 1rem;">
                      @if($category->image)
                        <img src="{{ asset('upload/filter/'.rawurlencode($category->name).'/category%20image/'.rawurlencode($category->image)) }}"
                          alt="{{ $category->name }}"
                          style="width:44px; height:44px; object-fit:cover; border-radius:50%; border:2px solid #e8e4ff;">
                      @else
                        <div style="width:44px; height:44px; border-radius:50%; background:#e8e4ff; display:flex; align-items:center; justify-content:center;">
                          <i class="fas fa-image" style="color:#9b8fd4; font-size:.9rem;"></i>
                        </div>
                      @endif
                    </td>
                    <td style="padding:.7rem 1rem;">
                      <span class="status-badge" data-id="{{ $category->id }}" data-status="{{ $category->status ? 1 : 0 }}"
                        style="display:inline-flex; align-items:center; gap:5px; cursor:pointer;
                          background:{{ $category->status ? '#e8f5e9' : '#fce4ec' }};
                          color:{{ $category->status ? '#2e7d32' : '#c62828' }};
                          padding:4px 12px; border-radius:20px; font-size:.8rem; font-weight:600; border:1px solid {{ $category->status ? '#a5d6a7' : '#ef9a9a' }};">
                        @if($category->status)
                          <i class="fas fa-check-circle"></i> Active
                        @else
                          <i class="fas fa-times-circle"></i> Inactive
                        @endif
                      </span>
                    </td>
                    <td style="padding:.7rem 1rem; text-align:right;">
                      <a href="{{ route('filter-categories.edit', $category->id) }}"
                        class="btn btn-info btn-sm" style="border-radius:6px;">
                        <i class="fas fa-pencil-alt"></i>
                      </a>
                      <button class="btn btn-danger btn-sm delete-btn ms-1"
                        data-url="{{ route('filter-categories.destroy', $category->id) }}"
                        data-name="{{ $category->name }}"
                        data-count="{{ $category->filters_count }}"
                        style="border-radius:6px;">
                        <i class="fas fa-trash"></i>
                      </button>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="4" class="text-center text-muted py-4">No filter categories found.</td>
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

@endsection

@push('scripts')
<script src="{{ asset('assets/js/plugin/sweetalert2.all.min.js') }}"></script>
<script>
$(document).ready(function () {

  /* Status toggle */
  $(document).on('click', '.status-badge', function () {
    var $badge     = $(this);
    var id         = $badge.data('id');
    var curStatus  = parseInt($badge.data('status'));
    var newStatus  = curStatus === 1 ? 0 : 1;

    $.ajax({
      url:  "{{ route('filter-categories.update-status') }}",
      type: 'POST',
      data: { id: id, status: newStatus, _token: '{{ csrf_token() }}' },
      success: function (res) {
        if (res.success) {
          $badge.data('status', newStatus);
          if (newStatus === 1) {
            $badge.css({ background:'#e8f5e9', color:'#2e7d32', 'border-color':'#a5d6a7' })
                  .html('<i class="fas fa-check-circle"></i> Active');
          } else {
            $badge.css({ background:'#fce4ec', color:'#c62828', 'border-color':'#ef9a9a' })
                  .html('<i class="fas fa-times-circle"></i> Inactive');
          }
        }
      }
    });
  });

  /* Delete */
  $(document).on('click', '.delete-btn', function () {
    var url   = $(this).data('url');
    var name  = $(this).data('name');
    var count = parseInt($(this).data('count')) || 0;
    var $row  = $(this).closest('tr');

    Swal.fire({
      title: 'Delete Category?',
      html:  'You are about to delete <strong>"' + $('<div>').text(name).html() + '"</strong>.',
      icon:  'warning',
      showCancelButton:   true,
      confirmButtonColor: '#e8a020',
      cancelButtonColor:  '#6c757d',
      confirmButtonText:  'Yes, continue &rarr;',
    }).then(function (s1) {
      if (!s1.isConfirmed) return;
      Swal.fire({
        title: 'Final Confirmation',
        html: '<div style="text-align:left;"><p class="text-danger fw-bold mb-2">&#9888; This will permanently delete:</p>' +
              '<ul class="mb-3"><li>Category thumbnail image</li>' +
              '<li>All associated filters (<strong>' + count + ' filter' + (count !== 1 ? 's' : '') + '</strong>)</li>' +
              '<li>All filter images &amp; zip files</li></ul>' +
              '<p class="mb-0 text-muted" style="font-size:.88rem;">This action <strong>cannot be undone</strong>.</p></div>',
        icon: 'error',
        showCancelButton:   true,
        confirmButtonColor: '#d33',
        cancelButtonColor:  '#6c757d',
        confirmButtonText:  '<i class="fas fa-trash me-1"></i> Delete Category',
      }).then(function (s2) {
        if (!s2.isConfirmed) return;
        $.ajax({
          url: url, type: 'DELETE',
          data: { _token: '{{ csrf_token() }}' },
          success: function (res) {
            if (res.success) {
              Swal.fire({ title:'Deleted!', text:'"'+name+'" deleted.', icon:'success', timer:2000, showConfirmButton:false });
              $row.remove();
            }
          },
          error: function () { Swal.fire('Error!', 'Failed to delete.', 'error'); }
        });
      });
    });
  });

  /* AJAX table */
  function fetchData(page) {
    $.ajax({
      url: "{{ route('filter-categories.index') }}",
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
});
</script>
@endpush
