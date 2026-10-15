@extends('layouts.admin')

@section('title', 'Daftar Karyawan')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
@endpush

@section('content')

{{-- ── Page Header ──────────────────────────────────────────── --}}
<div class="emp-page-header mb-4">
    <div>
        <div class="emp-title">
            <i class="fas fa-users"></i>
            <span data-en="Employee Management">Manajemen Karyawan</span>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" data-en="Home">Beranda</a></li>
                <li class="breadcrumb-item active" data-en="Employees">Karyawan</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('data-management.sdm-management.employee-data.create') }}"
       class="btn btn-primary btn-sm d-flex align-items-center gap-2">
        <i class="fas fa-plus"></i>
        <span data-en="Add Employee">Tambah Karyawan</span>
    </a>
</div>

{{-- ── Filter Bar ───────────────────────────────────────────── --}}
<div class="emp-filter-bar mb-3">
    <form method="GET" action="{{ route('data-management.sdm-management.employee-data.index') }}"
          class="row g-2 align-items-end">
        <div class="col-12 col-sm-6 col-md-4">
            <label class="form-label form-label-sm" data-en="Search">Cari</label>
            <div class="input-group input-group-sm">
                <span class="input-group-text"><i class="fas fa-search"></i></span>
                <input type="text" name="search" class="form-control"
                       value="{{ request('search') }}"
                       placeholder="NRK, nama, departemen…">
            </div>
        </div>
        <div class="col-6 col-md-auto">
            <button type="submit" class="btn btn-primary btn-sm w-100">
                <i class="fas fa-filter me-1"></i>
                <span data-en="Filter">Filter</span>
            </button>
        </div>
        @if(request()->hasAny(['search']))
        <div class="col-6 col-md-auto">
            <a href="{{ route('data-management.sdm-management.employee-data.index') }}"
               class="btn btn-outline-secondary btn-sm w-100">
                <i class="fas fa-times-circle me-1"></i>
                <span data-en="Reset">Reset</span>
            </a>
        </div>
        @endif
    </form>
</div>

{{-- ── Table Card ───────────────────────────────────────────── --}}
<div class="emp-table-card">
    <div class="emp-table-header">
        <h6>
            <i class="fas fa-list-alt"></i>
            <span data-en="Employee List">Daftar Karyawan</span>
        </h6>
        <small style="color:var(--text-faint)">
            {{ $employees->total() }} <span data-en="records">data</span>
        </small>
    </div>

    @if($employees->isEmpty())
    <div class="emp-empty">
        <div class="emp-empty-icon"><i class="fas fa-users"></i></div>
        <h6 data-en="No employees found">Tidak ada karyawan ditemukan</h6>
        <p data-en="Try adjusting your filter or add a new employee.">
            Coba ubah filter atau tambah karyawan baru.
        </p>
    </div>
    @else
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th style="width:3rem" class="text-center">No</th>
                    <th data-en="NRK">NRK</th>
                    <th data-en="Name">Nama</th>
                    <th data-en="Department">Departemen</th>
                    <th data-en="Position">Jabatan</th>
                    <th data-en="Wilker">Wilker</th>
                    <th class="text-end" data-en="Actions">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($employees as $employee)
                <tr>
                    <td class="text-center" style="font-size:.82rem;color:var(--text-muted)">
                        {{ $employees->firstItem() + $loop->index }}
                    </td>
                    <td style="font-family:monospace;font-size:.83rem">
                        {{ $employee->nrk }}
                    </td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <span class="emp-name">{{ $employee->full_name }}</span>
                        </div>
                    </td>
                    <td style="font-size:.875rem;color:var(--text-muted)">
                        {{ $employee->department ?? '—' }}
                    </td>
                    <td style="font-size:.875rem;color:var(--text-muted)">
                        {{ $employee->position ?? '—' }}
                    </td>
                    <td style="font-size:.875rem;color:var(--text-muted)">
                        {{ $employee->wilker ?? '—' }}
                    </td>
                    <td>
                        <div class="d-flex justify-content-end gap-1">
                            <a href="{{ route('data-management.sdm-management.employee-data.show', $employee) }}"
                               class="btn btn-sm btn-outline-secondary" title="Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('data-management.sdm-management.employee-data.edit', $employee) }}"
                               class="btn btn-sm btn-outline-primary" title="Edit">
                                <i class="fas fa-pen"></i>
                            </a>
                            <form method="POST"
                                  action="{{ route('data-management.sdm-management.employee-data.destroy', $employee) }}"
                                  onsubmit="return confirm('Hapus karyawan ini?')">
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

    <div class="emp-table-footer">
        <span>
            <span data-en="Showing">Menampilkan</span>
            {{ $employees->firstItem() }}–{{ $employees->lastItem() }}
            <span data-en="of">dari</span>
            {{ $employees->total() }}
        </span>
        {{ $employees->withQueryString()->links() }}
    </div>
    @endif
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show position-fixed bottom-0 start-50 translate-middle-x mb-4"
     style="z-index:9999;min-width:320px" role="alert">
    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@endsection

@push('scripts')
    @vite('resources/js/pages/employee.js')
@endpush