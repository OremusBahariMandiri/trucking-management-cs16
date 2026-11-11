@extends('layouts.admin')
@section('title', 'Jenis Usaha')
@section('content')
@php
    $canCreate = auth()->user()->hasPermission('master-data.administration.business-types.create');
    $canEdit   = auth()->user()->hasPermission('master-data.administration.business-types.edit');
    $canDelete = auth()->user()->hasPermission('master-data.administration.business-types.delete');
@endphp

<div class="veh-page-header mb-4">
    <div>
        <div class="veh-title" data-en="Business Types">Jenis Usaha</div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" data-en="Home">Beranda</a></li>
                <li class="breadcrumb-item" data-en="Master Data">Data Master</li>
                <li class="breadcrumb-item" data-en="Administration">Administrasi</li>
                <li class="breadcrumb-item active" data-en="Business Types">Jenis Usaha</li>
            </ol>
        </nav>
    </div>
    <div class="d-flex align-items-center gap-2">
        @if($canCreate)
        <a href="{{ route('master-data.administration.business-types.create') }}"
           class="btn btn-primary btn-sm d-flex align-items-center gap-2">
            <i class="fas fa-plus"></i> <span data-en="Add">Tambah</span>
        </a>
        @else
        <button type="button" class="btn btn-primary btn-sm d-flex align-items-center gap-2" disabled
                title="Anda tidak memiliki akses untuk menambah data">
            <i class="fas fa-plus"></i> <span>Tambah</span>
        </button>
        @endif
    </div>
</div>

<div class="veh-table-card">
    <div class="veh-table-header">
        <span data-en="Business Type List">Daftar Jenis Usaha</span>
        <small style="color:var(--text-faint)">{{ $businessTypes->count() }} <span data-en="records">data</span></small>
    </div>

    @if($businessTypes->isEmpty())
        <div class="p-4 text-center" style="color:var(--text-muted)">
            <i class="fas fa-briefcase fa-2x mb-2 d-block" style="opacity:.3"></i>
            Belum ada data jenis usaha.
        </div>
    @else

    {{-- Desktop --}}
    <div class="lkp-desktop-table">
        <div class="table-responsive">
            <table id="businessTypeTable" class="table" data-lookup-table data-col-targets='[0,5]' style="width:100%">
                <thead>
                    <tr>
                        <th style="width:50px" data-en="No">No</th>
                        <th data-en="Code">Kode</th>
                        <th data-en="Name">Nama</th>
                        <th data-en="Description">Deskripsi</th>
                        <th data-en="Status">Status</th>
                        <th class="text-end" style="width:100px" data-en="Actions">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($businessTypes as $item)
                    <tr data-row="{{ $loop->index }}">
                        <td></td>
                        <td>{{ $item->code ?: '—' }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ Str::limit($item->description, 60) ?: '—' }}</td>
                        <td>
                            @if($item->is_active)
                                <span class="bt-status-active" data-en="Active">Aktif</span>
                            @else
                                <span class="bt-status-inactive" data-en="Inactive">Tidak Aktif</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex justify-content-end gap-1">
                                @if($canEdit)
                                <a href="{{ route('master-data.administration.business-types.edit', $item) }}"
                                   class="btn btn-sm btn-outline-primary" title="Edit">
                                    <i class="fas fa-pen"></i>
                                </a>
                                @else
                                <button type="button" class="btn btn-sm btn-outline-primary" disabled title="Tidak ada akses edit"><i class="fas fa-pen"></i></button>
                                @endif
                                @if($canDelete)
                                <form method="POST"
                                      action="{{ route('master-data.administration.business-types.destroy', $item) }}"
                                      class="js-delete-form" data-name="{{ $item->name }}">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @else
                                <button type="button" class="btn btn-sm btn-outline-danger" disabled title="Tidak ada akses hapus"><i class="fas fa-trash"></i></button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Mobile --}}
    <div class="lkp-mobile-list">
        @foreach($businessTypes as $item)
        <div class="lkp-mob-card" data-row="{{ $loop->index }}">
            <div class="lkp-mob-header">
                <div class="lkp-mob-initial">{{ strtoupper(substr($item->name, 0, 1)) }}</div>
                <div class="lkp-mob-name-wrap">
                    <div class="lkp-mob-name">{{ $item->name }}</div>
                    @if($item->code)
                    <div class="lkp-mob-sub">{{ $item->code }}</div>
                    @endif
                </div>
                <i class="fas fa-chevron-down lkp-mob-chevron"></i>
            </div>
            <div class="lkp-mob-details">
                <div class="lkp-mob-detail-row">
                    <span class="lkp-mob-label">Kode</span>
                    <span class="lkp-mob-value">{{ $item->code ?: '—' }}</span>
                </div>
                @if($item->description)
                <div class="lkp-mob-detail-row">
                    <span class="lkp-mob-label">Deskripsi</span>
                    <span class="lkp-mob-value">{{ Str::limit($item->description, 80) }}</span>
                </div>
                @endif
                <div class="lkp-mob-detail-row">
                    <span class="lkp-mob-label">Status</span>
                    <span class="lkp-mob-value">{{ $item->is_active ? 'Aktif' : 'Tidak Aktif' }}</span>
                </div>
                <div class="lkp-mob-actions">
                    @if($canEdit)
                    <a href="{{ route('master-data.administration.business-types.edit', $item) }}"
                       class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-pen me-1"></i>Edit
                    </a>
                    @else
                    <button type="button" class="btn btn-sm btn-outline-primary" disabled title="Tidak ada akses edit"><i class="fas fa-pen me-1"></i>Edit</button>
                    @endif
                    @if($canDelete)
                    <form method="POST"
                          action="{{ route('master-data.administration.business-types.destroy', $item) }}"
                          class="js-delete-form" data-name="{{ $item->name }}">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                    @else
                    <button type="button" class="btn btn-sm btn-outline-danger" disabled title="Tidak ada akses hapus"><i class="fas fa-trash"></i></button>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
        <div class="lkp-mob-footer">
            <div class="lkp-mob-pagination-info"></div>
            <div class="lkp-mob-pagination-nav">
                <button class="btn btn-sm btn-outline-secondary lkp-mob-prev" disabled>&#8249;</button>
                <span class="lkp-mob-page-num"></span>
                <button class="btn btn-sm btn-outline-secondary lkp-mob-next">&#8250;</button>
            </div>
        </div>
    </div>

    @endif
</div>

@endsection
@push('scripts')
    @vite('resources/js/pages/master-data.js')
@endpush
