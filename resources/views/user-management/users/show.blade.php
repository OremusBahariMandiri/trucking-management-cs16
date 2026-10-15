@extends('layouts.admin')
@section('title', $user->full_name . ' — Detail User')
@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
@endpush

@section('content')

{{-- ── PAGE HEADER ── --}}
<div class="um-page-header mb-3">
    <div>
        <div class="um-title"><i class="fas fa-user-circle"></i> Detail User</div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
                <li class="breadcrumb-item"><a href="{{ route('user-management.users.index') }}">User</a></li>
                <li class="breadcrumb-item active">{{ $user->full_name }}</li>
            </ol>
        </nav>
    </div>
    <div class="d-flex gap-2">
        @hasPermission('user-management.users.update')
        <a href="{{ route('user-management.users.edit', $user) }}"
           class="btn btn-sm btn-primary d-flex align-items-center gap-1">
            <i class="fas fa-pen"></i> Edit
        </a>
        @endhasPermission
        @hasPermission('user-management.users.delete')
        <form method="POST" action="{{ route('user-management.users.destroy', $user) }}"
              onsubmit="return confirm('Hapus user ini?')">
            @csrf @method('DELETE')
            <button class="btn btn-sm btn-outline-danger d-flex align-items-center gap-1">
                <i class="fas fa-trash"></i> Hapus
            </button>
        </form>
        @endhasPermission
    </div>
</div>

{{-- ── HERO CARD ── --}}
<div class="um-detail-card mb-3">
    <div class="um-detail-hero">
        <div class="um-show-avatar">{{ strtoupper(substr($user->full_name, 0, 1)) }}</div>
        <div>
            <h4 class="um-show-name">{{ $user->full_name }}</h4>
            <div class="d-flex align-items-center gap-2 flex-wrap mt-1">
                <span class="um-show-code">
                    <i class="fas fa-id-badge me-1"></i>{{ $user->employee_code ?? 'Belum ada kode' }}
                </span>
                @if($user->isSuperAdmin())
                    <span class="role-badge" style="--um-accent:#7c3aed;--um-accent-soft:rgba(124,58,237,.12)">
                        <i class="fas fa-crown"></i> Super Admin
                    </span>
                @endif
            </div>
        </div>
    </div>
    <div class="um-detail-divider"></div>
    <div class="row g-4 px-3 py-3">
        @php
            $fields = [
                ['label' => 'NIK',          'value' => $user->employee_id_number, 'icon' => 'fas fa-id-card'],
                ['label' => 'Departemen',   'value' => $user->department,         'icon' => 'fas fa-building'],
                ['label' => 'Jabatan',      'value' => $user->position,           'icon' => 'fas fa-briefcase'],
                ['label' => 'Lokasi Kerja', 'value' => $user->work_location,      'icon' => 'fas fa-map-marker-alt'],
            ];
        @endphp
        @foreach($fields as $f)
        <div class="col-sm-6 col-md-3">
            <div class="um-info-item">
                <div class="um-info-icon"><i class="{{ $f['icon'] }}"></i></div>
                <div>
                    <div class="info-label">{{ $f['label'] }}</div>
                    <div class="info-value">{{ $f['value'] ?? '—' }}</div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <div class="um-detail-footer">
        <span><i class="fas fa-clock me-1"></i>Dibuat {{ $user->created_at?->format('d M Y H:i') ?? '—' }}</span>
        <span><i class="fas fa-edit me-1"></i>Diperbarui {{ $user->updated_at?->format('d M Y H:i') ?? '—' }}</span>
    </div>
</div>

