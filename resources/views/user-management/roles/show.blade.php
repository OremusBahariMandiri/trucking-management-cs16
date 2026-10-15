@extends('layouts.admin')
@section('title', $role->name . ' — Detail Role')
@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
@endpush

@section('content')

{{-- ── PAGE HEADER ── --}}
<div class="um-page-header mb-3">
    <div>
        <div class="um-title"><i class="fas fa-shield-alt"></i> Detail Role</div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
                <li class="breadcrumb-item"><a href="{{ route('user-management.roles.index') }}">Role</a></li>
                <li class="breadcrumb-item active">{{ $role->name }}</li>
            </ol>
        </nav>
    </div>
    <div class="d-flex gap-2">
        @hasPermission('user-management.roles.update')
        <a href="{{ route('user-management.roles.edit', $role) }}"
           class="btn btn-sm btn-primary d-flex align-items-center gap-1">
            <i class="fas fa-pen"></i> Edit
        </a>
        @endhasPermission
        @hasPermission('user-management.roles.delete')
        @if($role->slug !== 'superadmin')
        <form method="POST" action="{{ route('user-management.roles.destroy', $role) }}"
              onsubmit="return confirm('Hapus role ini?')">
            @csrf @method('DELETE')
            <button class="btn btn-sm btn-outline-danger d-flex align-items-center gap-1">
                <i class="fas fa-trash"></i> Hapus
            </button>
        </form>
        @endif
        @endhasPermission
    </div>
</div>

{{-- ── INFO ROLE ── --}}
<div class="um-detail-card mb-3">
    <div class="um-detail-hero">
        <div class="um-show-avatar" style="font-size:1.75rem">
            <i class="fas fa-shield-alt"></i>
        </div>
        <div>
            <h4 class="um-show-name">{{ $role->name }}</h4>
            <span class="um-show-code">{{ $role->slug }}</span>
        </div>
    </div>
    <div class="um-detail-divider"></div>
    <div class="row g-4 px-3 py-3">
        <div class="col-sm-4">
            <div class="um-info-item">
                <div class="um-info-icon"><i class="fas fa-key"></i></div>
                <div>
                    <div class="info-label">Permission Aktif</div>
                    <div class="info-value">{{ $role->permissions->count() }}</div>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="um-info-item">
                <div class="um-info-icon"><i class="fas fa-users"></i></div>
                <div>
                    <div class="info-label">Jumlah User</div>
                    <div class="info-value">{{ $role->users->count() }}</div>
                </div>
            </div>
        </div>
        @if($role->description)
        <div class="col-sm-12">
            <div class="um-info-item">
                <div class="um-info-icon"><i class="fas fa-info-circle"></i></div>
                <div>
                    <div class="info-label">Deskripsi</div>
                    <div class="info-value">{{ $role->description }}</div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

{{-- ── KELOLA PERMISSION ── --}}
@hasPermission('user-management.roles.update')
@php
    $actionOrder = ['monitor','view','create','update','delete','download','export','import','print'];
    $actionLabel = [
        'monitor'  => 'Monitor',  'view'     => 'Detail',
        'create'   => 'Tambah',   'update'   => 'Ubah',
        'delete'   => 'Hapus',    'download' => 'Unduh',
        'export'   => 'Export',   'import'   => 'Import',
        'print'    => 'Cetak',
    ];

    $flatPerms  = collect($allPermissions)->flatten();
    $systemSlugs = $flatPerms->pluck('slug');

    // Kolom: action unik dari seluruh permission sistem
    $actions = $systemSlugs
        ->map(fn($s) => last(explode('.', $s)))
        ->unique()
        ->sort(function ($a, $b) use ($actionOrder) {
            $posA = ($p = array_search($a, $actionOrder)) !== false ? $p : 99;
            $posB = ($p = array_search($b, $actionOrder)) !== false ? $p : 99;
            return $posA <=> $posB;
        })
        ->values();

    // Baris: semua modul dari sistem
    $permTable = $systemSlugs->groupBy(function ($slug) {
        $parts = explode('.', $slug); array_pop($parts);
        return implode('.', $parts);
    })->sortKeys();

    // Index cepat: slug → permission object
    $permBySlug = $flatPerms->keyBy('slug');
@endphp

