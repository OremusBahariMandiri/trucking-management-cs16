@extends('layouts.admin')

@section('title', 'Jenis Kendaraan')

@section('content')

    {{-- ── Page Header ─────────────────────────────────────────── --}}
    <div class="veh-page-header mb-4">
        <div>
            <div class="veh-title">
                <span data-en="Vehicle Types">Jenis Kendaraan</span>
            </div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" data-en="Home">Beranda</a></li>
                    <li class="breadcrumb-item active" data-en="Vehicle Types">Jenis Kendaraan</li>
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
            <a href="{{ route('master-data.vehicle-types.create') }}"
                class="btn btn-primary btn-sm d-flex align-items-center gap-2">
                <i class="fas fa-plus"></i>Tambah
            </a>
        </div>
    </div>

    {{-- ── Table Card ───────────────────────────────────────────── --}}
    <div class="veh-table-card">
        <div class="veh-table-header">
            <h6><span data-en="Vehicle Type List">Daftar Jenis Kendaraan</span></h6>
            <small style="color:var(--text-faint)">
                {{ $vehicleTypes->count() }} <span data-en="records">data</span>
            </small>
        </div>

        @if ($vehicleTypes->isEmpty())
            <div class="veh-empty">
                <div class="veh-empty-icon"><i class="fas fa-tags"></i></div>
                <h6 data-en="No types found">Belum ada jenis kendaraan</h6>
                <p data-en="Add the first vehicle type.">Tambah jenis kendaraan pertama.</p>
            </div>
        @else
            {{-- Desktop table --}}
            <div class="lkp-desktop-table">
                <div class="table-responsive">
                    <table id="vehicleTypeTable" class="table" data-lookup-table data-col-targets='[0, 5]' style="width:100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Deskripsi</th>
                                <th>Status</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($vehicleTypes as $item)
                                <tr data-row="{{ $loop->index }}">
                                    <td></td>
                                    <td>{{ $item->code ?: '—' }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ Str::limit($item->description, 60) ?: '—' }}</td>
                                    <td>{{ $item->is_active ? 'Aktif' : 'Tidak Aktif' }}</td>
                                    <td>
                                        <div class="d-flex justify-content-end gap-1">
                                            <a href="{{ route('master-data.vehicle-types.edit', $item) }}"
                                                class="btn btn-sm btn-outline-primary" title="Edit">
                                                <i class="fas fa-pen"></i>
                                            </a>
                                            <form method="POST"
                                                action="{{ route('master-data.vehicle-types.destroy', $item) }}"
                                                class="js-delete-form"
                                                data-name="{{ $item->name }}">
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
                @foreach($vehicleTypes as $item)
                <div class="lkp-mob-card" data-row="{{ $loop->index }}">
                    <div class="lkp-mob-header">
                        <div class="lkp-mob-initial">{{ strtoupper(substr($item->name, 0, 1)) }}</div>
                        <div class="lkp-mob-name-wrap">
                            <div class="lkp-mob-name">{{ $item->name }}</div>
                            @if($item->code)
                            <div class="lkp-mob-sub">{{ $item->code }}</div>
                            @endif
                        </div>
                        <i class="fas fa-chevron-down lkp-mob-chevron"></i>
                    </div>
                    <div class="lkp-mob-details">
                        <div class="lkp-mob-detail-row">
                            <span class="lkp-mob-label">Kode</span>
                            <span class="lkp-mob-value">{{ $item->code ?: '—' }}</span>
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
                            <a href="{{ route('master-data.vehicle-types.edit', $item) }}"
                               class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-pen me-1"></i>Edit
                            </a>
                            <form method="POST" action="{{ route('master-data.vehicle-types.destroy', $item) }}"
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
                <form method="GET" action="{{ route('master-data.vehicle-types.index') }}">
                    <div class="modal-header">
                        <h6 class="modal-title" id="filterModalLabel">Filter Jenis Kendaraan</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Kata Kunci</label>
                                <input type="text" name="q" class="form-control"
                                       placeholder="Cari nama atau deskripsi…"
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
                        <a href="{{ route('master-data.vehicle-types.index') }}"
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
                    <h6 class="modal-title" id="exportModalLabel">Export Data Jenis Kendaraan</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted small mb-3">Export akan menyertakan filter yang sedang aktif.</p>
                    <div class="d-grid gap-2">
                        <a href="{{ route('master-data.vehicle-types.index') }}?{{ http_build_query(array_merge(request()->query(), ['export' => 'pdf'])) }}"
                           target="_blank"
                           class="btn btn-outline-danger d-flex align-items-center justify-content-center gap-2">
                            <i class="fas fa-file-pdf fa-lg"></i>
                            <div class="text-start">
                                <div class="fw-semibold">Export PDF</div>
                                <small class="text-muted">Format siap cetak</small>
                            </div>
                        </a>
                        <a href="{{ route('master-data.vehicle-types.index') }}?{{ http_build_query(array_merge(request()->query(), ['export' => 'excel'])) }}"
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
