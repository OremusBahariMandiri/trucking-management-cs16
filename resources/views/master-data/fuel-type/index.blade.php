@extends('layouts.admin')

@section('title', 'Jenis Bahan Bakar')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        .dt-top-bar {
            display: flex; align-items: center; justify-content: space-between;
            flex-wrap: wrap; gap: .5rem;
            padding: .75rem 1.25rem;
            background: var(--surface); border-bottom: 1px solid var(--border);
        }
        .dt-top-bar .dt-length label,
        .dt-top-bar .dt-search label { color: var(--text-muted); font-size: .82rem; }
        .dt-top-bar .dt-length select {
            display: inline-block; width: auto;
            background: var(--surface-2); color: var(--text);
            border: 1px solid var(--border); border-radius: 6px;
            padding: .25rem .5rem; font-size: .82rem; margin: 0 .25rem;
        }
        .dt-top-bar .dt-search input {
            background: var(--surface-2); color: var(--text);
            border: 1px solid var(--border); border-radius: 6px;
            padding: .25rem .6rem; font-size: .82rem; width: 200px;
        }
        .dt-top-bar .dt-search input:focus {
            outline: none; border-color: var(--veh-accent);
            box-shadow: 0 0 0 2px var(--veh-accent-soft);
        }
        .dt-bottom-bar {
            display: flex; align-items: center; justify-content: space-between;
            flex-wrap: wrap; gap: .5rem;
            padding: .75rem 1.25rem;
            background: var(--surface); border-top: 1px solid var(--border);
        }
        .dt-bottom-bar .dt-info { color: var(--text-muted); font-size: .82rem; }
        .dt-bottom-bar .dt-paging .pagination { margin: 0; }
        .dt-bottom-bar .dt-paging .page-link {
            background: var(--surface-2); border-color: var(--border);
            color: var(--text); font-size: .82rem; padding: .25rem .55rem;
        }
        .dt-bottom-bar .dt-paging .page-item.active .page-link {
            background: var(--veh-accent); border-color: var(--veh-accent); color: #fff;
        }
        .dt-bottom-bar .dt-paging .page-link:hover { background: var(--veh-accent-soft); }
    </style>
@endpush

@section('content')

<div class="veh-page-header mb-4">
    <div>
        <div class="veh-title">
            <span data-en="Fuel Types">Jenis Bahan Bakar</span>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" data-en="Home">Beranda</a></li>
                <li class="breadcrumb-item active" data-en="Fuel Types">Jenis Bahan Bakar</li>
            </ol>
        </nav>
    </div>
    <div class="d-flex align-items-center gap-2">
        <button type="button" class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-2"
                data-bs-toggle="modal" data-bs-target="#filterModal">
            <i class="fas fa-filter"></i> <span data-en="Filter">Filter</span>
            @if(request()->hasAny(['q','status']))
                <span class="badge bg-primary rounded-pill">{{ collect(['q','status'])->filter(fn($k)=>request()->filled($k))->count() }}</span>
            @endif
        </button>
        <button type="button" class="btn btn-outline-success btn-sm d-flex align-items-center gap-2"
                data-bs-toggle="modal" data-bs-target="#exportModal">
            <i class="fas fa-file-export"></i> <span data-en="Export">Export</span>
        </button>
            <a href="{{ route('master-data.fuel-types.create') }}"
           class="btn btn-primary btn-sm d-flex align-items-center gap-2">
            <i class="fas fa-plus"></i>
            <span data-en="Add Fuel Type">Tambah</span>
        </a>
    </div>
</div>

