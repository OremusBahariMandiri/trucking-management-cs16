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
            <span data-en="Driver Management">Manajemen Supir</span>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" data-en="Home">Beranda</a></li>
                <li class="breadcrumb-item active" data-en="Drivers">Supir</li>
            </ol>
        </nav>
    </div>
    <div class="d-flex align-items-center gap-2">
        <button type="button" class="btn btn-secondary btn-sm d-flex align-items-center gap-2"
                data-bs-toggle="modal" data-bs-target="#drvFilterModal">
            <i class="fas fa-filter"></i>
            <span data-en="Filter">Filter</span>
            @if(request()->hasAny(['search','status']))
                <span class="badge bg-primary rounded-pill">
                    {{ collect(['search','status'])->filter(fn($k) => request()->filled($k))->count() }}
                </span>
            @endif
        </button>
        <a href="{{ route('data-management.sdm-management.driver-data.create') }}"
           class="btn btn-primary btn-sm d-flex align-items-center gap-2">
            <i class="fas fa-plus"></i>
            <span data-en="Add Driver">Tambah</span>
        </a>
    </div>
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

    {{-- Desktop table --}}
    <div class="lkp-desktop-table">
        <div class="table-responsive">
            <table id="driverTable" class="table drv-index-table"
                   data-lookup-table data-col-targets='[0,6,7,8,9]' data-no-order="1"
                   style="width:100%">
                <thead>
                    <tr>
                        <th style="width:3rem" class="text-center">No</th>
                        <th data-en="Name">Nama</th>
                        <th data-en="Code">Kode</th>
                        <th data-en="NIK">NIK</th>
                        <th data-en="Gender">Gender</th>
                        <th data-en="Phone">No Telepon</th>
                        <th class="text-center" data-en="Photo">Foto</th>
                        <th class="text-center" data-en="CV">CV</th>
                        <th data-en="Status">Status</th>
                        <th class="text-end" data-en="Actions">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($drivers as $driver)
                    <tr data-row="{{ $loop->index }}">
                        <td class="text-center"></td>
                        <td>{{ $driver->full_name }}</td>
                        <td>{{ $driver->driver_code ?? '—' }}</td>
                        <td>{{ $driver->nik ?? '—' }}</td>
                        <td>{{ $driver->gender_label ?? '—' }}</td>
                        <td>{{ $driver->phone ?? '—' }}</td>
                        <td class="text-center">
                            @if($driver->photo_path)
                                <img src="{{ Storage::url($driver->photo_path) }}"
                                     alt="{{ $driver->full_name }}"
                                     class="drv-thumb"
                                     data-bs-toggle="modal"
                                     data-bs-target="#drvPhotoModal"
                                     data-preview-src="{{ Storage::url($driver->photo_path) }}"
                                     data-preview-name="{{ $driver->full_name }}"
                                     title="Klik untuk preview">
                            @else
                                <span style="color:var(--text-muted)">—</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{ route('data-management.sdm-management.driver-data.pdf', $driver) }}"
                               target="_blank"
                               class="btn btn-sm btn-outline-danger"
                               title="Export CV PDF">
                                <i class="fas fa-file-pdf"></i>
                            </a>
                        </td>
                        <td>{{ $driver->status_label ?? $driver->status }}</td>
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
                                      class="drv-delete-form"
                                      data-name="{{ $driver->full_name }}">
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
    </div>{{-- /lkp-desktop-table --}}

    {{-- Mobile cards --}}
    <div class="lkp-mobile-list">
        @foreach($drivers as $driver)
        <div class="lkp-mob-card" data-row="{{ $loop->index }}">
            <div class="lkp-mob-header">
                @if($driver->photo_path)
                    <img src="{{ Storage::url($driver->photo_path) }}"
                         class="drv-mob-avatar"
                         data-bs-toggle="modal"
                         data-bs-target="#drvPhotoModal"
                         data-preview-src="{{ Storage::url($driver->photo_path) }}"
                         data-preview-name="{{ $driver->full_name }}">
                @else
                    <div class="lkp-mob-initial">{{ strtoupper(substr($driver->full_name, 0, 1)) }}</div>
                @endif
                <div class="lkp-mob-name-wrap">
                    <div class="lkp-mob-name">{{ $driver->full_name }}</div>
                    @if($driver->driver_code)
                    <div class="lkp-mob-sub">{{ $driver->driver_code }}</div>
                    @endif
                </div>
                <i class="fas fa-chevron-down lkp-mob-chevron"></i>
            </div>
            <div class="lkp-mob-details">
                <div class="lkp-mob-detail-row">
                    <span class="lkp-mob-label">NIK</span>
                    <span class="lkp-mob-value">{{ $driver->nik ?? '—' }}</span>
                </div>
                <div class="lkp-mob-detail-row">
                    <span class="lkp-mob-label">Gender</span>
                    <span class="lkp-mob-value">{{ $driver->gender_label ?? '—' }}</span>
                </div>
                <div class="lkp-mob-detail-row">
                    <span class="lkp-mob-label" data-en="Phone">Telepon</span>
                    <span class="lkp-mob-value">{{ $driver->phone ?? '—' }}</span>
                </div>
                <div class="lkp-mob-detail-row">
                    <span class="lkp-mob-label">Status</span>
                    <span class="lkp-mob-value">{{ $driver->status_label ?? $driver->status }}</span>
                </div>
                <div class="lkp-mob-actions">
                    <a href="{{ route('data-management.sdm-management.driver-data.show', $driver) }}"
                       class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-eye me-1"></i>Detail
                    </a>
                    <a href="{{ route('data-management.sdm-management.driver-data.pdf', $driver) }}"
                       target="_blank"
                       class="btn btn-sm btn-outline-danger"
                       title="Export CV PDF">
                        <i class="fas fa-file-pdf me-1"></i>CV
                    </a>
                    <a href="{{ route('data-management.sdm-management.driver-data.edit', $driver) }}"
                       class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-pen me-1"></i>Edit
                    </a>
                    <form method="POST"
                          action="{{ route('data-management.sdm-management.driver-data.destroy', $driver) }}"
                          class="drv-delete-form"
                          data-name="{{ $driver->full_name }}">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach

        <div class="lkp-mob-footer">
            <div class="lkp-mob-pagination-info"></div>
            <div class="lkp-mob-pagination-nav">
                <button class="btn btn-sm btn-outline-secondary lkp-mob-prev" disabled>&#8249;</button>
                <span class="lkp-mob-page-num"></span>
                <button class="btn btn-sm btn-outline-secondary lkp-mob-next">&#8250;</button>
            </div>
        </div>
    </div>{{-- /lkp-mobile-list --}}

    @endif
