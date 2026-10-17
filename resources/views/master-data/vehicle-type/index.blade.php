@extends('layouts.admin')

@section('title', 'Jenis Kendaraan')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
@endpush

@section('content')

{{-- ── Page Header ─────────────────────────────────────────── --}}
<div class="veh-page-header mb-4">
    <div>
        <div class="veh-title">
            <i class="fas fa-tags"></i>
            <span data-en="Vehicle Types">Jenis Kendaraan</span>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" data-en="Home">Beranda</a></li>
                <li class="breadcrumb-item active" data-en="Vehicle Types">Jenis Kendaraan</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('master-data.vehicle-types.create') }}"
       class="btn btn-primary btn-sm d-flex align-items-center gap-2">
        <i class="fas fa-plus"></i>
    </a>
</div>

{{-- ── Filter Bar ───────────────────────────────────────────── --}}
<div class="veh-filter-bar mb-3">
    <form method="GET" action="{{ route('master-data.vehicle-types.index') }}"
          class="row g-2 align-items-end">
        <div class="col-12 col-sm-6 col-md-4">
            <label class="form-label form-label-sm" data-en="Search">Cari</label>
            <div class="input-group input-group-sm">
                <span class="input-group-text"><i class="fas fa-search"></i></span>
                <input type="text" name="q" class="form-control"
                       value="{{ request('q') }}"
                       placeholder="Nama jenis…"
                       data-en-placeholder="Type name…">
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
            <a href="{{ route('master-data.vehicle-types.index') }}" class="btn btn-outline-secondary btn-sm w-100">
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
        <h6><i class="fas fa-tags"></i>
            <span data-en="Vehicle Type List">Daftar Jenis Kendaraan</span>
        </h6>
        <small style="color:var(--text-faint)">
            {{ $vehicleTypes->total() }} <span data-en="records">data</span>
        </small>
    </div>

    @if($vehicleTypes->isEmpty())
    <div class="veh-empty">
        <div class="veh-empty-icon"><i class="fas fa-tags"></i></div>
        <h6 data-en="No types found">Belum ada jenis kendaraan</h6>
        <p data-en="Add the first vehicle type.">Tambah jenis kendaraan pertama.</p>
    </div>
    @else
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th data-en="Name">Nama</th>
                    <th data-en="Description">Deskripsi</th>
                    <th data-en="Status">Status</th>
                    <th data-en="Created">Dibuat</th>
                    <th class="text-end" data-en="Actions">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($vehicleTypes as $item)
                <tr>
                    <td>
                        <span class="lkp-name">{{ $item->name }}</span>
                    </td>
                    <td style="max-width:260px">
                        <span class="lkp-sub" style="white-space:normal">
                            {{ Str::limit($item->description, 60) ?: '—' }}
                        </span>
                    </td>
                    <td>
                        <span class="badge-status {{ $item->is_active ? 'status-active' : 'status-inactive' }}">
                            <span data-en="{{ $item->is_active ? 'Active' : 'Inactive' }}">
                                {{ $item->is_active ? 'Aktif' : 'Tidak Aktif' }}
                            </span>
                        </span>
                    </td>
                    <td class="lkp-sub">{{ $item->created_at->format('d M Y') }}</td>
                    <td>
                        <div class="d-flex justify-content-end gap-1">
                            <a href="{{ route('master-data.vehicle-types.edit', $item) }}"
                               class="btn btn-sm btn-outline-primary" title="Edit">
                                <i class="fas fa-pen"></i>
                            </a>
                            <form method="POST"
                                  action="{{ route('master-data.vehicle-types.destroy', $item) }}"
                                  onsubmit="return confirm('{{ __('Hapus jenis ini?') }}')">
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
            {{ $vehicleTypes->firstItem() }}–{{ $vehicleTypes->lastItem() }}
            <span data-en="of">dari</span>
            {{ $vehicleTypes->total() }}
        </span>
        {{ $vehicleTypes->withQueryString()->links() }}
    </div>
    @endif
</div>

@endsection