{{-- Table --}}
<div class="veh-table-card">
    <div class="veh-table-header">
        <h6><i class="fas fa-gas-pump"></i>
            <span data-en="Fuel Type List">Daftar Jenis Bahan Bakar</span>
        </h6>
        <small style="color:var(--text-faint)">
            {{ $fuelTypes->count() }} <span data-en="records">data</span>
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
        <table id="fuelTypeTable" class="table" style="width:100%">
            <thead>
                <tr>
                    <th data-en="No">No</th>
                    <th data-en="Code">Kode</th>
                    <th data-en="Name">Nama</th>
                    <th data-en="Description">Deskripsi</th>
                    <th data-en="Status">Status</th>
                    <th class="text-end" data-en="Actions">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($fuelTypes as $item)
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
                    <td style="font-size:.85rem;color:var(--text-muted)">{{ $item->description ?: '—' }}</td>
                    <td>
                        <span data-en="{{ $item->is_active ? 'Active' : 'Inactive' }}">
                                {{ $item->is_active ? 'Aktif' : 'Tidak Aktif' }}
                        </span>
                    </td>
                    <td>
                        <div class="d-flex justify-content-end gap-1">
                            <a href="{{ route('master-data.fuel-types.edit', $item) }}"
                               class="btn btn-sm btn-outline-primary" title="Edit">
                                <i class="fas fa-pen"></i>
                            </a>
                            <form method="POST" action="{{ route('master-data.fuel-types.destroy', $item) }}" class="js-delete-form" data-name="{{ $item->name }}">
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
@endif
</div>

    {{-- ── Modal Filter ─────────────────────────────────────────── --}}
    <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="GET" action="{{ route('master-data.fuel-types.index') }}">
                    <div class="modal-header">
                        <h6 class="modal-title" id="filterModalLabel">
                            <i class="fas fa-filter me-2"></i>Filter Bahan Bakar
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
                        <a href="{{ route('master-data.fuel-types.index') }}"
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
                        <i class="fas fa-file-export me-2"></i>Export Data Bahan Bakar
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted small mb-3"><span data-en="Export will include active filters.">Export akan menyertakan filter yang sedang aktif.</span></p>
                    <div class="d-grid gap-2">
                        <a href="{{ route('master-data.fuel-types.index') }}?{{ http_build_query(array_merge(request()->query(), ['export' => 'pdf'])) }}"
                           target="_blank"
                           class="btn btn-outline-danger d-flex align-items-center justify-content-center gap-2">
                            <i class="fas fa-file-pdf fa-lg"></i>
                            <div class="text-start">
                                <div class="fw-semibold" data-en="Export PDF">Export PDF</div>
                                <small class="text-muted" data-en="Print-ready format">Format siap cetak</small>
                            </div>
                        </a>
                        <a href="{{ route('master-data.fuel-types.index') }}?{{ http_build_query(array_merge(request()->query(), ['export' => 'excel'])) }}"
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


@push('scripts')
<script>
    document.querySelectorAll('.js-delete-form').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            var name = form.dataset.name || 'data ini';
            var isEn = (typeof lang !== 'undefined' ? lang : (localStorage.getItem('lang')||'id')) === 'en';
            truckConfirm({
                text: isEn
                    ? 'Delete fuel type <strong>' + name + '</strong>? This action cannot be undone.'
                    : 'Hapus tipe bahan bakar <strong>' + name + '</strong>? Tindakan ini tidak dapat dibatalkan.',
            }).then(function(result) {
                if (result.isConfirmed) form.submit();
            });
        });
    });
</script>
@endpush
@endsection

@push('scripts')
<script>
$(document).ready(function () {
    $('#fuelTypeTable').DataTable({
        pageLength: 10,
        lengthMenu: [[5, 10, 25, 50, -1], ['5', '10', '25', '50', 'Semua']],
        dom: '<"dt-top-bar"lf>rt<"dt-bottom-bar"ip>',
        language: {
            lengthMenu:        'Tampilkan _MENU_ data',
            zeroRecords:       'Tidak ada data ditemukan',
            info:              'Menampilkan _START_\u2013_END_ dari _TOTAL_ data',
            infoEmpty:         'Menampilkan 0 dari 0 data',
            infoFiltered:      '(difilter dari _MAX_ total data)',
            search:            '',
            searchPlaceholder: 'Cari...',
            paginate: { first: '\u00ab', last: '\u00bb', next: '\u203a', previous: '\u2039' }
        },
        columnDefs: [
            { orderable: false, targets: [0, 5] }
        ],
        order: [[2, 'asc']],
        drawCallback: function(settings) {
            var api = this.api();
            var start = api.context[0]._iDisplayStart;
            api.column(0, {search:'applied', order:'applied'}).nodes().each(function(cell, i) {
                cell.innerHTML = start + i + 1;
            });
        },
        responsive: true,
        autoWidth: false,
    });
});
</script>
@endpush

