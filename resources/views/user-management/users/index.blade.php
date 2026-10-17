@extends('layouts.admin')
@section('title', 'Manajemen User')
@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
@endpush

@section('content')

    <div class="um-page-header mb-4">
        <div>
            <div class="um-title"><span data-en="User Management">Manajemen User</span></div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" data-en="Home">Beranda</a></li>
                    <li class="breadcrumb-item active" data-en="Users">User</li>
                </ol>
            </nav>
        </div>
        @hasPermission('user-management.users.create')
            <a href="{{ route('user-management.users.create') }}"
                class="btn btn-primary btn-sm d-flex align-items-center gap-2">
                <i class="fas fa-plus"></i> <span data-en="Add User">Tambah User</span>
            </a>
        @endhasPermission
    </div>

    {{-- Filter --}}
    <div class="um-filter-bar mb-3">
        <form method="GET" action="{{ route('user-management.users.index') }}" class="row g-2 align-items-end">
            <div class="col-12 col-md-5">
                <label class="form-label form-label-sm" data-en="Search">Cari</label>
                <div class="input-group input-group-sm">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" name="search" class="form-control" value="{{ request('search') }}"
                        data-placeholder-en="Name, employee code, NIK…" placeholder="Nama, kode karyawan, NIK…">
                </div>
            </div>
            <div class="col-12 col-md-5">
                <label class="form-label form-label-sm" data-en="Role">Role</label>
                <select name="role" class="form-select form-select-sm">
                    <option value="" data-en="All Roles">Semua Role</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->slug }}" @selected(request('role') === $role->slug)>
                            {{ $role->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm flex-fill">
                    <i class="fas fa-filter me-1"></i><span data-en="Filter">Filter</span>
                </button>
                @if (request()->hasAny(['search', 'role']))
                    <a href="{{ route('user-management.users.index') }}" class="btn btn-outline-secondary btn-sm flex-fill">
                        <i class="fas fa-times-circle me-1"></i><span data-en="Reset">Reset</span>
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="um-table-card" id="userTableCard">
        <div class="um-table-header">
            <h6><i class="fas fa-list"></i> <span data-en="User List">Daftar User</span></h6>
            <small style="color:var(--text-faint)">{{ $users->total() }} <span data-en="records">data</span></small>
        </div>

        @if ($users->isEmpty())
            <div class="um-empty">
                <div class="um-empty-icon"><i class="fas fa-users-cog"></i></div>
                <h6 data-en="No users found">Tidak ada user ditemukan</h6>
                <p data-en="Try adjusting your filter or add a new user.">Coba ubah filter atau tambah user baru.</p>
            </div>
        @else
            {{-- Desktop table --}}
            <div class="um-desktop-table" id="desktopTable">
                <table class="table" id="userTable">
                    <thead>
                        <tr>
                            <th style="width:3rem" class="text-center">No</th>
                            <th data-en="NRK">NRK</th>
                            <th data-en="Name">Nama</th>
                            <th data-en="Position">Jabatan</th>
                            <th data-en="Role">Role</th>
                            <th class="text-end pe-3" data-en="Actions">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td class="text-center" style="font-size:.82rem;color:var(--text-muted)">
                                    {{ $users->firstItem() + $loop->index }}
                                </td>
                                <td style="font-family:monospace;font-size:.83rem">
                                    {{ $user->employee_id_number ?? '—' }}
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div>
                                            <div class="um-name">{{ $user->full_name }}</div>
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
                                            <form method="POST" action="{{ route('user-management.users.destroy', $user) }}"
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

            {{-- Mobile cards --}}
            <div class="um-mobile-list" id="mobileList">
                @foreach ($users as $user)
                    <div class="um-mobile-card">
                        <div class="um-mobile-card-main">
                            <div class="um-avatar">{{ strtoupper(substr($user->full_name, 0, 1)) }}</div>
                            <div class="um-mobile-card-info">
                                <div class="um-name">{{ $user->full_name }}</div>
                                <div class="um-mobile-card-meta">
                                    @if ($user->employee_id_number)
                                        <span><i class="fas fa-id-badge fa-xs"></i> {{ $user->employee_id_number }}</span>
                                    @endif
                                    @if ($user->position)
                                        <span><i class="fas fa-briefcase fa-xs"></i> {{ $user->position }}</span>
                                    @endif
                                </div>
                                <div class="mt-1">
                                    @forelse($user->roles as $role)
                                        <span class="role-badge" style="font-size:.68rem">{{ $role->name }}</span>
                                    @empty
                                        <span style="font-size:.75rem;color:var(--text-faint)"
                                            data-en="No role assigned">Belum ada role</span>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                        <div class="um-mobile-card-actions">
                            <a href="{{ route('user-management.users.show', $user) }}"
                                class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-eye me-1"></i><span data-en="Detail">Detail</span>
                            </a>
                            @hasPermission('user-management.users.update')
                                <a href="{{ route('user-management.users.edit', $user) }}"
                                    class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-pen me-1"></i><span data-en="Edit">Edit</span>
                                </a>
                            @endhasPermission
                            @hasPermission('user-management.users.delete')
                                <form method="POST" action="{{ route('user-management.users.destroy', $user) }}"
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
                <span>
                    <span data-en="Showing">Menampilkan</span>
                    {{ $users->firstItem() }}–{{ $users->lastItem() }}
                    <span data-en="of">dari</span>
                    {{ $users->total() }}
                </span>
                {{ $users->links() }}
            </div>
        @endif
    </div>

    @include('user-management._partials._toast')

    @push('scripts')
        <script>
            (function() {
                const card = document.getElementById('userTableCard');
                const desktop = document.getElementById('desktopTable');
                const mobile = document.getElementById('mobileList');
                const table = document.getElementById('userTable');

                if (!card || !desktop || !mobile || !table) return;

                function check() {
                    desktop.style.display = 'block';
                    mobile.style.display = 'none';
                    const overflows = table.scrollWidth > card.clientWidth + 2;
                    if (overflows) {
                        desktop.style.display = 'none';
                        mobile.style.display = 'flex';
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
@endsection
