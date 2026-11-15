@extends('layouts.admin')
@section('title', 'Kelola Permission — ' . $role->name)

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        .pm-card { border:1px solid var(--border); border-radius:8px; overflow:hidden; }

        /* Satu tabel, satu header */
        .pm-table { width:100%; border-collapse:collapse; font-size:.8rem; }

        .pm-table thead th {
            position:sticky; top:0; z-index:2;
            padding:.5rem .85rem; text-align:center;
            background:var(--surface-2);
            font-weight:600; font-size:.71rem;
            color:var(--text-muted); text-transform:uppercase; letter-spacing:.04em;
            border-bottom:2px solid var(--border);
            white-space:nowrap;
        }
        /* Toggle L1 */
        .pm-l1-row td { cursor:pointer; }
        .pm-l1-row td:hover { background:var(--pm-l1-bg-hover); }
        .pm-l1-chevron { float:right; transition:transform .2s; opacity:.55; font-size:.75rem; }
        .pm-l1-row.collapsed .pm-l1-chevron { transform:rotate(-90deg); }

        .pm-table thead th.th-feat {
            text-align:left; color:var(--text);
            min-width:180px;
        }

        /* Pemisah L1 (Master Data, Data Management …) */
        .pm-table tbody .pm-l1-row td {
            padding:.45rem 1rem;
            background:var(--pm-l1-bg);
            font-weight:700; font-size:.78rem;
            color:var(--blue);
            border-top:2px solid var(--blue-lt);
            border-bottom:1px solid var(--blue-lt);
            letter-spacing:.02em;
            text-align:left !important;
        }

        /* Pemisah L2 (Kendaraan, Armada …) */
        .pm-table tbody .pm-l2-row td {
            padding:.35rem 1rem;
            background:var(--surface-2);
            font-weight:600; font-size:.71rem;
            color:var(--blue);
            text-transform:uppercase; letter-spacing:.05em;
            border-top:1px solid var(--border);
            border-bottom:1px solid var(--border);
            text-align:left !important;
        }
        .pm-l2-inner { display:flex; align-items:center; }

        /* Baris data */
        .pm-table tbody tr:not(.pm-l1-row):not(.pm-l2-row):hover td {
            background:var(--surface-2);
        }
        .pm-table tbody td {
            padding:.42rem .85rem; text-align:center;
            border-bottom:1px solid var(--border);
            vertical-align:middle;
        }
        .pm-table tbody td.td-feat {
            text-align:left;
            font-weight:500; color:var(--text);
            white-space:nowrap;
        }
        .pm-table tbody tr:last-child td { border-bottom:none; }

        /* N/A — jelas berbeda dari switch yang off */
        td.na { color:var(--text-faint); font-size:.8rem; background:var(--surface-2); }

        /* Switch Bootstrap sedikit diperkecil */
        .form-check.pm-sw { margin:0; padding-left:2.3rem; min-height:0; }
        .form-check.pm-sw .form-check-input {
            width:1.9rem; height:1.05rem; margin-left:-2.3rem;
            cursor:pointer;
        }

        /* Save bar */
        .pm-save-bar {
            background:var(--surface);
            border-top:1px solid var(--border);
            padding:.8rem 1rem;
            display:flex; align-items:center; justify-content:space-between; gap:1rem;
            border-radius:0 0 8px 8px;
        }
        .pm-counter { font-size:.82rem; color:var(--text-muted); }
        .pm-counter strong { color:var(--text); font-size:.92rem; }

        @media(max-width:576px){
            .pm-save-bar { flex-direction:column; gap:.5rem; }
            .pm-l2-row .sg-btns .btn span { display:none; }
        }
    </style>
@endpush

@section('content')

