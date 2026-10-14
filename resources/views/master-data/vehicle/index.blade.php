@extends('layouts.admin')

@section('title', 'Daftar Kendaraan')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
@endpush

@section('content')

{{-- ── Page Header ─────────────────────────────────────────── --}}
<div class="veh-page-header mb-4">
    <div>
        <div class="veh-title">
            <i class="fas fa-truck"></i>
            <span data-en="Fleet Management">Manajemen Kendaraan</span>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" data-en="Home">Beranda</a></li>
                <li class="breadcrumb-item active" data-en="Fleet">Kendaraan</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('master-data.kendaraan.create') }}"
       class="btn btn-primary btn-sm d-flex align-items-center gap-2">
        <i class="fas fa-plus"></i>
        <span data-en="Add Vehicle">Tambah Kendaraan</span>
    </a>
</div>

{{-- ── Stat Cards ───────────────────────────────────────────── --}}
<div class="veh-stats mb-4">
    @php
        $statsConfig = [
            ['label'=>'Total Kendaraan', 'label_en'=>'Total Vehicles',  'key'=>'total',       'icon'=>'fas fa-truck',          'color'=>'#3b6fd4', 'soft'=>'rgba(59,111,212,.12)'],
            ['label'=>'Aktif',           'label_en'=>'Active',          'key'=>'active',      'icon'=>'fas fa-check-circle',   'color'=>'#16a34a', 'soft'=>'rgba(22,163,74,.12)'],
            ['label'=>'Perawatan',       'label_en'=>'Maintenance',     'key'=>'maintenance', 'icon'=>'fas fa-tools',          'color'=>'#d97706', 'soft'=>'rgba(217,119,6,.12)'],
            ['label'=>'Tidak Aktif',     'label_en'=>'Inactive',        'key'=>'inactive',    'icon'=>'fas fa-ban',            'color'=>'#64748b', 'soft'=>'rgba(100,116,139,.12)'],
            ['label'=>'Milik Sendiri',   'label_en'=>'Own Fleet',       'key'=>'owned',       'icon'=>'fas fa-id-card',        'color'=>'#0ea5e9', 'soft'=>'rgba(14,165,233,.12)'],
            ['label'=>'Disewakan/Leasing','label_en'=>'Leased',         'key'=>'leased',      'icon'=>'fas fa-file-contract',  'color'=>'#8b5cf6', 'soft'=>'rgba(139,92,246,.12)'],
        ];
    @endphp
    @foreach($statsConfig as $s)
    <div class="stat-card" style="--stat-color:{{ $s['color'] }};--stat-soft:{{ $s['soft'] }}">
        <div class="stat-icon-wrap">
            <i class="{{ $s['icon'] }}"></i>
        </div>
        <div class="stat-number">{{ $stats[$s['key']] ?? 0 }}</div>
        <div class="stat-label" data-en="{{ $s['label_en'] }}">{{ $s['label'] }}</div>
    </div>
    @endforeach
</div>

