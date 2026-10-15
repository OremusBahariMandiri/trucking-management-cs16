@extends('layouts.admin')
@section('title', 'Manajemen Permission')
@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
@endpush

@section('content')

    <div class="um-page-header mb-4">
        <div>
            <div class="um-title">
                <span data-en="Permission Management">Manajemen Permission</span>
            </div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" data-en="Home">Beranda</a></li>
                    <li class="breadcrumb-item active" data-en="Permissions">Permission</li>
                </ol>
            </nav>
        </div>
        @hasPermission('user-management.permissions.create')
            <a href="{{ route('user-management.permissions.create') }}"
               class="btn btn-primary btn-sm d-flex align-items-center gap-2">
                <i class="fas fa-plus"></i>
                <span data-en="Add Permission">Tambah Permission</span>
            </a>
        @endhasPermission
    </div>

    <div class="um-filter-bar mb-3">
        <form method="GET" action="{{ route('user-management.permissions.index') }}" class="row g-2 align-items-end">
            <div class="col-12 col-md-6">
                <div class="input-group input-group-sm">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" name="search" class="form-control"
                           value="{{ request('search') }}"
                           data-placeholder-en="Name or slug…"
                           placeholder="Nama atau slug…">
                </div>
            </div>
            <div class="col-12 col-md-4">
                <select name="group" class="form-select form-select-sm">
                    <option value="" data-en="All Groups">Semua Group</option>
                    @foreach($groups as $g)
                        <option value="{{ $g }}" @selected(request('group') === $g)>{{ $g }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm flex-fill">
                    <i class="fas fa-filter me-1"></i><span data-en="Filter">Filter</span>
                </button>
                @if(request()->hasAny(['search','group']))
                    <a href="{{ route('user-management.permissions.index') }}"
                       class="btn btn-outline-secondary btn-sm flex-fill">
                        <i class="fas fa-times-circle me-1"></i><span data-en="Reset">Reset</span>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <div class="um-table-card" id="permTableCard">
        <div class="um-table-header">
            <h6>
                <i class="fas fa-list"></i>
                <span data-en="Permission List">Daftar Permission</span>
            </h6>
            <small style="color:var(--text-faint)">
                {{ $permissions->total() }} <span data-en="records">data</span>
            </small>
        </div>

        @if($permissions->isEmpty())
            <div class="um-empty">
                <div class="um-empty-icon"><i class="fas fa-key"></i></div>
                <h6 data-en="No permissions found">Belum ada permission</h6>
                <p data-en="Run the seeder or add a permission manually.">Jalankan seeder atau tambah permission secara manual.</p>
            </div>
        @else

            {{-- Desktop table --}}
            <div class="um-desktop-table" id="desktopTable">
                <table class="table" id="permTable">
                    <thead>
                        <tr>
                            <th style="width:3rem" class="text-center">No</th>
                            <th data-en="Name">Nama</th>
                            <th data-en="Slug">Slug</th>
                            <th data-en="Group">Group</th>
                            <th class="text-end pe-3" data-en="Actions">Aksi</th>
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
                                    <div class="d-flex justify-content-end gap-1 pe-1">
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

            {{-- Mobile cards --}}
            <div class="um-mobile-list" id="mobileList">
                @foreach($permissions as $permission)
                    <div class="um-mobile-card">
                        <div class="um-mobile-card-main">
                            <div style="width:36px;height:36px;border-radius:var(--um-radius-sm);background:var(--um-accent-soft);color:var(--um-accent);display:flex;align-items:center;justify-content:center;font-size:.9rem;flex-shrink:0">
                                <i class="fas fa-key"></i>
                            </div>
                            <div class="um-mobile-card-info">
                                <div class="um-name">{{ $permission->name }}</div>
                                <div class="um-mobile-card-meta mt-1">
                                    <span>
                                        <i class="fas fa-code fa-xs"></i>
                                        <code style="font-size:.75rem">{{ $permission->slug }}</code>
                                    </span>
                                    <span>
                                        <i class="fas fa-layer-group fa-xs"></i>
                                        {{ $permission->group }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="um-mobile-card-actions">
                            @hasPermission('user-management.permissions.update')
                                <a href="{{ route('user-management.permissions.edit', $permission) }}"
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-pen me-1"></i><span data-en="Edit">Edit</span>
                                </a>
                            @endhasPermission
                            @hasPermission('user-management.permissions.delete')
                                <form method="POST"
                                      action="{{ route('user-management.permissions.destroy', $permission) }}"
                                      onsubmit="return confirm('Hapus permission ini?')">
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
                <span>
                    <span data-en="Showing">Menampilkan</span>
                    {{ $permissions->firstItem() }}–{{ $permissions->lastItem() }}
                    <span data-en="of">dari</span>
                    {{ $permissions->total() }}
                </span>
                {{ $permissions->links() }}
            </div>
        @endif
    </div>

    @include('user-management._partials._toast')

@endsection

@push('scripts')
    <script>
        (function () {
            const card    = document.getElementById('permTableCard');
            const desktop = document.getElementById('desktopTable');
            const mobile  = document.getElementById('mobileList');
            const table   = document.getElementById('permTable');

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