<div class="um-section-card mb-3">
    <div class="um-section-head">
        <h6 class="um-section-title">
            <i class="fas fa-key"></i> Kelola Permission
        </h6>
        @if($role->slug === 'superadmin')
            <span class="um-section-count" style="background:rgba(124,58,237,.12);color:#7c3aed">
                Akses penuh otomatis
            </span>
        @else
            <span class="um-section-count" id="selectedCount">
                {{ count($assignedIds) }} / {{ $flatPerms->count() }} dipilih
            </span>
        @endif
    </div>

    @if($role->slug === 'superadmin')
        <div class="px-3 py-3">
            <div class="alert alert-info border-0 d-flex align-items-center gap-2 mb-0" style="font-size:.875rem">
                <i class="fas fa-crown me-1"></i>
                Role <strong>Superadmin</strong> memiliki akses penuh ke seluruh permission secara otomatis.
            </div>
        </div>
    @else
    <form method="POST" action="{{ route('user-management.roles.sync-permissions', $role) }}" id="permForm">
        @csrf

        {{-- Toolbar --}}
        <div class="d-flex align-items-center gap-2 px-3 py-2 border-bottom" style="background:var(--surface-2)">
            <button type="button" id="btnSelectAll" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-check-double me-1"></i>Pilih Semua
            </button>
            <button type="button" id="btnClearAll" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-times me-1"></i>Hapus Semua
            </button>
            <div class="ms-auto" style="font-size:.78rem;color:var(--text-muted)">
                <i class="fas fa-info-circle me-1"></i>
                Klik ↔ untuk toggle seluruh baris sekaligus
            </div>
        </div>

        {{-- Tabel --}}
        <div class="um-perm-table-wrapper">
            <table class="um-perm-table">
                <thead>
                    <tr>
                        <th class="col-menu">Menu / Modul</th>
                        @foreach($actions as $act)
                            <th>{{ $actionLabel[$act] ?? ucfirst($act) }}</th>
                        @endforeach
                        <th style="width:48px">↔</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($permTable as $group => $slugsInGroup)
                        @php
                            $label = collect(explode('.', $group))
                                ->map(fn($s) => ucwords(str_replace('-', ' ', $s)))
                                ->join(' › ');
                        @endphp
                        <tr>
                            <td class="col-menu">
                                <div class="menu-name">{{ $label }}</div>
                                <div class="menu-slug">{{ $group }}</div>
                            </td>
                            @foreach($actions as $act)
                                @php $perm = $permBySlug->get($group . '.' . $act); @endphp
                                <td>
                                    @if($perm)
                                        <label class="um-perm-toggle" title="{{ $group }}.{{ $act }}">
                                            <input type="checkbox"
                                                   name="permission_ids[]"
                                                   value="{{ $perm->id }}"
                                                   class="perm-checkbox visually-hidden"
                                                   {{ in_array($perm->id, $assignedIds) ? 'checked' : '' }}>
                                            <span class="perm-toggle-dot"></span>
                                        </label>
                                    @else
                                        <span class="perm-na">·</span>
                                    @endif
                                </td>
                            @endforeach
                            <td>
                                <button type="button" class="btn-row-toggle" onclick="toggleRow(this)"
                                        title="Toggle semua di baris ini">↔</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="um-action-bar">
            <div style="font-size:.8rem;color:var(--text-muted)">
                <i class="fas fa-info-circle me-1"></i>
                Perubahan permission langsung berlaku untuk semua user dengan role ini.
            </div>
            <button type="submit" class="btn-save-um">
                <i class="fas fa-save me-1"></i> Simpan Permission
            </button>
        </div>
    </form>
    @endif
</div>
@endhasPermission

{{-- ── USER DENGAN ROLE INI ── --}}
@if($role->users->count())
<div class="um-section-card">
    <div class="um-section-head">
        <h6 class="um-section-title">
            <i class="fas fa-users"></i> User dengan Role Ini
        </h6>
        <span class="um-section-count">{{ $role->users->count() }} user</span>
    </div>
    <div class="table-responsive">
        <table class="table um-perm-table">
            <thead>
                <tr>
                    <th class="col-menu" style="min-width:180px;text-align:left">Nama</th>
                    <th style="text-align:left">Kode</th>
                    <th style="text-align:left">Jabatan</th>
                    <th style="text-align:right;padding-right:1.25rem">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($role->users as $u)
                <tr>
                    <td class="col-menu">
                        <div class="d-flex align-items-center gap-2">
                            <div class="um-avatar">{{ strtoupper(substr($u->full_name, 0, 1)) }}</div>
                            <span class="um-name">{{ $u->full_name }}</span>
                        </div>
                    </td>
                    <td style="text-align:left;font-family:monospace;font-size:.82rem">{{ $u->employee_code ?? '—' }}</td>
                    <td style="text-align:left;color:var(--text-muted)">{{ $u->position ?? '—' }}</td>
                    <td style="text-align:right;padding-right:1.25rem">
                        <a href="{{ route('user-management.users.show', $u) }}"
                           class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

@include('user-management._partials._toast')

@push('scripts')
<script>
function toggleRow(btn) {
    const row = btn.closest('tr');
    const cbs = row.querySelectorAll('.perm-checkbox');
    const allChecked = [...cbs].every(cb => cb.checked);
    cbs.forEach(cb => { cb.checked = !allChecked; });
    updateCount();
}

document.getElementById('btnSelectAll')?.addEventListener('click', () => {
    document.querySelectorAll('.perm-checkbox').forEach(cb => cb.checked = true);
    updateCount();
});
document.getElementById('btnClearAll')?.addEventListener('click', () => {
    document.querySelectorAll('.perm-checkbox').forEach(cb => cb.checked = false);
    updateCount();
});

function updateCount() {
    const total   = document.querySelectorAll('.perm-checkbox').length;
    const checked = document.querySelectorAll('.perm-checkbox:checked').length;
    const el = document.getElementById('selectedCount');
    if (el) el.textContent = checked + ' / ' + total + ' dipilih';
}

document.querySelectorAll('.perm-checkbox').forEach(cb => {
    cb.addEventListener('change', updateCount);
});
</script>
@endpush
@endsection