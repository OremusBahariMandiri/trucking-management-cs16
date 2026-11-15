@extends('layouts.admin')
@section('title', 'Manajemen Role')
@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
@endpush

@section('content')

    <div class="um-page-header mb-4">
        <div>
            <div class="um-title">
                <span data-en="Role Management">Manajemen Role</span>
            </div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" data-en="Home">Beranda</a></li>
                    <li class="breadcrumb-item active" data-en="Roles">Role</li>
                </ol>
            </nav>
        </div>
        @hasPermission('user-management.roles.create')
            <a href="{{ route('user-management.roles.create') }}"
                class="btn btn-primary btn-sm d-flex align-items-center gap-2">
                <i class="fas fa-plus"></i>
                <span data-en="Add Role">Tambah Role</span>
            </a>
        @endhasPermission
    </div>

    <div class="um-filter-bar mb-3">
        <form method="GET" action="{{ route('user-management.roles.index') }}" class="row g-2 align-items-end">
            <div class="col-12 col-md-10">
                <div class="input-group input-group-sm">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" name="search" class="form-control" value="{{ request('search') }}"
                        data-placeholder-en="Role name or slug…"
                        placeholder="Nama atau slug role…">
                </div>
            </div>
            <div class="col-12 col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm flex-fill">
                    <i class="fas fa-filter me-1"></i><span data-en="Filter">Filter</span>
                </button>
                @if (request('search'))
                    <a href="{{ route('user-management.roles.index') }}"
                       class="btn btn-outline-secondary btn-sm flex-fill">
                        <i class="fas fa-times-circle me-1"></i><span data-en="Reset">Reset</span>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <div class="um-table-card" id="roleTableCard">
        <div class="um-table-header">
            <h6>
                <i class="fas fa-list"></i>
                <span data-en="Role List">Daftar Role</span>
            </h6>
            <small style="color:var(--text-faint)">
                {{ $roles->total() }} <span data-en="records">data</span>
            </small>
        </div>

        @if ($roles->isEmpty())
            <div class="um-empty">
                <div class="um-empty-icon"><i class="fas fa-user-tag"></i></div>
                <h6 data-en="No roles found">Belum ada role</h6>
                <p data-en="Add a role to manage user access rights.">Tambah role untuk mengelola hak akses user.</p>
            </div>
        @else

            {{-- Desktop table --}}
            <div class="um-desktop-table" id="desktopTable">
                <table class="table" id="roleTable">
                    <thead>
                        <tr>
                            <th style="width:3rem" class="text-center">No</th>
                            <th data-en="Name">Nama</th>
                            <th data-en="Slug">Slug</th>
                            <th class="text-center" data-en="Permissions">Permission</th>
                            <th class="text-center" data-en="Users">User</th>
                            <th class="text-end pe-3" data-en="Actions">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($roles as $role)
                            <tr>
                                <td class="text-center" style="font-size:.82rem;color:var(--text-muted)">
                                    {{ $roles->firstItem() + $loop->index }}
                                </td>
                                <td>
                                    <span class="um-name">{{ $role->name }}</span>
                                    @if ($role->description)
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
                                    <div class="d-flex justify-content-end gap-1 pe-1">
                                        <a href="{{ route('user-management.roles.show', $role) }}"
                                            class="btn btn-sm btn-outline-secondary" title="Detail Role">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @hasPermission('user-management.roles.edit')
                                            <a href="{{ route('user-management.roles.manage-permissions', $role) }}"
                                                class="btn btn-sm btn-outline-warning" title="Kelola Permission">
                                                <i class="fas fa-lock"></i>
                                            </a>
                                        @endhasPermission
                                        @hasPermission('user-management.roles.update')
                                            <a href="{{ route('user-management.roles.edit', $role) }}"
                                                class="btn btn-sm btn-outline-primary" title="Edit">
                                                <i class="fas fa-pen"></i>
                                            </a>
                                        @endhasPermission
                                        @hasPermission('user-management.roles.delete')
                                            @if ($role->slug !== 'superadmin')
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

            {{-- Mobile cards --}}
            <div class="um-mobile-list" id="mobileList">
                @foreach ($roles as $role)
                    <div class="um-mobile-card">
                        <div class="um-mobile-card-main">
                            <div class="um-role-icon" style="width:36px;height:36px;border-radius:var(--um-radius-sm);background:var(--um-accent-soft);color:var(--um-accent);display:flex;align-items:center;justify-content:center;font-size:.9rem;flex-shrink:0">
                                <i class="fas fa-user-tag"></i>
                            </div>
                            <div class="um-mobile-card-info">
                                <div class="um-name">{{ $role->name }}</div>
                                @if ($role->description)
                                    <div style="font-size:.75rem;color:var(--text-faint);margin-top:.1rem">
                                        {{ $role->description }}
                                    </div>
                                @endif
                                <div class="um-mobile-card-meta mt-1">
                                    <span>
                                        <i class="fas fa-code fa-xs"></i>
                                        <code style="font-size:.75rem">{{ $role->slug }}</code>
                                    </span>
                                    <span>
                                        <i class="fas fa-key fa-xs"></i>
                                        {{ $role->permissions_count }}
                                        <span data-en="permissions">permission</span>
                                    </span>
                                    <span>
                                        <i class="fas fa-users fa-xs"></i>
                                        {{ $role->users_count }}
                                        <span data-en="users">user</span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="um-mobile-card-actions">
                            <a href="{{ route('user-management.roles.show', $role) }}"
                               class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-eye me-1"></i><span data-en="Detail">Detail</span>
                            </a>
                            @hasPermission('user-management.roles.edit')
                                <a href="{{ route('user-management.roles.manage-permissions', $role) }}"
                                   class="btn btn-sm btn-outline-warning">
                                    <i class="fas fa-lock me-1"></i><span>Permission</span>
                                </a>
                            @endhasPermission
                            @hasPermission('user-management.roles.update')
                                <a href="{{ route('user-management.roles.edit', $role) }}"
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-pen me-1"></i><span data-en="Edit">Edit</span>
                                </a>
                            @endhasPermission
                            @hasPermission('user-management.roles.delete')
                                @if ($role->slug !== 'superadmin')
                                    <form method="POST"
                                        action="{{ route('user-management.roles.destroy', $role) }}"
                                        onsubmit="return confirm('Hapus role {{ $role->name }}?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            @endhasPermission
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="um-table-footer">
                <span>
                    <span data-en="Showing">Menampilkan</span>
                    {{ $roles->firstItem() }}–{{ $roles->lastItem() }}
                    <span data-en="of">dari</span>
                    {{ $roles->total() }}
                </span>
                {{ $roles->links() }}
            </div>
        @endif
    </div>

    @include('user-management._partials._toast')

@endsection

@push('scripts')
    <script>
        (function () {
            const card    = document.getElementById('roleTableCard');
            const desktop = document.getElementById('desktopTable');
            const mobile  = document.getElementById('mobileList');
            const table   = document.getElementById('roleTable');

            if (!card || !desktop || !mobile || !table) return;

            function check() {
                desktop.style.display = 'block';
                mobile.style.display  = 'none';
                const overflows = table.scrollWidth > card.clientWidth + 2;
                if (overflows) {
                    desktop.style.display = 'none';
                    mobile.style.display  = 'flex';
                }
            }

            check();

            if (window.ResizeObserver) {
                new ResizeObserver(check).observe(card);
            } else {
                window.addEventListener('resize', check);
            }
        })();
    </script>
    @vite('resources/js/pages/user-management.js')
@endpush