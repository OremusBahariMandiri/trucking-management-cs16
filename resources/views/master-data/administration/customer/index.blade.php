@extends('layouts.admin')
@section('title', 'Pelanggan')
@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        /* ── DataTable integrated into veh-table-card ── */
        #customerTable_wrapper .dt-top-bar,
        .dt-top-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: .75rem 1.25rem;
            border-bottom: 1px solid var(--border);
            background: var(--surface-2);
            gap: .75rem;
            flex-wrap: wrap;
        }

        .dt-top-bar .dataTables_length {
            display: flex;
            align-items: center;
            gap: .5rem;
        }

        .dt-top-bar .dataTables_length label {
            display: flex;
            align-items: center;
            gap: .5rem;
            font-size: .8rem;
            color: var(--text-muted);
            margin: 0;
            white-space: nowrap;
        }

        .dt-top-bar .dataTables_length select {
            width: auto;
            padding: .3rem .75rem;
            font-size: .8rem;
            height: 30px;
            background-color: var(--surface) !important;
            color: var(--text) !important;
            border: 1px solid var(--border) !important;
            border-radius: 6px;
        }

        .dt-top-bar .dataTables_length select:focus {
            border-color: var(--veh-accent) !important;
            box-shadow: 0 0 0 2px var(--veh-accent-soft) !important;
            outline: none;
        }

        .dt-top-bar .dataTables_filter label {
            display: flex;
            align-items: center;
            gap: .5rem;
            font-size: .8rem;
            color: var(--text-muted);
            margin: 0;
        }

        .dt-top-bar .dataTables_filter input {
            height: 30px;
            padding: .3rem .75rem;
            font-size: .8rem;
            background-color: var(--surface) !important;
            color: var(--text) !important;
            border: 1px solid var(--border) !important;
            border-radius: 6px;
            min-width: 180px;
        }

        .dt-top-bar .dataTables_filter input:focus {
            border-color: var(--veh-accent) !important;
            box-shadow: 0 0 0 2px var(--veh-accent-soft) !important;
            outline: none;
        }

        .dt-top-bar .dataTables_filter input::placeholder {
            color: var(--text-faint);
        }

        /* ── bottom bar ── */
        .dt-bottom-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: .75rem 1.25rem;
            border-top: 1px solid var(--border);
            background: var(--surface-2);
            flex-wrap: wrap;
            gap: .5rem;
        }

        .dt-bottom-bar .dataTables_info {
            font-size: .78rem;
            color: var(--text-faint);
            padding: 0;
        }

        .dt-bottom-bar .dataTables_paginate {
            display: flex;
            gap: 3px;
        }

        .dt-bottom-bar .dataTables_paginate .paginate_button {
            display: inline-flex !important;
            align-items: center;
            justify-content: center;
            min-width: 30px;
            height: 30px;
            padding: 0 8px !important;
            font-size: .78rem !important;
            border-radius: 6px !important;
            border: 1px solid var(--border) !important;
            background: var(--surface) !important;
            color: var(--text-muted) !important;
            cursor: pointer;
            transition: all .15s;
            line-height: 1 !important;
        }

        .dt-bottom-bar .dataTables_paginate .paginate_button:hover {
            background: var(--surface) !important;
            border-color: var(--veh-accent) !important;
            color: var(--veh-accent) !important;
        }

        .dt-bottom-bar .dataTables_paginate .paginate_button.current,
        .dt-bottom-bar .dataTables_paginate .paginate_button.current:hover {
            background: var(--veh-accent) !important;
            border-color: var(--veh-accent) !important;
            color: #fff !important;
        }

        .dt-bottom-bar .dataTables_paginate .paginate_button.disabled,
        .dt-bottom-bar .dataTables_paginate .paginate_button.disabled:hover {
            opacity: .35;
            cursor: not-allowed;
            color: var(--text-faint) !important;
            border-color: var(--border) !important;
            background: var(--surface-2) !important;
        }

        /* ── status badge ── */
        .cust-status-active {
            display: inline-flex;
            align-items: center;
            gap: .3em;
            padding: .25em .7em;
            border-radius: 100px;
            font-size: .7rem;
            font-weight: 600;
            color: #16a34a;
            background: #dcfce7;
        }

        .cust-status-inactive {
            display: inline-flex;
            align-items: center;
            gap: .3em;
            padding: .25em .7em;
            border-radius: 100px;
            font-size: .7rem;
            font-weight: 600;
            color: #64748b;
            background: #f1f5f9;
        }

        .cust-status-active::before,
        .cust-status-inactive::before {
            content: '';
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: currentColor;
        }

        @media (prefers-color-scheme: dark) {
            :root:not([data-theme="light"]) .cust-status-active {
                color: #4ade80;
                background: rgba(22,163,74,.2);
            }
            :root:not([data-theme="light"]) .cust-status-inactive {
                color: #94a3b8;
                background: rgba(100,116,139,.2);
            }
        }

        :root[data-theme="dark"] .cust-status-active {
            color: #4ade80;
            background: rgba(22,163,74,.2);
        }

        :root[data-theme="dark"] .cust-status-inactive {
            color: #94a3b8;
            background: rgba(100,116,139,.2);
        }

        /* ── customer name link ── */
        .cust-name-link {
            font-weight: 600;
            color: var(--text);
            text-decoration: none;
            transition: color .15s;
        }

        .cust-name-link:hover {
            color: var(--veh-accent);
        }

        .cust-code {
            font-size: .75rem;
            color: var(--text-faint);
            font-family: ui-monospace, monospace;
        }
    </style>
