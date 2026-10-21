@extends('layouts.admin')

@section('title', 'Merek Kendaraan')

@section('content')

<div class="veh-page-header mb-4">
    <div>
        <div class="veh-title">
            <span data-en="Vehicle Brands">Merek Kendaraan</span>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" data-en="Home">Beranda</a></li>
                <li class="breadcrumb-item active" data-en="Vehicle Brands">Merek Kendaraan</li>
            </ol>
        </nav>
    </div>
    <div class="d-flex align-items-center gap-2">
        <button type="button" class="btn btn-secondary btn-sm d-flex align-items-center gap-2"
                data-bs-toggle="modal" data-bs-target="#filterModal">
            <i class="fas fa-filter"></i> <span data-en="Filter">Filter</span>
            @if(request()->hasAny(['q','status']))
                <span class="badge bg-primary rounded-pill">{{ collect(['q','status'])->filter(fn($k)=>request()->filled($k))->count() }}</span>
            @endif
        </button>
        <button type="button" class="btn btn-success btn-sm d-flex align-items-center gap-2"
                data-bs-toggle="modal" data-bs-target="#exportModal">
            <i class="fas fa-file-export"></i> <span data-en="Export">Export</span>
        </button>
        <a href="{{ route('master-data.vehicle-brands.create') }}"
           class="btn btn-primary btn-sm d-flex align-items-center gap-2">
            <i class="fas fa-plus"></i>
            <span data-en="Add Brand">Tambah</span>
        </a>
    </div>
</div>

{{-- Table --}}
<div class="veh-table-card">
    <div class="veh-table-header">
        <h6><span data-en="Brand List">Daftar Merek Kendaraan</span></h6>
        <small style="color:var(--text-faint)">
            {{ $vehicleBrands->count() }} <span data-en="records">data</span>
        </small>
    </div>

    @if($vehicleBrands->isEmpty())
    <div class="veh-empty">
        <div class="veh-empty-icon"><i class="fas fa-trademark"></i></div>
        <h6 data-en="No brands found">Belum ada merek kendaraan</h6>
        <p data-en="Add the first vehicle brand.">Tambah merek kendaraan pertama.</p>
    </div>
    @else

    {{-- Desktop table --}}
    <div class="lkp-desktop-table">
        <div class="table-responsive">
            <table id="vehicleBrandTable" class="table" data-lookup-table data-col-targets='[0, 7]' style="width:100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Nama Merek</th>
                        <th>Negara Asal</th>
                        <th>Jml Model</th>
                        <th>Deskripsi</th>
                        <th>Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($vehicleBrands as $item)
                    <tr data-row="{{ $loop->index }}">
                        <td></td>
                        <td>{{ $item->code ?: '—' }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->country_of_origin ?: '—' }}</td>
                        <td>{{ $item->vehicle_models_count ?? 0 }}</td>
                        <td>{{ Str::limit($item->description, 60) ?: '—' }}</td>
                        <td>{{ $item->is_active ? 'Aktif' : 'Tidak Aktif' }}</td>
                        <td>
                            <div class="d-flex justify-content-end gap-1">
                                <a href="{{ route('master-data.vehicle-brands.edit', $item) }}"
                                   class="btn btn-sm btn-outline-primary" title="Edit">
                                    <i class="fas fa-pen"></i>
                                </a>
                                <form method="POST" action="{{ route('master-data.vehicle-brands.destroy', $item) }}" class="js-delete-form" data-name="{{ $item->name }}">
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
        @foreach($vehicleBrands as $item)
        <div class="lkp-mob-card" data-row="{{ $loop->index }}">
            <div class="lkp-mob-header">
                <div class="lkp-mob-initial">{{ strtoupper(substr($item->name, 0, 1)) }}</div>
                <div class="lkp-mob-name-wrap">
                    <div class="lkp-mob-name">{{ $item->name }}</div>
                    <div class="lkp-mob-sub">
                        @if($item->country_of_origin){{ $item->country_of_origin }} · @endif{{ $item->vehicle_models_count ?? 0 }} Model
                    </div>
                </div>
                <i class="fas fa-chevron-down lkp-mob-chevron"></i>
            </div>
            <div class="lkp-mob-details">
                <div class="lkp-mob-detail-row">
                    <span class="lkp-mob-label">Kode</span>
                    <span class="lkp-mob-value">{{ $item->code ?: '—' }}</span>
                </div>
                <div class="lkp-mob-detail-row">
                    <span class="lkp-mob-label">Negara Asal</span>
                    <span class="lkp-mob-value">{{ $item->country_of_origin ?: '—' }}</span>
                </div>
                <div class="lkp-mob-detail-row">
                    <span class="lkp-mob-label">Jml Model</span>
                    <span class="lkp-mob-value">{{ $item->vehicle_models_count ?? 0 }}</span>
                </div>
                @if($item->description)
                <div class="lkp-mob-detail-row">
                    <span class="lkp-mob-label">Deskripsi</span>
                    <span class="lkp-mob-value">{{ Str::limit($item->description, 80) }}</span>
                </div>
                @endif
                <div class="lkp-mob-detail-row">
                    <span class="lkp-mob-label">Status</span>
                    <span class="lkp-mob-value">{{ $item->is_active ? 'Aktif' : 'Tidak Aktif' }}</span>
                </div>
                <div class="lkp-mob-actions">
                    <a href="{{ route('master-data.vehicle-brands.edit', $item) }}"
                       class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-pen me-1"></i>Edit
                    </a>
                    <form method="POST" action="{{ route('master-data.vehicle-brands.destroy', $item) }}"
                          class="js-delete-form" data-name="{{ $item->name }}">
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

{{-- ── Modal Filter ─────────────────────────────────────────── --}}
<div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="GET" action="{{ route('master-data.vehicle-brands.index') }}">
                <div class="modal-header">
                    <h6 class="modal-title" id="filterModalLabel">Filter Merek Kendaraan</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Kata Kunci</label>
                            <input type="text" name="q" class="form-control"
                                   placeholder="Cari nama atau negara asal…"
                                   value="{{ request('q') }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="">— Semua Status —</option>
                                <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Tidak Aktif</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <a href="{{ route('master-data.vehicle-brands.index') }}"
                       class="btn btn-outline-secondary btn-sm">Reset</a>
                    <button type="submit" class="btn btn-primary btn-sm">Terapkan Filter</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ── Modal Export ─────────────────────────────────────────── --}}
<div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exportModalLabel">Export Data Merek Kendaraan</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted small mb-3">Export akan menyertakan filter yang sedang aktif.</p>
                <div class="d-grid gap-2">
                    <a href="{{ route('master-data.vehicle-brands.index') }}?{{ http_build_query(array_merge(request()->query(), ['export' => 'pdf'])) }}"
                       target="_blank"
                       class="btn btn-outline-danger d-flex align-items-center justify-content-center gap-2">
                        <i class="fas fa-file-pdf fa-lg"></i>
                        <div class="text-start">
                            <div class="fw-semibold">Export PDF</div>
                            <small class="text-muted">Format siap cetak</small>
                        </div>
                    </a>
                    <a href="{{ route('master-data.vehicle-brands.index') }}?{{ http_build_query(array_merge(request()->query(), ['export' => 'excel'])) }}"
                       class="btn btn-outline-success d-flex align-items-center justify-content-center gap-2">
                        <i class="fas fa-file-excel fa-lg"></i>
                        <div class="text-start">
                            <div class="fw-semibold">Export Excel</div>
                            <small class="text-muted">Format spreadsheet (.xlsx)</small>
                        </div>
                    </a>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
    @vite('resources/js/pages/master-data.js')
@endpush
