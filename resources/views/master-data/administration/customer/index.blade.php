@extends('layouts.admin')
@section('title', 'Pelanggan')
@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        /* ── DataTable bars ── */
        .dt-top-bar {
            display: flex; align-items: center; justify-content: space-between;
            padding: .75rem 1.25rem; border-bottom: 1px solid var(--border);
            background: var(--surface-2); gap: .75rem; flex-wrap: wrap;
        }
        .dt-top-bar .dataTables_length { display: flex; align-items: center; gap: .5rem; }
        .dt-top-bar .dataTables_length label,
        .dt-top-bar .dataTables_filter label {
            display: flex; align-items: center; gap: .5rem;
            font-size: .8rem; color: var(--text-muted); margin: 0; white-space: nowrap;
        }
        .dt-top-bar .dataTables_length select,
        .dt-top-bar .dataTables_filter input {
            height: 30px; padding: .3rem .75rem; font-size: .8rem;
            background-color: var(--surface) !important; color: var(--text) !important;
            border: 1px solid var(--border) !important; border-radius: 6px;
        }
        .dt-top-bar .dataTables_length select { width: auto; }
        .dt-top-bar .dataTables_filter input { min-width: 180px; }
        .dt-top-bar .dataTables_length select:focus,
        .dt-top-bar .dataTables_filter input:focus {
            border-color: var(--veh-accent) !important;
            box-shadow: 0 0 0 2px var(--veh-accent-soft) !important; outline: none;
        }
        .dt-top-bar .dataTables_filter input::placeholder { color: var(--text-faint); }
        .dt-bottom-bar {
            display: flex; align-items: center; justify-content: space-between;
            padding: .75rem 1.25rem; border-top: 1px solid var(--border);
            background: var(--surface-2); flex-wrap: wrap; gap: .5rem;
        }
        .dt-bottom-bar .dataTables_info { font-size: .78rem; color: var(--text-faint); padding: 0; }
        .dt-bottom-bar .dataTables_paginate { display: flex; gap: 3px; }
        .dt-bottom-bar .dataTables_paginate .paginate_button {
            display: inline-flex !important; align-items: center; justify-content: center;
            min-width: 30px; height: 30px; padding: 0 8px !important; font-size: .78rem !important;
            border-radius: 6px !important; border: 1px solid var(--border) !important;
            background: var(--surface) !important; color: var(--text-muted) !important;
            cursor: pointer; transition: all .15s; line-height: 1 !important;
        }
        .dt-bottom-bar .dataTables_paginate .paginate_button:hover {
            border-color: var(--veh-accent) !important; color: var(--veh-accent) !important;
        }
        .dt-bottom-bar .dataTables_paginate .paginate_button.current,
        .dt-bottom-bar .dataTables_paginate .paginate_button.current:hover {
            background: var(--veh-accent) !important; border-color: var(--veh-accent) !important; color: #fff !important;
        }
        .dt-bottom-bar .dataTables_paginate .paginate_button.disabled,
        .dt-bottom-bar .dataTables_paginate .paginate_button.disabled:hover {
            opacity: .35; cursor: not-allowed; color: var(--text-faint) !important;
            border-color: var(--border) !important; background: var(--surface-2) !important;
        }
        /* status badges */
        .cust-status-active,.cust-status-inactive {
            display: inline-flex; align-items: center; gap: .3em;
            padding: .25em .7em; border-radius: 100px; font-size: .7rem; font-weight: 600;
        }
        .cust-status-active { color:#16a34a; background:#dcfce7; }
        .cust-status-inactive { color:#64748b; background:#f1f5f9; }
        .cust-status-active::before,.cust-status-inactive::before {
            content:''; width:5px; height:5px; border-radius:50%; background:currentColor;
        }
        :root[data-theme="dark"] .cust-status-active { color:#4ade80; background:rgba(22,163,74,.2); }
        :root[data-theme="dark"] .cust-status-inactive { color:#94a3b8; background:rgba(100,116,139,.2); }
        @media (prefers-color-scheme:dark) {
            :root:not([data-theme="light"]) .cust-status-active { color:#4ade80; background:rgba(22,163,74,.2); }
            :root:not([data-theme="light"]) .cust-status-inactive { color:#94a3b8; background:rgba(100,116,139,.2); }
        }
        .cust-name-link { font-weight:600; color:var(--text); text-decoration:none; transition:color .15s; }
        .cust-name-link:hover { color:var(--veh-accent); }
        .cust-code { font-size:.75rem; color:var(--text-faint); font-family:ui-monospace,monospace; }
    </style>
@endpush

@section('content')
@php
    $canCreate = auth()->user()->hasPermission('master-data.administration.customers.create');
    $canEdit   = auth()->user()->hasPermission('master-data.administration.customers.edit');
    $canDelete = auth()->user()->hasPermission('master-data.administration.customers.delete');
    $canShow   = auth()->user()->hasPermission('master-data.administration.customers.show');
@endphp

<div class="veh-page-header mb-4">
    <div>
        <div class="veh-title">
            <i class="fas fa-users"></i>
            <span data-en="Customers">Pelanggan</span>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" data-en="Home">Beranda</a></li>
                <li class="breadcrumb-item" data-en="Master Data">Data Master</li>
                <li class="breadcrumb-item" data-en="Administration">Administrasi</li>
                <li class="breadcrumb-item active" data-en="Customers">Pelanggan</li>
            </ol>
        </nav>
    </div>
    <div class="d-flex align-items-center gap-2">
        <button type="button" class="btn btn-secondary btn-sm d-flex align-items-center gap-2"
            data-bs-toggle="modal" data-bs-target="#filterModal">
            <i class="fas fa-filter"></i>
            <span data-en="Filter">Filter</span>
            @if(request()->hasAny(['q','status','customer_type_id','business_type_id']))
                <span class="badge bg-primary rounded-pill">
                    {{ collect(['q','status','customer_type_id','business_type_id'])->filter(fn($k) => request()->filled($k))->count() }}
                </span>
            @endif
        </button>
        @if($canCreate)
        <a href="{{ route('master-data.administration.customers.create') }}"
           class="btn btn-primary btn-sm d-flex align-items-center gap-2">
            <i class="fas fa-plus"></i>
            <span data-en="Add">Tambah</span>
        </a>
        @else
        <button type="button" class="btn btn-primary btn-sm d-flex align-items-center gap-2" disabled
                title="Anda tidak memiliki akses untuk menambah data">
            <i class="fas fa-plus"></i> <span>Tambah</span>
        </button>
        @endif
    </div>
</div>

<div class="veh-table-card">
    <div class="veh-table-header">
        <span class="d-flex align-items-center gap-2">
            <i class="fas fa-list-ul" style="color:var(--veh-accent);font-size:.85em"></i>
            <span data-en="Customer List">Daftar Pelanggan</span>
        </span>
        <small style="color:var(--text-faint)">{{ count($customers) }} <span data-en="records">data</span></small>
    </div>

    @if(empty($customers) || count($customers) === 0)
        <div class="p-4 text-center" style="color:var(--text-muted)">
            <i class="fas fa-users fa-2x mb-2 d-block" style="opacity:.3"></i>
            Belum ada data pelanggan.
        </div>
    @else

    {{-- Desktop --}}
    <div class="lkp-desktop-table">
        <div class="table-responsive">
            <table id="customerTable" class="table mb-0 w-100">
                <thead>
                    <tr>
                        <th style="width:50px">No</th>
                        <th data-en="Name">Nama</th>
                        <th data-en="Customer Type">Jenis</th>
                        <th data-en="City">Kota</th>
                        <th data-en="PIC">PIC</th>
                        <th data-en="Status">Status</th>
                        <th class="text-end" style="width:90px" data-en="Actions">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($customers as $i => $item)
                    <tr data-row="{{ $loop->index }}">
                        <td class="text-center" style="color:var(--text-faint);font-size:.8rem"></td>
                        <td>
                            @if($canShow)
                            <a href="{{ route('master-data.administration.customers.show', $item) }}"
                               class="cust-name-link">{{ $item->name }}</a>
                            @else
                            <span class="cust-name-link" style="cursor:default">{{ $item->name }}</span>
                            @endif
                            @if($item->customer_code)
                                <span class="d-block cust-code">{{ $item->customer_code }}</span>
                            @endif
                        </td>
                        <td style="font-size:.82rem;color:var(--text-muted)">{{ $item->customerType?->name ?: '—' }}</td>
                        <td style="font-size:.82rem;color:var(--text-muted)">{{ $item->city ?: '—' }}</td>
                        <td style="font-size:.82rem;color:var(--text-muted)">{{ $item->primaryPic?->name ?: '—' }}</td>
                        <td>
                            @if($item->is_active)
                                <span class="cust-status-active" data-en="Active">Aktif</span>
                            @else
                                <span class="cust-status-inactive" data-en="Inactive">Tidak Aktif</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex justify-content-end gap-1">
                                @if($canShow)
                                <a href="{{ route('master-data.administration.customers.show', $item) }}"
                                   class="btn btn-sm btn-outline-info" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @else
                                <button type="button" class="btn btn-sm btn-outline-info" disabled title="Tidak ada akses lihat detail"><i class="fas fa-eye"></i></button>
                                @endif
                                @if($canEdit)
                                <a href="{{ route('master-data.administration.customers.edit', $item) }}"
                                   class="btn btn-sm btn-outline-primary" title="Edit">
                                    <i class="fas fa-pen"></i>
                                </a>
                                @else
                                <button type="button" class="btn btn-sm btn-outline-primary" disabled title="Tidak ada akses edit"><i class="fas fa-pen"></i></button>
                                @endif
                                @if($canDelete)
                                <form method="POST"
                                      action="{{ route('master-data.administration.customers.destroy', $item) }}"
                                      class="js-delete-form" data-name="{{ $item->name }}">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @else
                                <button type="button" class="btn btn-sm btn-outline-danger" disabled title="Tidak ada akses hapus"><i class="fas fa-trash"></i></button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Mobile --}}
    <div class="lkp-mobile-list" id="custMobileList">
        @foreach($customers as $item)
        <div class="lkp-mob-card" data-row="{{ $loop->index }}">
            <div class="lkp-mob-header">
                <div class="lkp-mob-initial">{{ strtoupper(substr($item->name, 0, 1)) }}</div>
                <div class="lkp-mob-name-wrap">
                    <div class="lkp-mob-name">{{ $item->name }}</div>
                    @if($item->customer_code)
                    <div class="lkp-mob-sub">{{ $item->customer_code }}</div>
                    @endif
                </div>
                <i class="fas fa-chevron-down lkp-mob-chevron"></i>
            </div>
            <div class="lkp-mob-details">
                <div class="lkp-mob-detail-row">
                    <span class="lkp-mob-label">Jenis</span>
                    <span class="lkp-mob-value">{{ $item->customerType?->name ?: '—' }}</span>
                </div>
                <div class="lkp-mob-detail-row">
                    <span class="lkp-mob-label">Kota</span>
                    <span class="lkp-mob-value">{{ $item->city ?: '—' }}</span>
                </div>
                <div class="lkp-mob-detail-row">
                    <span class="lkp-mob-label">PIC</span>
                    <span class="lkp-mob-value">{{ $item->primaryPic?->name ?: '—' }}</span>
                </div>
                <div class="lkp-mob-detail-row">
                    <span class="lkp-mob-label">Status</span>
                    <span class="lkp-mob-value">{{ $item->is_active ? 'Aktif' : 'Tidak Aktif' }}</span>
                </div>
                <div class="lkp-mob-actions">
                    @if($canShow)
                    <a href="{{ route('master-data.administration.customers.show', $item) }}"
                       class="btn btn-sm btn-outline-info">
                        <i class="fas fa-eye me-1"></i>Detail
                    </a>
                    @else
                    <button type="button" class="btn btn-sm btn-outline-info" disabled title="Tidak ada akses lihat detail"><i class="fas fa-eye"></i></button>
                    @endif
                    @if($canEdit)
                    <a href="{{ route('master-data.administration.customers.edit', $item) }}"
                       class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-pen me-1"></i>Edit
                    </a>
                    @else
                    <button type="button" class="btn btn-sm btn-outline-primary" disabled title="Tidak ada akses edit"><i class="fas fa-pen me-1"></i>Edit</button>
                    @endif
                    @if($canDelete)
                    <form method="POST"
                          action="{{ route('master-data.administration.customers.destroy', $item) }}"
                          class="js-delete-form" data-name="{{ $item->name }}">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                    @else
                    <button type="button" class="btn btn-sm btn-outline-danger" disabled title="Tidak ada akses hapus"><i class="fas fa-trash"></i></button>
                    @endif
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
    </div>

    @endif
</div>

{{-- Filter Modal --}}
<div class="modal fade" id="filterModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="GET" action="{{ route('master-data.administration.customers.index') }}">
                <div class="modal-header">
                    <h6 class="modal-title"><i class="fas fa-filter me-2"></i><span data-en="Filter Customers">Filter Pelanggan</span></h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label" data-en="Keyword">Kata Kunci</label>
                            <input type="text" name="q" class="form-control"
                                   placeholder="Cari nama atau kode…" value="{{ request('q') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" data-en="Customer Type">Jenis Pelanggan</label>
                            <select name="customer_type_id" class="form-select">
                                <option value="" data-en="— All —">— Semua —</option>
                                @foreach($customerTypes as $ct)
                                    <option value="{{ $ct->id }}" {{ request('customer_type_id') == $ct->id ? 'selected' : '' }}>{{ $ct->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" data-en="Business Type">Jenis Usaha</label>
                            <select name="business_type_id" class="form-select">
                                <option value="" data-en="— All —">— Semua —</option>
                                @foreach($businessTypes as $bt)
                                    <option value="{{ $bt->id }}" {{ request('business_type_id') == $bt->id ? 'selected' : '' }}>{{ $bt->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label" data-en="Status">Status</label>
                            <select name="status" class="form-select">
                                <option value="" data-en="— All Status —">— Semua Status —</option>
                                <option value="1" {{ request('status') === '1' ? 'selected' : '' }} data-en="Active">Aktif</option>
                                <option value="0" {{ request('status') === '0' ? 'selected' : '' }} data-en="Inactive">Tidak Aktif</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <a href="{{ route('master-data.administration.customers.index') }}"
                       class="btn btn-outline-secondary btn-sm"><i class="fas fa-times me-1"></i>Reset</a>
                    <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search me-1"></i>Terapkan Filter</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function () {
    function syncMobileCards(api) {
        var info  = api.page.info();
        var start = info.start, end = info.end;
        document.querySelectorAll('#custMobileList .lkp-mob-card').forEach(function (card) {
            var idx = parseInt(card.dataset.row, 10);
            card.style.display = (idx >= start && idx < end) ? '' : 'none';
        });
        var infoEl = document.querySelector('#custMobileList .lkp-mob-pagination-info');
        if (infoEl) infoEl.textContent = info.recordsTotal
            ? 'Menampilkan ' + (start + 1) + '–' + Math.min(end, info.recordsDisplay) + ' dari ' + info.recordsDisplay + ' data'
            : 'Tidak ada data';
        var pageNum = document.querySelector('#custMobileList .lkp-mob-page-num');
        if (pageNum) pageNum.textContent = (info.page + 1) + ' / ' + info.pages;
        var prev = document.querySelector('#custMobileList .lkp-mob-prev');
        var next = document.querySelector('#custMobileList .lkp-mob-next');
        if (prev) prev.disabled = (info.page === 0);
        if (next) next.disabled = (info.page >= info.pages - 1);
    }

    var table = $('#customerTable').DataTable({
        pageLength: 10,
        lengthMenu: [[5,10,25,50,-1],['5','10','25','50','Semua']],
        dom: '<"dt-top-bar"lf>rt<"dt-bottom-bar"ip>',
        language: {
            lengthMenu: 'Tampilkan _MENU_ data',
            zeroRecords: 'Tidak ada data ditemukan',
            info: 'Menampilkan _START_–_END_ dari _TOTAL_ data',
            infoEmpty: 'Tidak ada data',
            infoFiltered: '(dari _MAX_ total)',
            search: '',
            searchPlaceholder: 'Cari pelanggan...',
            paginate: { first:'«', last:'»', next:'›', previous:'‹' }
        },
        columnDefs: [{ orderable: false, targets: [0, 6] }],
        order: [[1, 'asc']],
        autoWidth: false,
        drawCallback: function () {
            var api = this.api();
            var start = api.context[0]._iDisplayStart;
            api.column(0, { search:'applied', order:'applied' }).nodes().each(function (cell, i) {
                cell.innerHTML = start + i + 1;
            });
            syncMobileCards(api);
        }
    });

    // Mobile prev/next
    document.querySelector('#custMobileList .lkp-mob-prev')?.addEventListener('click', function () { table.page('previous').draw('page'); });
    document.querySelector('#custMobileList .lkp-mob-next')?.addEventListener('click', function () { table.page('next').draw('page'); });

    // Mobile expand/collapse
    document.querySelectorAll('#custMobileList .lkp-mob-header').forEach(function (header) {
        header.addEventListener('click', function () {
            var card    = header.closest('.lkp-mob-card');
            var details = card.querySelector('.lkp-mob-details');
            var chevron = card.querySelector('.lkp-mob-chevron');
            var open    = details.style.display !== 'none' && details.style.display !== '';
            details.style.display = open ? 'none' : 'block';
            if (chevron) chevron.style.transform = open ? '' : 'rotate(180deg)';
        });
    });
});

// Delete confirmation
document.querySelectorAll('.js-delete-form').forEach(function (form) {
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        var name = form.dataset.name || 'data ini';
        if (confirm('Hapus pelanggan "' + name + '"? Tindakan ini tidak dapat dibatalkan.')) form.submit();
    });
});
</script>
@endpush