{{-- ── Filter Bar ───────────────────────────────────────────── --}}
<div class="veh-filter-bar mb-3">
    <form method="GET" action="{{ route('master-data.kendaraan.index') }}"
          class="row g-2 align-items-end">
        <div class="col-12 col-sm-6 col-md-3">
            <label class="form-label form-label-sm" data-en="Search">Cari</label>
            <div class="input-group input-group-sm">
                <span class="input-group-text"><i class="fas fa-search"></i></span>
                <input type="text" name="q" class="form-control"
                       value="{{ request('q') }}"
                       placeholder="Kode, no. pol, merek…"
                       data-en-placeholder="Code, plate, brand…">
            </div>
        </div>
        <div class="col-6 col-md-2">
            <label class="form-label form-label-sm" data-en="Status">Status</label>
            <select name="status" class="form-select form-select-sm">
                <option value="" data-en="All Status">Semua Status</option>
                @foreach($filterOptions['statuses'] ?? [] as $val => $label)
                    <option value="{{ $val }}" {{ request('status') === $val ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-6 col-md-2">
            <label class="form-label form-label-sm" data-en="Type">Jenis</label>
            <select name="type" class="form-select form-select-sm">
                <option value="" data-en="All Types">Semua Jenis</option>
                @foreach($filterOptions['types'] ?? [] as $t)
                    <option value="{{ $t->id }}" {{ request('type') == $t->id ? 'selected' : '' }}>{{ $t->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-6 col-md-2">
            <label class="form-label form-label-sm" data-en="Brand">Merek</label>
            <select name="brand" class="form-select form-select-sm">
                <option value="" data-en="All Brands">Semua Merek</option>
                @foreach($filterOptions['brands'] ?? [] as $b)
                    <option value="{{ $b->id }}" {{ request('brand') == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-6 col-md-auto">
            <button type="submit" class="btn btn-primary btn-sm w-100">
                <i class="fas fa-filter me-1"></i>
                <span data-en="Filter">Filter</span>
            </button>
        </div>
        @if(request()->hasAny(['q','status','type','brand']))
        <div class="col-6 col-md-auto">
            <a href="{{ route('master-data.kendaraan.index') }}" class="btn btn-outline-secondary btn-sm w-100">
                <i class="fas fa-times-circle me-1"></i>
                <span data-en="Reset">Reset</span>
            </a>
        </div>
        @endif
    </form>
</div>

{{-- ── Table Card ───────────────────────────────────────────── --}}
<div class="veh-table-card">
    <div class="veh-table-header">
        <h6><i class="fas fa-list-alt"></i>
            <span data-en="Vehicle List">Daftar Kendaraan</span>
        </h6>
        <small style="color:var(--text-faint)">
            {{ $vehicles->total() }}
            <span data-en="records">data</span>
        </small>
    </div>

    @if($vehicles->isEmpty())
    <div class="veh-empty">
        <div class="veh-empty-icon"><i class="fas fa-truck"></i></div>
        <h6 data-en="No vehicles found">Tidak ada kendaraan ditemukan</h6>
        <p data-en="Try adjusting your filter or add a new vehicle.">Coba ubah filter atau tambah kendaraan baru.</p>
    </div>
    @else
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th data-en="Code / Plate">Kode / No Pol</th>
                    <th data-en="Brand / Model">Merek / Model</th>
                    <th data-en="Type">Jenis</th>
                    <th data-en="Year">Tahun</th>
                    <th data-en="Status">Status</th>
                    <th data-en="Ownership">Kepemilikan</th>
                    <th class="text-end" data-en="Actions">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($vehicles as $v)
                <tr>
                    <td>
                        <span class="vehicle-code">{{ $v->vehicle_code ?? '-' }}</span>
                        <span class="vehicle-police">{{ $v->police_number }}</span>
                    </td>
                    <td>
                        <span class="vehicle-brand">{{ $v->brand?->name ?? '-' }}</span>
                        <span class="vehicle-model">{{ $v->model?->name ?? $v->model_name ?? '-' }}</span>
                    </td>
                    <td style="font-size:.83rem;color:var(--text-muted)">{{ $v->type?->name ?? '-' }}</td>
                    <td style="font-size:.83rem;color:var(--text-muted)">{{ $v->manufacture_year ?? '-' }}</td>
                    <td>
                        <span class="badge-status status-{{ $v->vehicle_status ?? 'inactive' }}">
                            {{ $v->status_label }}
                        </span>
                    </td>
                    <td>
                        <span class="badge-ownership">{{ $v->ownership_label ?? '-' }}</span>
                    </td>
                    <td>
                        <div class="d-flex justify-content-end gap-1">
                            <a href="{{ route('master-data.kendaraan.show', $v) }}"
                               class="btn btn-sm btn-outline-secondary" title="Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('master-data.kendaraan.edit', $v) }}"
                               class="btn btn-sm btn-outline-primary" title="Edit">
                                <i class="fas fa-pen"></i>
                            </a>
                            <form method="POST"
                                  action="{{ route('master-data.kendaraan.destroy', $v) }}"
                                  onsubmit="return confirm('Hapus kendaraan ini?')">
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
            {{ $vehicles->firstItem() }}–{{ $vehicles->lastItem() }}
            <span data-en="of">dari</span>
            {{ $vehicles->total() }}
        </span>
        {{ $vehicles->withQueryString()->links() }}
    </div>
    @endif
</div>

@endsection

@push('scripts')
<script type="module" src="{{ asset('js/pages/vehicle.js') }}"></script>
@endpush