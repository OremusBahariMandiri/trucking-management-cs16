@extends('layouts.admin')

@section('title', 'Daftar Supir')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
@endpush

@section('content')

{{-- ── Page Header ──────────────────────────────────────────── --}}
<div class="drv-page-header mb-4">
    <div>
        <div class="drv-title">
            <i class="fas fa-id-card"></i>
            <span data-en="Driver Management">Manajemen Supir</span>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" data-en="Home">Beranda</a></li>
                <li class="breadcrumb-item active" data-en="Drivers">Supir</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('data-management.sdm-management.driver-data.create') }}"
       class="btn btn-primary btn-sm d-flex align-items-center gap-2">
        <i class="fas fa-plus"></i>
        <span data-en="Add Driver">Tambah Supir</span>
    </a>
</div>

{{-- ── Filter Bar ───────────────────────────────────────────── --}}
<div class="drv-filter-bar mb-3">
    <form method="GET" action="{{ route('data-management.sdm-management.driver-data.index') }}"
          class="row g-2 align-items-end">
        <div class="col-12 col-sm-6 col-md-4">
            <label class="form-label form-label-sm" data-en="Search">Cari</label>
            <div class="input-group input-group-sm">
                <span class="input-group-text"><i class="fas fa-search"></i></span>
                <input type="text" name="search" class="form-control"
                       value="{{ request('search') }}"
                       placeholder="Kode, nama, telepon…">
            </div>
        </div>
        <div class="col-6 col-md-2">
            <label class="form-label form-label-sm" data-en="Status">Status</label>
            <select name="status" class="form-select form-select-sm">
                <option value="" data-en="All Status">Semua Status</option>
                <option value="active"   {{ request('status') === 'active'   ? 'selected' : '' }} data-en="Active">Aktif</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }} data-en="Inactive">Tidak Aktif</option>
                <option value="resigned" {{ request('status') === 'resigned' ? 'selected' : '' }} data-en="Resigned">Resign</option>
            </select>
        </div>
        <div class="col-6 col-md-auto">
            <button type="submit" class="btn btn-primary btn-sm w-100">
                <i class="fas fa-filter me-1"></i>
                <span data-en="Filter">Filter</span>
            </button>
        </div>
        @if(request()->hasAny(['search', 'status']))
        <div class="col-6 col-md-auto">
            <a href="{{ route('data-management.sdm-management.driver-data.index') }}"
               class="btn btn-outline-secondary btn-sm w-100">
                <i class="fas fa-times-circle me-1"></i>
                <span data-en="Reset">Reset</span>
            </a>
        </div>
        @endif
    </form>
</div>

{{-- ── Table Card ───────────────────────────────────────────── --}}
<div class="drv-table-card">
    <div class="drv-table-header">
        <h6>
            <i class="fas fa-list-alt"></i>
            <span data-en="Driver List">Daftar Supir</span>
        </h6>
        <small style="color:var(--text-faint)">
            {{ $drivers->total() }} <span data-en="records">data</span>
        </small>
    </div>

    @if($drivers->isEmpty())
    <div class="drv-empty">
        <div class="drv-empty-icon"><i class="fas fa-id-card"></i></div>
        <h6 data-en="No drivers found">Tidak ada supir ditemukan</h6>
        <p data-en="Try adjusting your filter or add a new driver.">
            Coba ubah filter atau tambah supir baru.
        </p>
    </div>
    @else
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th style="width:3rem" class="text-center">No</th>
                    <th data-en="Name">Nama</th>
                    <th data-en="Code">Kode</th>
                    <th data-en="NIK">NIK</th>
                    <th data-en="Phone">No Telepon</th>
                    <th data-en="Gender">Gender</th>
                    <th data-en="Status">Status</th>
                    <th class="text-end" data-en="Actions">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($drivers as $driver)
                <tr>
                    {{-- No --}}
                    <td class="text-center" style="font-size:.82rem;color:var(--text-muted)">
                        {{ $drivers->firstItem() + $loop->index }}
                    </td>

                    {{-- Nama --}}
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            @if($driver->photo_path)
                                <img src="{{ Storage::url($driver->photo_path) }}"
                                     alt="{{ $driver->full_name }}"
                                     class="drv-avatar">
                            @else
                                <div class="drv-avatar-placeholder">
                                    <i class="fas fa-user"></i>
                                </div>
                            @endif
                            <span class="drv-name">{{ $driver->full_name }}</span>
                        </div>
                    </td>

                    {{-- Kode --}}
                    <td style="font-family:monospace;font-size:.83rem">
                        {{ $driver->driver_code ?? '—' }}
                    </td>

                    {{-- NIK --}}
                    <td style="font-family:monospace;font-size:.83rem;letter-spacing:.02em">
                        {{ $driver->nik ?? '—' }}
                    </td>

                    {{-- No Telepon --}}
                    <td style="font-size:.83rem;color:var(--text-muted)">
                        {{ $driver->phone ?? '—' }}
                    </td>

                    {{-- Gender --}}
                    <td style="font-size:.83rem;color:var(--text-muted)">
                        {{ $driver->gender_label }}
                    </td>

                    {{-- Status --}}
                    <td>
                        <span class="badge-status status-drv-{{ $driver->status }}">
                            {{ $driver->status_label }}
                        </span>
                    </td>

                    {{-- Aksi --}}
                    <td>
                        <div class="d-flex justify-content-end gap-1">
                            <a href="{{ route('data-management.sdm-management.driver-data.show', $driver) }}"
                               class="btn btn-sm btn-outline-secondary" title="Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('data-management.sdm-management.driver-data.edit', $driver) }}"
                               class="btn btn-sm btn-outline-primary" title="Edit">
                                <i class="fas fa-pen"></i>
                            </a>
                            <form method="POST"
                                  action="{{ route('data-management.sdm-management.driver-data.destroy', $driver) }}"
                                  onsubmit="return confirm('Hapus supir ini?')">
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

    <div class="drv-table-footer">
        <span>
            <span data-en="Showing">Menampilkan</span>
            {{ $drivers->firstItem() }}–{{ $drivers->lastItem() }}
            <span data-en="of">dari</span>
            {{ $drivers->total() }}
        </span>
        {{ $drivers->withQueryString()->links() }}
    </div>
    @endif
</div>

@endsection

@push('scripts')
    @vite('resources/js/pages/driver.js')
@endpush