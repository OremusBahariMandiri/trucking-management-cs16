@extends('layouts.admin')

@section('title', 'Model Kendaraan')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
@endpush

@section('content')

<div class="veh-page-header mb-4">
    <div>
        <div class="veh-title">
            <i class="fas fa-car-side"></i>
            <span data-en="Vehicle Models">Model Kendaraan</span>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" data-en="Home">Beranda</a></li>
                <li class="breadcrumb-item active" data-en="Vehicle Models">Model Kendaraan</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('master-data.vehicle-models.create') }}"
       class="btn btn-primary btn-sm d-flex align-items-center gap-2">
        <i class="fas fa-plus"></i>
        <span data-en="Add Model">Tambah Model</span>
    </a>
</div>

{{-- Stats --}}
<div class="lkp-stats mb-4">
    @php
        $statsConfig = [
            ['label'=>'Total Model', 'label_en'=>'Total Models', 'key'=>'total',    'icon'=>'fas fa-car-side',    'color'=>'#3b6fd4','soft'=>'rgba(59,111,212,.12)'],
            ['label'=>'Aktif',       'label_en'=>'Active',       'key'=>'active',   'icon'=>'fas fa-check-circle','color'=>'#16a34a','soft'=>'rgba(22,163,74,.12)'],
            ['label'=>'Tidak Aktif', 'label_en'=>'Inactive',     'key'=>'inactive', 'icon'=>'fas fa-ban',        'color'=>'#64748b','soft'=>'rgba(100,116,139,.12)'],
        ];
    @endphp
    @foreach($statsConfig as $s)
    <div class="lkp-stat" style="--stat-color:{{ $s['color'] }};--stat-soft:{{ $s['soft'] }}">
        <div class="lkp-stat-icon"><i class="{{ $s['icon'] }}"></i></div>
        <div>
            <div class="lkp-stat-num">{{ $statCounts[$s['key']] ?? 0 }}</div>
            <div class="lkp-stat-label" data-en="{{ $s['label_en'] }}">{{ $s['label'] }}</div>
        </div>
    </div>
    @endforeach
</div>

{{-- Filter --}}
<div class="veh-filter-bar mb-3">
    <form method="GET" action="{{ route('master-data.vehicle-models.index') }}"
          class="row g-2 align-items-end">
        <div class="col-12 col-sm-6 col-md-3">
            <label class="form-label form-label-sm" data-en="Search">Cari</label>
            <div class="input-group input-group-sm">
                <span class="input-group-text"><i class="fas fa-search"></i></span>
                <input type="text" name="q" class="form-control"
                       value="{{ request('q') }}"
                       placeholder="Nama model…"
                       data-en-placeholder="Model name…">
            </div>
        </div>
        <div class="col-6 col-md-2">
            <label class="form-label form-label-sm" data-en="Brand">Merek</label>
            <select name="brand_id" class="form-select form-select-sm">
                <option value="" data-en="All Brands">Semua Merek</option>
                @foreach($brands ?? [] as $brand)
                    <option value="{{ $brand->id }}" {{ request('brand_id') == $brand->id ? 'selected' : '' }}>
                        {{ $brand->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-6 col-md-2">
            <label class="form-label form-label-sm" data-en="Status">Status</label>
            <select name="status" class="form-select form-select-sm">
                <option value="" data-en="All">Semua</option>
                <option value="1" {{ request('status') === '1' ? 'selected' : '' }} data-en="Active">Aktif</option>
                <option value="0" {{ request('status') === '0' ? 'selected' : '' }} data-en="Inactive">Tidak Aktif</option>
            </select>
        </div>
        <div class="col-6 col-md-auto">
            <button type="submit" class="btn btn-primary btn-sm w-100">
                <i class="fas fa-filter me-1"></i>
                <span data-en="Filter">Filter</span>
            </button>
        </div>
        @if(request()->hasAny(['q','brand_id','status']))
        <div class="col-6 col-md-auto">
            <a href="{{ route('master-data.vehicle-models.index') }}" class="btn btn-outline-secondary btn-sm w-100">
                <i class="fas fa-times-circle me-1"></i>
                <span data-en="Reset">Reset</span>
            </a>
        </div>
        @endif
    </form>
</div>

{{-- Table --}}
<div class="veh-table-card">
    <div class="veh-table-header">
        <h6><i class="fas fa-car-side"></i>
            <span data-en="Model List">Daftar Model Kendaraan</span>
        </h6>
        <small style="color:var(--text-faint)">
            {{ $vehicleModels->total() }} <span data-en="records">data</span>
        </small>
    </div>

    @if($vehicleModels->isEmpty())
    <div class="veh-empty">
        <div class="veh-empty-icon"><i class="fas fa-car-side"></i></div>
        <h6 data-en="No models found">Belum ada model kendaraan</h6>
        <p data-en="Add the first vehicle model.">Tambah model kendaraan pertama.</p>
    </div>
    @else
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th data-en="Model Name">Nama Model</th>
                    <th data-en="Brand">Merek</th>
                    <th data-en="Type">Jenis</th>
                    <th data-en="Status">Status</th>
                    <th class="text-end" data-en="Actions">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($vehicleModels as $item)
                <tr>
                    <td><span class="lkp-name">{{ $item->name }}</span></td>
                    <td>
                        <span class="lkp-sub">{{ $item->brand?->name ?? '—' }}</span>
                    </td>
                    <td>
                        <span class="lkp-sub">{{ $item->vehicleType?->name ?? '—' }}</span>
                    </td>
                    <td>
                        <span class="badge-status {{ $item->is_active ? 'status-active' : 'status-inactive' }}">
                            <span data-en="{{ $item->is_active ? 'Active' : 'Inactive' }}">
                                {{ $item->is_active ? 'Aktif' : 'Tidak Aktif' }}
                            </span>
                        </span>
                    </td>
                    <td>
                        <div class="d-flex justify-content-end gap-1">
                            <a href="{{ route('master-data.vehicle-models.edit', $item) }}"
                               class="btn btn-sm btn-outline-primary" title="Edit">
                                <i class="fas fa-pen"></i>
                            </a>
                            <form method="POST"
                                  action="{{ route('master-data.vehicle-models.destroy', $item) }}"
                                  onsubmit="return confirm('Hapus model ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="veh-table-footer">
        <span>
            <span data-en="Showing">Menampilkan</span>
            {{ $vehicleModels->firstItem() }}–{{ $vehicleModels->lastItem() }}
            <span data-en="of">dari</span>
            {{ $vehicleModels->total() }}
        </span>
        {{ $vehicleModels->withQueryString()->links() }}
    </div>
    @endif
</div>

@endsection