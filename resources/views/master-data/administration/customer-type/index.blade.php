@extends('layouts.admin')
@section('title', 'Jenis Pelanggan')
@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        /* ── DataTable top/bottom bars integrated into veh-table-card ── */
        .dt-top-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: .5rem;
            padding: .75rem 1.25rem;
            background: var(--surface);
            border-bottom: 1px solid var(--border);
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
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: .5rem;
            padding: .75rem 1.25rem;
            background: var(--surface);
            border-top: 1px solid var(--border);
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

        /* status badges */
        .ct-status-active   { display:inline-block; padding:.2rem .55rem; border-radius:99px; font-size:.75rem; font-weight:600; background:rgba(16,185,129,.12); color:#059669; }
        .ct-status-inactive { display:inline-block; padding:.2rem .55rem; border-radius:99px; font-size:.75rem; font-weight:600; background:rgba(107,114,128,.1);  color:var(--text-muted); }
        @media (prefers-color-scheme: dark) {
            :root:not([data-theme="light"]) .ct-status-active  { background:rgba(16,185,129,.18); color:#34d399; }
            :root:not([data-theme="light"]) .ct-status-inactive{ background:rgba(107,114,128,.18); }
        }
        :root[data-theme="dark"] .ct-status-active  { background:rgba(16,185,129,.18); color:#34d399; }
        :root[data-theme="dark"] .ct-status-inactive{ background:rgba(107,114,128,.18); }
    </style>
@endpush

@section('content')

    <div class="veh-page-header mb-4">
        <div>
            <div class="veh-title" data-en="Customer Types">Jenis Pelanggan</div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" data-en="Home">Beranda</a></li>
                    <li class="breadcrumb-item" data-en="Master Data">Data Master</li>
                    <li class="breadcrumb-item" data-en="Administration">Administrasi</li>
                    <li class="breadcrumb-item active" data-en="Customer Types">Jenis Pelanggan</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('master-data.administration.customer-types.create') }}"
                class="btn btn-primary btn-sm d-flex align-items-center gap-2">
                <i class="fas fa-plus"></i> <span data-en="Add">Tambah</span>
            </a>
        </div>
    </div>


    <div class="veh-table-card">
        <div class="veh-table-header">
            <span data-en="Customer Type List">Daftar Jenis Pelanggan</span>
            <small style="color:var(--text-faint)">{{ $customerTypes->count() }} <span data-en="records">data</span></small>
        </div>

        <div class="table-responsive">
            <table id="customerTypeTable" class="table" style="width:100%">
                <thead>
                    <tr>
                        <th style="width:50px" data-en="No">No</th>
                        <th data-en="Code">Kode</th>
                        <th data-en="Name">Nama</th>
                        <th data-en="Description">Deskripsi</th>
                        <th data-en="Status">Status</th>
                        <th class="text-end" style="width:100px" data-en="Actions">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($customerTypes as $item)
                    <tr>
                        <td></td>
                        <td>{{ $item->code ?: '—' }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ Str::limit($item->description, 60) ?: '—' }}</td>
                        <td>
                            @if($item->is_active)
                                <span class="ct-status-active" data-en="Active">Aktif</span>
                            @else
                                <span class="ct-status-inactive" data-en="Inactive">Tidak Aktif</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex justify-content-end gap-1">
                                <a href="{{ route('master-data.administration.customer-types.edit', $item) }}"
                                    class="btn btn-sm btn-outline-primary" title="Edit">
                                    <i class="fas fa-pen"></i>
                                </a>
                                <form method="POST"
                                    action="{{ route('master-data.administration.customer-types.destroy', $item) }}"
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
                    ? 'Delete customer type <strong>' + name + '</strong>? This action cannot be undone.'
                    : 'Hapus jenis pelanggan <strong>' + name + '</strong>? Tindakan ini tidak dapat dibatalkan.',
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
    $('#customerTypeTable').DataTable({
        pageLength: 10,
        lengthMenu: [[5, 10, 25, 50, -1], ['5', '10', '25', '50', 'Semua']],
        dom: '<"dt-top-bar"lf>rt<"dt-bottom-bar"ip>',
        language: {
            lengthMenu:        'Tampilkan _MENU_ data',
            zeroRecords:       'Tidak ada data ditemukan',
            info:              'Menampilkan _START_–_END_ dari _TOTAL_ data',
            infoEmpty:         'Menampilkan 0 dari 0 data',
            infoFiltered:      '(difilter dari _MAX_ total data)',
            search:            '',
            searchPlaceholder: 'Cari...',
            paginate: { first: '«', last: '»', next: '›', previous: '‹' }
        },
        columnDefs: [
            { orderable: false, targets: [0, 5] }
        ],
        order: [[2, 'asc']],
        responsive: true,
        autoWidth: false,
        drawCallback: function(settings) {
            var api = this.api();
            var start = api.context[0]._iDisplayStart;
            api.column(0, {search:'applied', order:'applied'}).nodes().each(function(cell, i) {
                cell.innerHTML = start + i + 1;
            });
        },
    });
});
</script>
@endpush