<div class="um-page-header mb-4">
    <div>
        <div class="um-title">
            <i class="fas fa-lock me-2" style="font-size:.9em;opacity:.65"></i>
            <span data-en="Manage Permissions">Kelola Permission</span> &mdash; <span style="color:var(--um-accent)">{{ $role->name }}</span>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" data-en="Home">Beranda</a></li>
                <li class="breadcrumb-item"><a href="{{ route('user-management.roles.index') }}" data-en="Roles">Role</a></li>
                <li class="breadcrumb-item"><a href="{{ route('user-management.roles.show', $role) }}">{{ $role->name }}</a></li>
                <li class="breadcrumb-item active" data-en="Manage Permissions">Kelola Permission</li>
            </ol>
        </nav>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<form method="POST" action="{{ route('user-management.roles.sync-permissions', $role) }}" id="permForm">
    @csrf

    @php
        $actionMeta = [
            'index'        => 'Index',
            'show'         => 'Detail',
            'create'       => 'Tambah',
            'edit'         => 'Ubah',
            'delete'       => 'Hapus',
            'export-pdf'   => 'Export PDF',
            'export-excel' => 'Export Excel',
        ];
        $actionOrder = array_keys($actionMeta);

        // Urutkan L1 group
        $l1Order = ['User Management', 'Master Data', 'Data Management'];
        $treeSorted = [];
        foreach ($l1Order as $key) {
            if (isset($tree[$key])) $treeSorted[$key] = $tree[$key];
        }
        foreach ($tree as $key => $val) {
            if (!isset($treeSorted[$key])) $treeSorted[$key] = $val;
        }
        $tree = $treeSorted;

        // Tentukan kolom global — semua aksi yang ada di seluruh permission
        $globalActions = [];
        foreach ($tree as $l2Groups) {
            foreach ($l2Groups as $modules) {
                foreach ($modules as $perms) {
                    foreach ($perms as $p) {
                        $act = last(explode('.', $p->slug));
                        if (!in_array($act, $globalActions)) $globalActions[] = $act;
                    }
                }
            }
        }
        $globalActions = array_values(array_filter($actionOrder, fn($a) => in_array($a, $globalActions)));
        $totalCols = count($globalActions) + 1;

        // Hitung total permission
        $totalPerms = 0;
        foreach ($tree as $l2Groups) {
            foreach ($l2Groups as $modules) {
                foreach ($modules as $perms) {
                    $totalPerms += count($perms);
                }
            }
        }
    @endphp

    <div class="mb-5 pm-card">
        <div style="overflow-x:auto">
            <table class="pm-table">

                {{-- SATU header untuk seluruh tabel --}}
                <thead>
                    <tr>
                        <th class="th-feat" data-en="Feature">Fitur</th>
                        @php
                            $actionEnMap = [
                                'index'        => 'View List',
                                'show'         => 'View Detail',
                                'create'       => 'Add',
                                'edit'         => 'Edit',
                                'delete'       => 'Delete',
                                'export-pdf'   => 'Export PDF',
                                'export-excel' => 'Export Excel',
                            ];
                        @endphp
                        @foreach ($globalActions as $act)
                            <th><span data-en="{{ $actionEnMap[$act] ?? $act }}">{{ $actionMeta[$act] }}</span></th>
                        @endforeach
                    </tr>
                </thead>

                <tbody>
                @foreach ($tree as $l1Name => $l2Groups)

                    {{-- Pemisah L1 --}}
                    <tr class="pm-l1-row" data-l1="{{ $l1Name }}"
                        onclick="toggleL1(this)">
                        <td colspan="{{ $totalCols }}">
                            <i class="fas fa-layer-group me-2" style="opacity:.7"></i>{{ $l1Name }}
                            <i class="fas fa-chevron-down pm-l1-chevron"></i>
                        </td>
                    </tr>

                    @foreach ($l2Groups as $l2Name => $modules)
                        @php
                            $l2PermIds = [];
                            $moduleMap = [];
                            foreach ($modules as $modulePrefix => $perms) {
                                foreach ($perms as $p) {
                                    $act = last(explode('.', $p->slug));
                                    $moduleMap[$modulePrefix][$act] = $p;
                                    $l2PermIds[] = $p->id;
                                }
                            }
                        @endphp

                        {{-- Pemisah L2 --}}
                        <tr class="pm-l2-row" data-l1="{{ $l1Name }}">
                            <td colspan="{{ $totalCols }}">
                                <div>
                                    <span>
                                        <i class="fas fa-folder fa-xs me-1"></i>
                                        {{ $l2Name ?: $l1Name }}
                                    </span>

                                </div>
                            </td>
                        </tr>

                        {{-- Baris modul --}}
                        @foreach ($moduleMap as $modulePrefix => $permsByAction)
                            @php
                                if (isset($permsByAction['index'])) {
                                    $moduleName = preg_replace('/^Lihat Daftar\s+/u', '', $permsByAction['index']->name);
                                } elseif (isset($permsByAction['show'])) {
                                    $moduleName = preg_replace('/^Lihat Detail\s+/u', '', $permsByAction['show']->name);
                                } else {
                                    $moduleName = ucwords(str_replace('-', ' ', last(explode('.', $modulePrefix))));
                                }
                            @endphp
                            <tr data-l1="{{ $l1Name }}">
                                <td class="td-feat">{{ $moduleName }}</td>
                                @foreach ($globalActions as $act)
                                    @if (isset($permsByAction[$act]))
                                        @php $perm = $permsByAction[$act]; $checked = in_array($perm->id, $assignedIds); @endphp
                                        <td>
                                            <div class="form-check form-switch pm-sw d-flex justify-content-center"
                                                 title="{{ $perm->name }}">
                                                <input class="form-check-input perm-cb"
                                                       type="checkbox"
                                                       name="permission_ids[]"
                                                       value="{{ $perm->id }}"
                                                       role="switch"
                                                       {{ $checked ? 'checked' : '' }}>
                                            </div>
                                        </td>
                                    @else
                                        <td class="na" title="Tidak tersedia" data-en-title="Not available">—</td>
                                    @endif
                                @endforeach
                            </tr>
                        @endforeach

                    @endforeach
                @endforeach
                </tbody>

            </table>
        </div>
    </div>

    <div class="pm-save-bar">
        <div class="pm-counter">
            <span data-en="Selected">Terpilih</span>: <strong id="selectedCount">0</strong>
            <span style="opacity:.55"> / {{ $totalPerms }} <span data-en="permissions">permission</span></span>
        </div>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="selectAll(false)">
                <i class="fas fa-times me-1"></i><span data-en="Deselect All">Hapus Semua</span>
            </button>
            <button type="button" class="btn btn-outline-primary btn-sm" onclick="selectAll(true)">
                <i class="fas fa-check-double me-1"></i><span data-en="Select All">Pilih Semua</span>
            </button>
            <button type="submit" class="btn btn-primary btn-sm">
                <i class="fas fa-save me-1"></i>Simpan
            </button>
        </div>
    </div>

</form>

@include('user-management._partials._toast')
@endsection

@push('scripts')
<script>
(function () {
    function updateCounter() {
        var n = document.querySelectorAll('.perm-cb:checked').length;
        var el = document.getElementById('selectedCount');
        if (el) el.textContent = n;
    }
    window.toggleL1 = function (row) {
        var group = row.getAttribute('data-l1');
        var isCollapsed = row.classList.toggle('collapsed');
        document.querySelectorAll('tr[data-l1="' + group + '"]:not(.pm-l1-row)').forEach(function (tr) {
            tr.style.display = isCollapsed ? 'none' : '';
        });
    };
    window.selectAll = function (state) {
        document.querySelectorAll('.perm-cb').forEach(function (cb) { cb.checked = state; });
        updateCounter();
    };
    window.selectGroup = function (ids, state) {
        ids.forEach(function (id) {
            var cb = document.querySelector('.perm-cb[value="' + id + '"]');
            if (cb) cb.checked = state;
        });
        updateCounter();
    };
    document.querySelectorAll('.perm-cb').forEach(function (cb) {
        cb.addEventListener('change', updateCounter);
    });
    updateCounter();
})();
</script>
@vite('resources/js/pages/user-management.js')
@endpush
