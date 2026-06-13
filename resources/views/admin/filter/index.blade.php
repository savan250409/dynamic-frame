@extends('layouts.app')

@section('title', 'Filter Management')

@section('content')

<div class="page-header">
  <h4 class="page-title">Filter Management</h4>
  <ul class="breadcrumbs">
    <li class="nav-home"><a href="{{ route('dashboard') }}"><i class="icon-home"></i></a></li>
    <li class="separator"><i class="icon-arrow-right"></i></li>
    <li class="nav-item"><a href="#">Filter Management</a></li>
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
              <i class="fas fa-sliders-h" style="color:#fff; font-size:1.3rem;"></i>
            </div>
            <div>
              <h5 class="mb-0 fw-bold" style="color:#fff;">Filter Management</h5>
              <small style="color:rgba(255,255,255,.75); font-size:.8rem;">Manage filters</small>
            </div>
          </div>
          <div class="d-flex align-items-center gap-2">
            <span class="badge" style="background:rgba(255,255,255,.2); color:#fff; font-size:.8rem; padding:.45rem .9rem; border-radius:20px; font-weight:600; border:1px solid rgba(255,255,255,.3);">
              <i class="fas fa-layer-group me-1"></i> Total {{ $filters->total() }}
            </span>
            <a href="{{ route('filters.import-csv') }}"
              style="background:rgba(255,255,255,.2); color:#fff; border:1px solid rgba(255,255,255,.4); border-radius:20px; padding:.4rem 1rem; font-size:.85rem; font-weight:600; text-decoration:none;">
              <i class="fas fa-file-import me-1"></i> Import CSV
            </a>
            <a href="{{ route('filters.create') }}"
              style="background:#fff; color:#5b4fcf; border:none; border-radius:20px; padding:.45rem 1.1rem; font-size:.85rem; font-weight:700; text-decoration:none;">
              + Add Filter
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
        <div class="d-flex justify-content-between align-items-center mb-3 mt-2 flex-wrap gap-2">
          <div class="d-flex align-items-center gap-2">
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
            <select id="category-filter" class="form-select form-select-sm" style="width:160px;">
              <option value="">All Categories</option>
              @foreach($allCategories as $cat)
                <option value="{{ $cat->id }}" {{ (isset($categoryId) && $categoryId == $cat->id) ? 'selected' : '' }}>
                  {{ $cat->name }}
                </option>
              @endforeach
            </select>
          </div>
          <div class="d-flex align-items-center gap-2">
            <input type="search" id="search-input" class="form-control form-control-sm"
              placeholder="Search filters..." value="{{ isset($search)?$search:'' }}" style="width:220px;">
            <button type="button" id="clear-search" class="btn btn-sm btn-light border">&times;</button>
          </div>
        </div>

        <div id="table-data">
          <div class="table-responsive">
            <table class="table table-hover align-middle">
              <thead>
                <tr style="background:#f8f7ff;">
                  <th style="border-bottom:2px solid #e8e4ff; padding:.75rem 1rem; font-weight:700; color:#333;">Category</th>
                  <th style="border-bottom:2px solid #e8e4ff; padding:.75rem 1rem; font-weight:700; color:#333;">Filter Name</th>
                  <th style="border-bottom:2px solid #e8e4ff; padding:.75rem 1rem; font-weight:700; color:#333;">Values</th>
                  <th style="border-bottom:2px solid #e8e4ff; padding:.75rem 1rem; font-weight:700; color:#333;">Type</th>
                  <th style="border-bottom:2px solid #e8e4ff; padding:.75rem 1rem; font-weight:700; color:#333; text-align:right;">Action</th>
                </tr>
              </thead>
              <tbody>
                @forelse($filters as $filter)
                  <tr style="border-bottom:1px solid #f0f0f0;">
                    <td style="padding:.7rem 1rem; font-weight:700; color:#333;">{{ $filter->filterCategory->name ?? '—' }}</td>
                    <td style="padding:.7rem 1rem; color:#444;">{{ $filter->name }}</td>
                    <td style="padding:.7rem 1rem;">
                      <div style="font-size:.78rem; color:#555; line-height:1.6; font-family:monospace;">
                        <div>S: {{ $filter->saturation }}, B: {{ $filter->brightness }}, C: {{ $filter->contrast }}</div>
                        <div>R: {{ $filter->red }}, G: {{ $filter->green }}, B: {{ $filter->blue }}</div>
                      </div>
                    </td>
                    <td style="padding:.7rem 1rem;">
                      @if($filter->is_premium)
                        <span class="badge bg-primary" style="font-size:.75rem; border-radius:10px;">
                          <i class="fas fa-check me-1"></i> Pro
                        </span>
                      @else
                        <span class="badge bg-secondary" style="font-size:.75rem; border-radius:10px;">Free</span>
                      @endif
                    </td>
                    <td style="padding:.7rem 1rem; text-align:right;">
                      <a href="{{ route('filters.edit', $filter->id) }}"
                        class="btn btn-info btn-sm" style="border-radius:6px;">
                        <i class="fas fa-pencil-alt"></i>
                      </a>
                      <button class="btn btn-danger btn-sm delete-btn ms-1"
                        data-url="{{ route('filters.destroy', $filter->id) }}"
                        data-name="{{ $filter->name }}"
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

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function () {

  /* Delete */
  $(document).on('click', '.delete-btn', function () {
    var url  = $(this).data('url');
    var name = $(this).data('name');
    var $row = $(this).closest('tr');

    Swal.fire({
      title: 'Delete Filter?',
      html:  'Delete <strong>"' + $('<div>').text(name).html() + '"</strong>?',
      icon:  'warning',
      showCancelButton:   true,
      confirmButtonColor: '#e8a020',
      cancelButtonColor:  '#6c757d',
      confirmButtonText:  'Yes, continue &rarr;',
    }).then(function (s1) {
      if (!s1.isConfirmed) return;
      Swal.fire({
        title: 'Final Confirmation',
        html: '<p class="text-danger fw-bold">&#9888; This action <strong>cannot be undone</strong>.</p>',
        icon: 'error',
        showCancelButton:   true,
        confirmButtonColor: '#d33',
        cancelButtonColor:  '#6c757d',
        confirmButtonText:  '<i class="fas fa-trash me-1"></i> Delete Filter',
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
      url: "{{ route('filters.index') }}",
      data: {
        page: page,
        search: $('#search-input').val(),
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
  $(document).on('keyup', '#search-input', function () { fetchData(1); });
  $(document).on('change', '#per_page', function () { fetchData(1); });
  $(document).on('change', '#category-filter', function () { fetchData(1); });
  $('#clear-search').on('click', function () { $('#search-input').val(''); fetchData(1); });
});
</script>
@endpush
