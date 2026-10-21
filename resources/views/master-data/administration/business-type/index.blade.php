@extends('layouts.admin')
@section('title', 'Jenis Usaha')
@section('content')

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
            <a href="{{ route('master-data.administration.business-types.create') }}"
                class="btn btn-primary btn-sm d-flex align-items-center gap-2">
                <i class="fas fa-plus"></i> <span data-en="Add">Tambah</span>
            </a>
        </div>
    </div>


    <div class="veh-table-card">
        <div class="veh-table-header">
            <span data-en="Business Type List">Daftar Jenis Usaha</span>
            <small style="color:var(--text-faint)">{{ $businessTypes->count() }} <span data-en="records">data</span></small>
        </div>

        <div class="table-responsive">
            <table id="businessTypeTable" class="table" data-lookup-table data-col-targets='[0, 5]' style="width:100%">
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
                    <tr>
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
                                <a href="{{ route('master-data.administration.business-types.edit', $item) }}"
                                    class="btn btn-sm btn-outline-primary" title="Edit">
                                    <i class="fas fa-pen"></i>
                                </a>
                                <form method="POST"
                                    action="{{ route('master-data.administration.business-types.destroy', $item) }}"
                                    class="js-delete-form"
                                    data-name="{{ $item->name }}">
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
    </div>



@endsection

@push('scripts')
    @vite('resources/js/pages/master-data.js')
@endpush
