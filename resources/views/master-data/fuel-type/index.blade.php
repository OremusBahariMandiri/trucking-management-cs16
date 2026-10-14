@extends('layouts.admin')

@section('title', 'Jenis Bahan Bakar')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
@endpush

@section('content')

<div class="veh-page-header mb-4">
    <div>
        <div class="veh-title">
            <i class="fas fa-gas-pump"></i>
            <span data-en="Fuel Types">Jenis Bahan Bakar</span>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" data-en="Home">Beranda</a></li>
                <li class="breadcrumb-item active" data-en="Fuel Types">Jenis Bahan Bakar</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('master-data.fuel-types.create') }}"
       class="btn btn-primary btn-sm d-flex align-items-center gap-2">
        <i class="fas fa-plus"></i>
        <span data-en="Add Fuel Type">Tambah Jenis BBM</span>
    </a>
</div>

{{-- Stats --}}
<div class="lkp-stats mb-4">
    @php
        $statsConfig = [
            ['label'=>'Total Jenis BBM', 'label_en'=>'Total Fuel Types', 'key'=>'total',    'icon'=>'fas fa-gas-pump',    'color'=>'#d97706','soft'=>'rgba(217,119,6,.12)'],
            ['label'=>'Aktif',           'label_en'=>'Active',           'key'=>'active',   'icon'=>'fas fa-check-circle','color'=>'#16a34a','soft'=>'rgba(22,163,74,.12)'],
            ['label'=>'Tidak Aktif',     'label_en'=>'Inactive',         'key'=>'inactive', 'icon'=>'fas fa-ban',        'color'=>'#64748b','soft'=>'rgba(100,116,139,.12)'],
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
    <form method="GET" action="{{ route('master-data.fuel-types.index') }}"
          class="row g-2 align-items-end">
        <div class="col-12 col-sm-6 col-md-4">
            <label class="form-label form-label-sm" data-en="Search">Cari</label>
            <div class="input-group input-group-sm">
                <span class="input-group-text"><i class="fas fa-search"></i></span>
                <input type="text" name="q" class="form-control"
                       value="{{ request('q') }}"
                       placeholder="Nama atau kode BBM…"
                       data-en-placeholder="Name or fuel code…">
            </div>
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
        @if(request()->hasAny(['q','status']))
        <div class="col-6 col-md-auto">
            <a href="{{ route('master-data.fuel-types.index') }}" class="btn btn-outline-secondary btn-sm w-100">
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
        <h6><i class="fas fa-gas-pump"></i>
            <span data-en="Fuel Type List">Daftar Jenis Bahan Bakar</span>
        </h6>
        <small style="color:var(--text-faint)">
            {{ $fuelTypes->total() }} <span data-en="records">data</span>
        </small>
    </div>

    @if($fuelTypes->isEmpty())
    <div class="veh-empty">
        <div class="veh-empty-icon"><i class="fas fa-gas-pump"></i></div>
        <h6 data-en="No fuel types found">Belum ada jenis bahan bakar</h6>
        <p data-en="Add the first fuel type.">Tambah jenis bahan bakar pertama.</p>
    </div>
    @else
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th data-en="Name">Nama</th>
                    <th data-en="Status">Status</th>
                    <th class="text-end" data-en="Actions">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($fuelTypes as $item)
                <tr>
                    <td><span class="lkp-name">{{ $item->name }}</span></td>
                    <td>
                        <span class="badge-status {{ $item->is_active ? 'status-active' : 'status-inactive' }}">
                            <span data-en="{{ $item->is_active ? 'Active' : 'Inactive' }}">
                                {{ $item->is_active ? 'Aktif' : 'Tidak Aktif' }}
                            </span>
                        </span>
                    </td>
                    <td>
                        <div class="d-flex justify-content-end gap-1">
                            <a href="{{ route('master-data.fuel-types.edit', $item) }}"
                               class="btn btn-sm btn-outline-primary" title="Edit">
                                <i class="fas fa-pen"></i>
                            </a>
                            <form method="POST"
                                  action="{{ route('master-data.fuel-types.destroy', $item) }}"
                                  onsubmit="return confirm('Hapus jenis BBM ini?')">
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
            {{ $fuelTypes->firstItem() }}–{{ $fuelTypes->lastItem() }}
            <span data-en="of">dari</span>
            {{ $fuelTypes->total() }}
        </span>
        {{ $fuelTypes->withQueryString()->links() }}
    </div>
    @endif
</div>

@endsection