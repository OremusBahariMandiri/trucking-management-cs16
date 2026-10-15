@extends('layouts.admin')
@section('title', 'Manajemen Permission')
@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
@endpush

@section('content')

<div class="um-page-header mb-4">
    <div>
        <div class="um-title"><i class="fas fa-key"></i> Manajemen Permission</div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
                <li class="breadcrumb-item active">Permission</li>
            </ol>
        </nav>
    </div>
    @hasPermission('user-management.permissions.create')
    <a href="{{ route('user-management.permissions.create') }}"
       class="btn btn-primary btn-sm d-flex align-items-center gap-2">
        <i class="fas fa-plus"></i> Tambah Permission
    </a>
    @endhasPermission
</div>

<div class="um-filter-bar mb-3">
    <form method="GET" action="{{ route('user-management.permissions.index') }}" class="row g-2 align-items-end">
        <div class="col-12 col-sm-5 col-md-4">
            <div class="input-group input-group-sm">
                <span class="input-group-text"><i class="fas fa-search"></i></span>
                <input type="text" name="search" class="form-control"
                       value="{{ request('search') }}" placeholder="Nama atau slug…">
            </div>
        </div>
        <div class="col-12 col-sm-4 col-md-3">
            <select name="group" class="form-select form-select-sm">
                <option value="">Semua Group</option>
                @foreach($groups as $g)
                    <option value="{{ $g }}" @selected(request('group') === $g)>{{ $g }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-6 col-md-auto">
            <button type="submit" class="btn btn-primary btn-sm w-100">
                <i class="fas fa-filter me-1"></i>Filter
            </button>
        </div>
        @if(request()->hasAny(['search','group']))
        <div class="col-6 col-md-auto">
            <a href="{{ route('user-management.permissions.index') }}"
               class="btn btn-outline-secondary btn-sm w-100">
                <i class="fas fa-times-circle me-1"></i>Reset
            </a>
        </div>
        @endif
    </form>
</div>

<div class="um-table-card">
    <div class="um-table-header">
        <h6><i class="fas fa-list"></i> Daftar Permission</h6>
        <small style="color:var(--text-faint)">{{ $permissions->total() }} data</small>
    </div>

    @if($permissions->isEmpty())
    <div class="um-empty">
        <div class="um-empty-icon"><i class="fas fa-key"></i></div>
        <h6>Belum ada permission</h6>
        <p>Jalankan seeder atau tambah permission secara manual.</p>
    </div>
    @else
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th style="width:3rem" class="text-center">No</th>
                    <th>Nama</th>
                    <th>Slug</th>
                    <th>Group</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($permissions as $permission)
                <tr>
                    <td class="text-center" style="font-size:.82rem;color:var(--text-muted)">
                        {{ $permissions->firstItem() + $loop->index }}
                    </td>
                    <td class="um-name">{{ $permission->name }}</td>
                    <td><code style="font-size:.78rem">{{ $permission->slug }}</code></td>
                    <td>
                        <span class="role-badge" style="font-size:.72rem">{{ $permission->group }}</span>
                    </td>
                    <td>
                        <div class="d-flex justify-content-end gap-1">
                            @hasPermission('user-management.permissions.update')
                            <a href="{{ route('user-management.permissions.edit', $permission) }}"
                               class="btn btn-sm btn-outline-primary" title="Edit">
                                <i class="fas fa-pen"></i>
                            </a>
                            @endhasPermission
                            @hasPermission('user-management.permissions.delete')
                            <form method="POST"
                                  action="{{ route('user-management.permissions.destroy', $permission) }}"
                                  onsubmit="return confirm('Hapus permission ini?')">
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
    <div class="um-table-footer">
        <span>Menampilkan {{ $permissions->firstItem() }}–{{ $permissions->lastItem() }} dari {{ $permissions->total() }}</span>
        {{ $permissions->links() }}
    </div>
    @endif
</div>

@include('user-management._partials._toast')
@endsection