</div>

{{-- ── Modal Preview Foto ───────────────────────────────────── --}}
<div class="modal fade" id="drvPhotoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content" style="border-radius:12px;overflow:hidden">
            <div class="modal-header py-2 px-3" style="border-bottom:1px solid var(--border)">
                <span id="drvPhotoModalName" style="font-size:.875rem;font-weight:500"></span>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <img id="drvPhotoModalImg" src="" alt=""
                     style="width:100%;display:block;max-height:420px;object-fit:contain;background:#f8fafc">
            </div>
        </div>
    </div>
</div>

{{-- ── Modal Filter ─────────────────────────────────────────── --}}
<div class="modal fade" id="drvFilterModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="GET" action="{{ route('data-management.sdm-management.driver-data.index') }}">
                <div class="modal-header">
                    <h6 class="modal-title">Filter Supir</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label form-label-sm" data-en="Search">Kata Kunci</label>
                            <input type="text" name="search" class="form-control form-control-sm"
                                   placeholder="Kode, nama, telepon…"
                                   value="{{ request('search') }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label form-label-sm" data-en="Status">Status</label>
                            <select name="status" class="form-select form-select-sm">
                                <option value="">— Semua Status —</option>
                                <option value="active"   {{ request('status') === 'active'   ? 'selected' : '' }}>Aktif</option>
                                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                                <option value="resigned" {{ request('status') === 'resigned' ? 'selected' : '' }}>Resign</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <a href="{{ route('data-management.sdm-management.driver-data.index') }}"
                       class="btn btn-outline-secondary btn-sm">Reset</a>
                    <button type="submit" class="btn btn-primary btn-sm">Terapkan Filter</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
    @vite('resources/js/pages/driver.js')
@endpush
