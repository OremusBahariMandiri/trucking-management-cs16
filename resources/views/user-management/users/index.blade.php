@extends('layouts.admin')
@section('title', 'Manajemen User')
@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
@endpush

@section('content')

<div class="um-page-header mb-4">
    <div>
        <div class="um-title"><i class="fas fa-users-cog"></i> Manajemen User</div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
                <li class="breadcrumb-item active">User</li>
            </ol>
        </nav>
    </div>
    @hasPermission('user-management.users.create')
    <a href="{{ route('user-management.users.create') }}"
       class="btn btn-primary btn-sm d-flex align-items-center gap-2">
        <i class="fas fa-plus"></i> Tambah User
    </a>
    @endhasPermission
</div>

{{-- Filter --}}
<div class="um-filter-bar mb-3">
    <form method="GET" action="{{ route('user-management.users.index') }}" class="row g-2 align-items-end">
        <div class="col-12 col-sm-6 col-md-4">
            <label class="form-label form-label-sm">Cari</label>
            <div class="input-group input-group-sm">
                <span class="input-group-text"><i class="fas fa-search"></i></span>
                <input type="text" name="search" class="form-control"
                       value="{{ request('search') }}" placeholder="Nama, kode karyawan, NIK…">
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <label class="form-label form-label-sm">Role</label>
            <select name="role" class="form-select form-select-sm">
                <option value="">Semua Role</option>
                @foreach($roles as $role)
                    <option value="{{ $role->slug }}" @selected(request('role') === $role->slug)>
                        {{ $role->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-6 col-md-auto">
            <button type="submit" class="btn btn-primary btn-sm w-100">
                <i class="fas fa-filter me-1"></i>Filter
            </button>
        </div>
        @if(request()->hasAny(['search','role']))
        <div class="col-6 col-md-auto">
            <a href="{{ route('user-management.users.index') }}"
               class="btn btn-outline-secondary btn-sm w-100">
                <i class="fas fa-times-circle me-1"></i>Reset
            </a>
        </div>
        @endif
    </form>
</div>

{{-- Table --}}
<div class="um-table-card">
    <div class="um-table-header">
        <h6><i class="fas fa-list"></i> Daftar User</h6>
        <small style="color:var(--text-faint)">{{ $users->total() }} data</small>
    </div>

    @if($users->isEmpty())
    <div class="um-empty">
        <div class="um-empty-icon"><i class="fas fa-users-cog"></i></div>
        <h6>Tidak ada user ditemukan</h6>
        <p>Coba ubah filter atau tambah user baru.</p>
    </div>
    @else

    {{-- ── DESKTOP TABLE (md ke atas) ── --}}
    <div class="um-desktop-table">
        <table class="table">
            <thead>
                <tr>
                    <th style="width:3rem" class="text-center">No</th>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Jabatan</th>
                    <th>Role</th>
                    <th class="text-end pe-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td class="text-center" style="font-size:.82rem;color:var(--text-muted)">
                        {{ $users->firstItem() + $loop->index }}
                    </td>
                    <td style="font-family:monospace;font-size:.83rem">
                        {{ $user->employee_code ?? '—' }}
                    </td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="um-avatar">{{ strtoupper(substr($user->full_name,0,1)) }}</div>
                            <div>
                                <div class="um-name">{{ $user->full_name }}</div>
                                @if($user->employee_id_number)
                                    <div style="font-size:.75rem;color:var(--text-faint)">
                                        NIK: {{ $user->employee_id_number }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td style="font-size:.875rem;color:var(--text-muted)">
                        {{ $user->position ?? '—' }}
                    </td>
                    <td>
                        @forelse($user->roles as $role)
                            <span class="role-badge">{{ $role->name }}</span>
                        @empty
                            <span style="font-size:.8rem;color:var(--text-faint)">—</span>
                        @endforelse
                    </td>
                    <td>
                        <div class="d-flex justify-content-end gap-1 pe-1">
                            <a href="{{ route('user-management.users.show', $user) }}"
                               class="btn btn-sm btn-outline-secondary" title="Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            @hasPermission('user-management.users.update')
                            <a href="{{ route('user-management.users.edit', $user) }}"
                               class="btn btn-sm btn-outline-primary" title="Edit">
                                <i class="fas fa-pen"></i>
                            </a>
                            @endhasPermission
                            @hasPermission('user-management.users.delete')
                            <form method="POST"
                                  action="{{ route('user-management.users.destroy', $user) }}"
                                  onsubmit="return confirm('Hapus user {{ $user->full_name }}?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @endhasPermission
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- ── MOBILE CARDS (di bawah md) ── --}}
    <div class="um-mobile-list">
        @foreach($users as $user)
        <div class="um-mobile-card">
            <div class="um-mobile-card-main">
                <div class="um-avatar">{{ strtoupper(substr($user->full_name,0,1)) }}</div>
                <div class="um-mobile-card-info">
                    <div class="um-name">{{ $user->full_name }}</div>
                    <div class="um-mobile-card-meta">
                        @if($user->employee_code)
                            <span><i class="fas fa-id-badge fa-xs"></i> {{ $user->employee_code }}</span>
                        @endif
                        @if($user->position)
                            <span><i class="fas fa-briefcase fa-xs"></i> {{ $user->position }}</span>
                        @endif
                    </div>
                    <div class="mt-1">
                        @forelse($user->roles as $role)
                            <span class="role-badge" style="font-size:.68rem">{{ $role->name }}</span>
                        @empty
                            <span style="font-size:.75rem;color:var(--text-faint)">Belum ada role</span>
                        @endforelse
                    </div>
                </div>
            </div>
            <div class="um-mobile-card-actions">
                <a href="{{ route('user-management.users.show', $user) }}"
                   class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-eye me-1"></i>Detail
                </a>
                @hasPermission('user-management.users.update')
                <a href="{{ route('user-management.users.edit', $user) }}"
                   class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-pen me-1"></i>Edit
                </a>
                @endhasPermission
                @hasPermission('user-management.users.delete')
                <form method="POST"
                      action="{{ route('user-management.users.destroy', $user) }}"
                      onsubmit="return confirm('Hapus user {{ $user->full_name }}?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>
                @endhasPermission
            </div>
        </div>
        @endforeach
    </div>

    <div class="um-table-footer">
        <span>Menampilkan {{ $users->firstItem() }}–{{ $users->lastItem() }} dari {{ $users->total() }}</span>
        {{ $users->links() }}
    </div>
    @endif
</div>

@include('user-management._partials._toast')
@endsection