@endpush
@section('content')

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
                @if (request()->hasAny(['q', 'status', 'customer_type_id', 'business_type_id']))
                    <span class="badge bg-primary rounded-pill">
                        {{ collect(['q', 'status', 'customer_type_id', 'business_type_id'])->filter(fn($k) => request()->filled($k))->count() }}
                    </span>
                @endif
            </button>
            <a href="{{ route('master-data.administration.customers.create') }}"
                class="btn btn-primary btn-sm d-flex align-items-center gap-2">
                <i class="fas fa-plus"></i>
                <span data-en="Add">Tambah</span>
            </a>
        </div>
    </div>

    @include('components.alert')

    <div class="veh-table-card">
        {{-- Header card --}}
        <div class="veh-table-header">
            <span class="d-flex align-items-center gap-2">
                <i class="fas fa-list-ul" style="color:var(--veh-accent);font-size:.85em"></i>
                <span data-en="Customer List">Daftar Pelanggan</span>
            </span>
            <small style="color:var(--text-faint)">
                {{ count($customers) }}
                <span data-en="records">data</span>
            </small>
        </div>

        {{-- DataTable --}}
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
                    @foreach ($customers as $i => $item)
                        <tr>
                            <td class="text-center" style="color:var(--text-faint);font-size:.8rem">
                                {{ $i + 1 }}
                            </td>
                            <td>
                                <a href="{{ route('master-data.administration.customers.show', $item) }}"
                                    class="cust-name-link">
                                    {{ $item->name }}
                                </a>
                                @if ($item->customer_code)
                                    <span class="d-block cust-code">{{ $item->customer_code }}</span>
                                @endif
                            </td>
                            <td style="font-size:.82rem;color:var(--text-muted)">
                                {{ $item->customerType?->name ?: '—' }}
                            </td>
                            <td style="font-size:.82rem;color:var(--text-muted)">
                                {{ $item->city ?: '—' }}
                            </td>
                            <td style="font-size:.82rem;color:var(--text-muted)">
                                {{ $item->primaryPic?->name ?: '—' }}
                            </td>
                            <td>
                                @if ($item->is_active)
                                    <span class="cust-status-active" data-en="Active">Aktif</span>
                                @else
                                    <span class="cust-status-inactive" data-en="Inactive">Tidak Aktif</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex justify-content-end gap-1">
                                    <a href="{{ route('master-data.administration.customers.show', $item) }}"
                                        class="btn btn-sm btn-outline-info" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('master-data.administration.customers.edit', $item) }}"
                                        class="btn btn-sm btn-outline-primary" title="Edit">
                                        <i class="fas fa-pen"></i>
                                    </a>
                                    <form method="POST"
                                        action="{{ route('master-data.administration.customers.destroy', $item) }}"
                                        onsubmit="return confirm('Hapus pelanggan ini?')">
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
    </div>

    {{-- Filter Modal --}}
    <div class="modal fade" id="filterModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="GET" action="{{ route('master-data.administration.customers.index') }}">
                    <div class="modal-header">
                        <h6 class="modal-title">
                            <i class="fas fa-filter me-2"></i>
                            <span data-en="Filter Customers">Filter Pelanggan</span>
                        </h6>
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
                                    @foreach ($customerTypes as $ct)
                                        <option value="{{ $ct->id }}"
                                            {{ request('customer_type_id') == $ct->id ? 'selected' : '' }}>
                                            {{ $ct->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" data-en="Business Type">Jenis Usaha</label>
                                <select name="business_type_id" class="form-select">
                                    <option value="" data-en="— All —">— Semua —</option>
                                    @foreach ($businessTypes as $bt)
                                        <option value="{{ $bt->id }}"
                                            {{ request('business_type_id') == $bt->id ? 'selected' : '' }}>
                                            {{ $bt->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label" data-en="Status">Status</label>
                                <select name="status" class="form-select">
                                    <option value="" data-en="— All Status —">— Semua Status —</option>
                                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}
                                        data-en="Active">Aktif</option>
                                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}
                                        data-en="Inactive">Tidak Aktif</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <a href="{{ route('master-data.administration.customers.index') }}"
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

    @push('scripts')
        <script>
            $(document).ready(function() {
                var table = $('#customerTable').DataTable({
                    pageLength: 10,
                    lengthMenu: [
                        [5, 10, 25, 50, -1],
                        ['5', '10', '25', '50', 'Semua']
                    ],
                    dom: '<"dt-top-bar"lf>rt<"dt-bottom-bar"ip>',
                    language: {
                        lengthMenu: 'Tampilkan _MENU_ data',
                        zeroRecords: 'Tidak ada data ditemukan',
                        info: 'Menampilkan _START_–_END_ dari _TOTAL_ data',
                        infoEmpty: 'Tidak ada data',
                        infoFiltered: '(dari _MAX_ total)',
                        search: '',
                        searchPlaceholder: 'Cari pelanggan...',
                        paginate: {
                            first: '«',
                            last: '»',
                            next: '›',
                            previous: '‹'
                        }
                    },
                    columnDefs: [{
                        orderable: false,
                        targets: -1
                    }],
                    order: [[1, 'asc']],
                    responsive: true,
                    autoWidth: false,
                    drawCallback: function() {
                        // pastikan border-radius card tetap setelah DataTables render
                        $('.veh-table-card').css('overflow', 'hidden');
                    }
                });
            });
        </script>
    @endpush

@endsection
