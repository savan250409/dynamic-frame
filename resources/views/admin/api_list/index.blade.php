@extends('layouts.app')

@section('title', 'API List')

@section('content')

<div class="page-header">
  <h4 class="page-title">API List</h4>
  <ul class="breadcrumbs">
    <li class="nav-home"><a href="{{ route('dashboard') }}"><i class="icon-home"></i></a></li>
    <li class="separator"><i class="icon-arrow-right"></i></li>
    <li class="nav-item"><a href="#">API List</a></li>
  </ul>
</div>

@push('scripts')
<style>
.api-card {
  background: #fff;
  border: 1px solid #e5e9f0;
  border-radius: 10px;
  padding: 1.2rem 1.4rem;
  height: 100%;
}
.api-card .api-num-title {
  font-size: .95rem;
  font-weight: 700;
  color: #1e2a3a;
  margin-bottom: .65rem;
}
.api-badge-get  { background: #17a2b8; color: #fff; font-size: .72rem; font-weight: 700; padding: 3px 12px; border-radius: 5px; letter-spacing: .4px; }
.api-badge-post { background: #17a2b8; color: #fff; font-size: .72rem; font-weight: 700; padding: 3px 12px; border-radius: 5px; letter-spacing: .4px; }
.api-section-label {
  font-size: .78rem;
  font-weight: 700;
  color: #888;
  margin-top: .8rem;
  margin-bottom: .2rem;
  display: block;
}
.api-url {
  font-size: .8rem;
  color: #e05a00;
  word-break: break-all;
  font-family: monospace;
}
.api-header-val {
  font-size: .8rem;
  color: #e05a00;
  font-family: monospace;
}
.api-param-row {
  font-size: .8rem;
  color: #333;
  display: flex;
  gap: .4rem;
  align-items: baseline;
  margin-bottom: 2px;
}
.api-param-key  { color: #1a73e8; font-family: monospace; font-weight: 600; }
.api-param-type { color: #888; font-style: italic; }
.api-param-req  { color: #d33; font-weight: 700; font-size: .7rem; }
.api-desc {
  font-size: .82rem;
  color: #555;
  line-height: 1.55;
  margin-top: .3rem;
  margin-bottom: 0;
}
.api-divider { border-top: 1px solid #f0f2f5; margin: .6rem 0; }
</style>
@endpush

@php
  $base  = rtrim(request()->root(), '/') . '/api';
  $token = 'Bearer <Your_API_Token>';

  $apis = [
    [
      'num'    => 1,
      'title'  => 'Get AI Filter Categories',
      'method' => 'GET',
      'url'    => $base . '/ai-image-filter/categories',
      'headers'=> ['Authorization' => $token],
      'params' => [],
      'desc'   => 'Fetches all active AI image filter categories along with their filters. Categories with no filters are excluded from the response.',
    ],
    [
      'num'    => 2,
      'title'  => 'Get Filters By Category',
      'method' => 'POST',
      'url'    => $base . '/ai-image-filter/get-by-category',
      'headers'=> ['Authorization' => $token],
      'params' => [
        ['key' => 'category_id', 'type' => 'integer', 'required' => true, 'desc' => 'Required'],
      ],
      'desc'   => 'Fetches all filters for a specific active category. Pass <code>category_id</code> in the request body as JSON.',
    ],
    [
      'num'    => 3,
      'title'  => 'Get Stickers',
      'method' => 'GET',
      'url'    => $base . '/sticker/get-stickers',
      'headers'=> ['Authorization' => $token],
      'params' => [],
      'desc'   => 'Fetches all active sticker categories along with their sticker image URLs and thumbnail. Returns <code>id</code>, <code>category_name</code>, <code>is_premium</code>, <code>thumbnail_url</code>, and <code>stickers</code> (array of image URLs).',
    ],
    [
      'num'    => 4,
      'title'  => 'Get All Filters',
      'method' => 'GET',
      'url'    => $base . '/filter/get-all-filters',
      'headers'=> ['Authorization' => $token],
      'params' => [],
      'desc'   => 'Fetches all active filter categories with their active filters. Returns <code>id</code>, <code>name</code>, <code>image_url</code>, and <code>filters</code> array (each with <code>id</code>, <code>name</code>, <code>is_premium</code>, <code>values</code> containing saturation, brightness, contrast, red, green, blue). Categories with no active filters are excluded.',
    ],
    [
      'num'    => 5,
      'title'  => 'Get Doodles',
      'method' => 'GET',
      'url'    => $base . '/doodle/get-doodles',
      'headers'=> ['Authorization' => $token],
      'params' => [],
      'desc'   => 'Fetches all active doodles. Returns <code>id</code>, <code>name</code>, <code>doodle_type</code> (<code>image</code> or <code>line</code>), <code>is_premium</code>, and <code>image_url</code>. Only doodles with <code>status = 1</code> are included.',
    ],
    [
      'num'    => 5,
      'title'  => 'Get Fonts',
      'method' => 'GET',
      'url'    => $base . '/font/get-fonts',
      'headers'=> ['Authorization' => $token],
      'params' => [],
      'desc'   => 'Fetches all active fonts. Returns <code>id</code>, <code>font_name</code>, <code>is_premium</code>, <code>font_url</code> (downloadable font file URL), and <code>preview_url</code> (preview image URL). Only fonts with <code>status = 1</code> are included.',
    ],
  ];
@endphp

<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body">

        <div class="d-flex align-items-center pb-3 mb-4" style="border-bottom:2px solid #f0f0f0;">
          <i class="fas fa-code text-primary" style="font-size:2rem; margin-right:12px;"></i>
          <div>
            <h4 class="mb-0 fw-bold text-primary">API List</h4>
            <small class="text-muted">AI Image Filter, Filter, Sticker, Doodle &amp; Font — available API endpoints</small>
          </div>
        </div>

        <div class="row g-3">
          @foreach($apis as $api)
          <div class="col-md-6">
            <div class="api-card">

              {{-- Number + Title --}}
              <div class="api-num-title">{{ $api['num'] }}. {{ $api['title'] }}</div>

              {{-- Method badge --}}
              <div class="mb-1">
                <span class="me-1" style="font-size:.78rem; font-weight:600; color:#888;">Method</span>
                <span class="api-badge-{{ strtolower($api['method']) }}">{{ $api['method'] }}</span>
              </div>

              <div class="api-divider"></div>

              {{-- URL --}}
              <span class="api-section-label">URL:</span>
              <div class="api-url">{{ $api['url'] }}</div>

              {{-- Parameters (POST only) --}}
              @if(!empty($api['params']))
                <span class="api-section-label">Parameters:</span>
                @foreach($api['params'] as $p)
                  <div class="api-param-row">
                    <span class="api-param-key">{{ $p['key'] }}</span>
                    <span class="api-param-type">({{ $p['type'] }})</span>
                    <span class="api-param-req">{{ $p['desc'] }}</span>
                  </div>
                @endforeach
              @endif

              {{-- Headers --}}
              <span class="api-section-label">Headers:</span>
              @foreach($api['headers'] as $hKey => $hVal)
                <div>
                  <span style="font-size:.8rem; color:#555; font-family:monospace;">{{ $hKey }}:&nbsp;</span>
                  <span class="api-header-val">{{ $hVal }}</span>
                </div>
              @endforeach

              <div class="api-divider"></div>

              {{-- Description --}}
              <span class="api-section-label">Description:</span>
              <p class="api-desc">{!! $api['desc'] !!}</p>

            </div>
          </div>
          @endforeach
        </div>

      </div>
    </div>
  </div>
</div>

@endsection
