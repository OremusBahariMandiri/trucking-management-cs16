@extends('layouts.admin')

@section('title', 'Konfigurasi Sumbu')

@section('content')

    <div class="veh-page-header mb-4">
        <div>
            <div class="veh-title">
                <span data-en="Axis Configurations">Konfigurasi Sumbu</span>
            </div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" data-en="Home">Beranda</a></li>
                    <li class="breadcrumb-item active" data-en="Axis Configurations">Konfigurasi Sumbu</li>
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
            <a href="{{ route('master-data.axis-configurations.create') }}"
                class="btn btn-primary btn-sm d-flex align-items-center gap-2">
                <i class="fas fa-plus"></i>
                <span data-en="Add Configuration">Tambah</span>
            </a>
        </div>
    </div>

    {{-- Table --}}
    <div class="veh-table-card">
        <div class="veh-table-header">
            <h6>
                <i class="fas fa-road"></i>
                <span data-en="Axis Configuration List">Daftar Konfigurasi Sumbu</span>
            </h6>
            <small style="color:var(--text-faint)">
                {{ $items->count() }} <span data-en="records">data</span>
            </small>
        </div>

        @if ($items->isEmpty())
            <div class="veh-empty">
                <div class="veh-empty-icon"><i class="fas fa-road"></i></div>
                <h6 data-en="No axis configurations found">Belum ada konfigurasi sumbu</h6>
                <p data-en="Add the first axis configuration.">Tambah konfigurasi sumbu pertama.</p>
            </div>
        @else
            <div class="lkp-desktop-table" id="lkpDesktopTable">
    <div class="table-responsive">
                <table id="axisConfigTable" class="table" data-lookup-table data-col-targets='[0, 5]' style="width:100%">
                    <thead>
                        <tr>
                            <th data-en="No" data-priority="6">No</th>
                            <th data-en="Code" data-priority="4">Kode</th>
                            <th data-en="Name" data-priority="1">Nama</th>
                            <th data-en="Description" data-priority="5">Deskripsi</th>
                            <th data-en="Status" data-priority="2">Status</th>
                            <th class="text-end" data-en="Actions" data-priority="1">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $item)
                            <tr>
                                <td></td>
                                <td>
                                    @if($item->code)
                                        {{ $item->code }}
                                    @else
                                        —
                                    @endif
                                </td>
                                <td>{{ $item->name }}</td>
                                <td>{{ Str::limit($item->description, 60) ?: '—' }}</td>
                                <td>
                                    <span data-en="{{ $item->is_active ? 'Active' : 'Inactive' }}">
                                            {{ $item->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-end gap-1">
                                        <a href="{{ route('master-data.axis-configurations.edit', $item) }}"
                                            class="btn btn-sm btn-outline-primary" title="Edit">
                                            <i class="fas fa-pen"></i>
                                        </a>
                                        <form method="POST" action="{{ route('master-data.axis-configurations.destroy', $item) }}" class="js-delete-form" data-name="{{ $item->name }}">
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
    <div class="lkp-mobile-list" id="lkpMobileList">
        @foreach($items as $item)
        <div class="lkp-mob-card">
            <div class="lkp-mob-main">
                <div class="lkp-mob-icon"><i class="fas fa-sliders"></i></div>
                <div class="lkp-mob-info">
                    <div class="lkp-mob-name">{{ $item->name }}</div>
                    @if($item->code)
                    <div class="lkp-mob-meta">
                        <span><i class="fas fa-hashtag fa-xs"></i> <span data-en="Code">Kode</span>: <strong>{{ $item->code }}</strong></span>
                    </div>
                    @endif
                    @if($item->description)
                    <div class="lkp-mob-desc">{{ Str::limit($item->description, 80) }}</div>
                    @endif
                    <div class="lkp-mob-status mt-1">
                        <span class="bt-status-{{ $item->is_active ? 'active' : 'inactive' }}">
                            <span data-en="{{ $item->is_active ? 'Active' : 'Inactive' }}">{{ $item->is_active ? 'Aktif' : 'Tidak Aktif' }}</span>
                        </span>
                    </div>
                </div>
            </div>
            <div class="lkp-mob-actions">
                <a href="{{ route('master-data.axis-configurations.edit', \$item) }}"
                   class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-pen me-1"></i><span data-en="Edit">Edit</span>
                </a>
                <form method="POST" action="{{ route('master-data.axis-configurations.destroy', \$item) }}"
                      class="js-delete-form" data-name="{{ $item->name }}">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
@endif
    </div>

    {{-- ── Modal Filter ─────────────────────────────────────────── --}}
    <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="GET" action="{{ route('master-data.axis-configurations.index') }}">
                    <div class="modal-header">
                        <h6 class="modal-title" id="filterModalLabel">
                            <i class="fas fa-filter me-2"></i>Filter Konfigurasi Sumbu
                        </h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label" data-en="Keyword">Kata Kunci</label>
                                <input type="text" name="q" class="form-control"
                                       placeholder="Cari kode atau nama…"
                                       value="{{ request('q') }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label" data-en="Status">Status</label>
                                <select name="status" class="form-select">
                                    <option value=""><span data-en="— All Status —">— Semua Status —</span></option>
                                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }} data-en="Active">Aktif</option>
                                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }} data-en="Inactive">Tidak Aktif</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <a href="{{ route('master-data.axis-configurations.index') }}"
                           class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-times me-1"></i><span data-en="Reset">Reset</span>
                        </a>
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="fas fa-search me-1"></i><span data-en="Apply Filter">Terapkan Filter</span>
                        </button>
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
                    <h6 class="modal-title" id="exportModalLabel">
                        <i class="fas fa-file-export me-2"></i>Export Data Konfigurasi Sumbu
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted small mb-3"><span data-en="Export will include active filters.">Export akan menyertakan filter yang sedang aktif.</span></p>
                    <div class="d-grid gap-2">
                        <a href="{{ route('master-data.axis-configurations.index') }}?{{ http_build_query(array_merge(request()->query(), ['export' => 'pdf'])) }}"
                           target="_blank"
                           class="btn btn-outline-danger d-flex align-items-center justify-content-center gap-2">
                            <i class="fas fa-file-pdf fa-lg"></i>
                            <div class="text-start">
                                <div class="fw-semibold" data-en="Export PDF">Export PDF</div>
                                <small class="text-muted" data-en="Print-ready format">Format siap cetak</small>
                            </div>
                        </a>
                        <a href="{{ route('master-data.axis-configurations.index') }}?{{ http_build_query(array_merge(request()->query(), ['export' => 'excel'])) }}"
                           class="btn btn-outline-success d-flex align-items-center justify-content-center gap-2">
                            <i class="fas fa-file-excel fa-lg"></i>
                            <div class="text-start">
                                <div class="fw-semibold" data-en="Export Excel">Export Excel</div>
                                <small class="text-muted" data-en="Spreadsheet format (.xlsx)">Format spreadsheet (.xlsx)</small>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal" data-en="Close">Tutup</button>
                </div>
            </div>
        </div>
    </div>



@endsection

@push('scripts')
    @vite('resources/js/pages/master-data.js')
@endpush