{{-- ── ROLE DIMILIKI ── --}}
<div class="um-section-card mb-3">
    <div class="um-section-head">
        <h6 class="um-section-title">
            <i class="fas fa-user-shield"></i> Role Dimiliki
        </h6>
        <span class="um-section-count">{{ $user->roles->count() }} role</span>
    </div>

    @if($user->roles->isEmpty())
        <div class="um-empty">
            <div class="um-empty-icon"><i class="fas fa-user-slash"></i></div>
            <h6>Belum ada role</h6>
            <p>User ini belum ditetapkan ke role manapun.</p>
        </div>
    @else
        <div class="um-role-list">
            @foreach($user->roles as $role)
            <div class="um-role-item">
                <div class="um-role-item-info">
                    <div class="um-role-icon"><i class="fas fa-shield-alt"></i></div>
                    <div>
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <span class="um-role-name">{{ $role->name }}</span>
                            <span class="um-role-slug">{{ $role->slug }}</span>
                        </div>
                        @if($role->description)
                            <div class="um-role-desc">{{ $role->description }}</div>
                        @endif
                    </div>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <span class="um-role-perm-count">
                        <i class="fas fa-key fa-xs me-1" style="color:var(--um-accent)"></i>
                        {{ $role->permissions->count() }} permission
                    </span>
                    <a href="{{ route('user-management.roles.show', $role) }}"
                       class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-eye"></i>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>

{{-- ── PERMISSION AKTIF ── --}}
@php
    $actionOrder = ['monitor','view','create','update','delete','download','export','import','print'];
    $actionLabel = [
        'monitor'  => 'Monitor',  'view'     => 'Detail',
        'create'   => 'Tambah',   'update'   => 'Ubah',
        'delete'   => 'Hapus',    'download' => 'Unduh',
        'export'   => 'Export',   'import'   => 'Import',
        'print'    => 'Cetak',
    ];

    // Semua slug permission yang ada di sistem (untuk kolom & baris)
    $systemSlugs = collect($allPermissions)->flatten()->pluck('slug');

    // Slug yang dimiliki user (untuk menentukan ✓ atau ✗)
    $userSlugs = $user->roles
        ->flatMap(fn($r) => $r->permissions)
        ->unique('id')
        ->pluck('slug');

    // Kolom: dari sistem agar selalu lengkap
    $actions = $systemSlugs
        ->map(fn($s) => last(explode('.', $s)))
        ->unique()
        ->sort(function ($a, $b) use ($actionOrder) {
            $posA = ($p = array_search($a, $actionOrder)) !== false ? $p : 99;
            $posB = ($p = array_search($b, $actionOrder)) !== false ? $p : 99;
            return $posA <=> $posB;
        })
        ->values();

    // Baris: dari sistem agar semua modul muncul
    $grouped = $systemSlugs->groupBy(function ($slug) {
        $parts = explode('.', $slug); array_pop($parts);
        return implode('.', $parts);
    })->sortKeys();
@endphp

<div class="um-section-card">
    <div class="um-section-head">
        <h6 class="um-section-title">
            <i class="fas fa-key"></i> Permission Aktif
        </h6>
        <span class="um-section-count">
            {{ $userSlugs->count() }} / {{ $systemSlugs->count() }} permission
        </span>
    </div>

    @if($grouped->isEmpty())
        <div class="um-empty">
            <div class="um-empty-icon"><i class="fas fa-lock"></i></div>
            <h6>Tidak ada permission</h6>
            <p>Belum ada permission terdaftar di sistem.</p>
        </div>
    @else
        <div class="um-perm-table-wrapper">
            <table class="um-perm-table">
                <thead>
                    <tr>
                        <th class="col-menu">Menu / Modul</th>
                        @foreach($actions as $act)
                            <th>{{ $actionLabel[$act] ?? ucfirst($act) }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($grouped as $group => $slugsInGroup)
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
                                @php $slug = $group . '.' . $act; @endphp
                                <td>
                                    @if($systemSlugs->contains($slug))
                                        @if($userSlugs->contains($slug))
                                            <span class="perm-yes" title="{{ $slug }}">✓</span>
                                        @else
                                            <span class="perm-no perm-x" title="{{ $slug }}">✗</span>
                                        @endif
                                    @else
                                        <span class="perm-na">·</span>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

@include('user-management._partials._toast')
@endsection