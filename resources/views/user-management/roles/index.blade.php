@extends('layouts.admin')
@section('title', 'Manajemen Role')
@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
@endpush

@section('content')

<div class="um-page-header mb-4">
    <div>
        <div class="um-title"><i class="fas fa-user-tag"></i> Manajemen Role</div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
                <li class="breadcrumb-item active">Role</li>
            </ol>
        </nav>
    </div>
    @hasPermission('user-management.roles.create')
    <a href="{{ route('user-management.roles.create') }}"
       class="btn btn-primary btn-sm d-flex align-items-center gap-2">
        <i class="fas fa-plus"></i> Tambah Role
    </a>
    @endhasPermission
</div>

<div class="um-filter-bar mb-3">
    <form method="GET" action="{{ route('user-management.roles.index') }}" class="row g-2 align-items-end">
        <div class="col-12 col-sm-6 col-md-5">
            <div class="input-group input-group-sm">
                <span class="input-group-text"><i class="fas fa-search"></i></span>
                <input type="text" name="search" class="form-control"
                       value="{{ request('search') }}" placeholder="Nama atau slug role…">
            </div>
        </div>
        <div class="col-6 col-md-auto">
            <button type="submit" class="btn btn-primary btn-sm w-100">
                <i class="fas fa-filter me-1"></i>Filter
            </button>
        </div>
        @if(request('search'))
        <div class="col-6 col-md-auto">
            <a href="{{ route('user-management.roles.index') }}"
               class="btn btn-outline-secondary btn-sm w-100">
                <i class="fas fa-times-circle me-1"></i>Reset
            </a>
        </div>
        @endif
    </form>
</div>

<div class="um-table-card">
    <div class="um-table-header">
        <h6><i class="fas fa-list"></i> Daftar Role</h6>
        <small style="color:var(--text-faint)">{{ $roles->total() }} data</small>
    </div>

    @if($roles->isEmpty())
    <div class="um-empty">
        <div class="um-empty-icon"><i class="fas fa-user-tag"></i></div>
        <h6>Belum ada role</h6>
        <p>Tambah role untuk mengelola hak akses user.</p>
    </div>
    @else
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th style="width:3rem" class="text-center">No</th>
                    <th>Nama</th>
                    <th>Slug</th>
                    <th class="text-center">Permission</th>
                    <th class="text-center">User</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($roles as $role)
                <tr>
                    <td class="text-center" style="font-size:.82rem;color:var(--text-muted)">
                        {{ $roles->firstItem() + $loop->index }}
                    </td>
                    <td>
                        <span class="um-name">{{ $role->name }}</span>
                        @if($role->description)
                            <div style="font-size:.75rem;color:var(--text-faint)">{{ $role->description }}</div>
                        @endif
                    </td>
                    <td><code style="font-size:.78rem">{{ $role->slug }}</code></td>
                    <td class="text-center">
                        <span class="role-badge">{{ $role->permissions_count }}</span>
                    </td>
                    <td class="text-center" style="font-size:.875rem;color:var(--text-muted)">
                        {{ $role->users_count }}
                    </td>
                    <td>
                        <div class="d-flex justify-content-end gap-1">
                            <a href="{{ route('user-management.roles.show', $role) }}"
                               class="btn btn-sm btn-outline-secondary" title="Detail & Permission">
                                <i class="fas fa-eye"></i>
                            </a>
                            @hasPermission('user-management.roles.update')
                            <a href="{{ route('user-management.roles.edit', $role) }}"
                               class="btn btn-sm btn-outline-primary" title="Edit">
                                <i class="fas fa-pen"></i>
                            </a>
                            @endhasPermission
                            @hasPermission('user-management.roles.delete')
                            @if($role->slug !== 'superadmin')
                            <form method="POST"
                                  action="{{ route('user-management.roles.destroy', $role) }}"
                                  onsubmit="return confirm('Hapus role {{ $role->name }}?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @endif
                            @endhasPermission
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="um-table-footer">
        <span>Menampilkan {{ $roles->firstItem() }}–{{ $roles->lastItem() }} dari {{ $roles->total() }}</span>
        {{ $roles->links() }}
    </div>
    @endif
</div>

@include('user-management._partials._toast')
@endsection