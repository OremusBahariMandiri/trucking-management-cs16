@extends('layouts.admin')
@section('title', 'Jenis Usaha')
@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
@endpush
@section('content')

    <div class="veh-page-header mb-4">
        <div>
            <div class="veh-title">
                <span data-en="Business Types">Jenis Usaha</span>
            </div>
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
            <button type="button" class="btn btn-secondary btn-sm d-flex align-items-center gap-2"
                data-bs-toggle="modal" data-bs-target="#filterModal">
                <i class="fas fa-filter"></i>
                <span data-en="Filter">Filter</span>
                @if(request()->hasAny(['q','status']))
                    <span class="badge bg-primary rounded-pill">{{ collect(['q','status'])->filter(fn($k)=>request()->filled($k))->count() }}</span>
                @endif
            </button>
            <a href="{{ route('master-data.administration.business-types.create') }}"
                class="btn btn-primary btn-sm d-flex align-items-center gap-2">
                <i class="fas fa-plus"></i>
                <span data-en="Add">Tambah</span>
            </a>
        </div>
    </div>

    @include('components.alert')

    <div class="veh-table-card">
        <div class="veh-table-header">
            <span data-en="Business Type List">Daftar Jenis Usaha</span>
            <small style="color:var(--text-faint)">
                {{ $businessTypes->total() }} <span data-en="records">data</span>
            </small>
        </div>

        @if($businessTypes->isEmpty())
            <div class="veh-empty">
                <div class="veh-empty-icon"><i class="fas fa-briefcase"></i></div>
                <h6 data-en="No business types found">Belum ada jenis usaha</h6>
                <p data-en="Add the first business type.">Tambah jenis usaha pertama.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th data-en="No">No</th>
                            <th data-en="Code">Kode</th>
                            <th data-en="Name">Nama</th>
                            <th data-en="Description">Deskripsi</th>
                            <th data-en="Status">Status</th>
                            <th class="text-end" data-en="Actions">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($businessTypes as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->code ?: '—' }}</td>
                            <td>{{ $item->name }}</td>
                            <td>{{ Str::limit($item->description, 60) ?: '—' }}</td>
                            <td>
                                <span data-en="{{ $item->is_active ? 'Active' : 'Inactive' }}">
                                    {{ $item->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex justify-content-end gap-1">
                                    <a href="{{ route('master-data.administration.business-types.edit', $item) }}"
                                        class="btn btn-sm btn-outline-primary" title="Edit">
                                        <i class="fas fa-pen"></i>
                                    </a>
                                    <form method="POST" action="{{ route('master-data.administration.business-types.destroy', $item) }}"
                                        onsubmit="return confirm('Hapus jenis usaha ini?')">
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
            <div class="veh-table-footer">
                <span>
                    <span data-en="Showing">Menampilkan</span>
                    {{ $businessTypes->firstItem() }}–{{ $businessTypes->lastItem() }}
                    <span data-en="of">dari</span>
                    {{ $businessTypes->total() }}
                </span>
                {{ $businessTypes->withQueryString()->links() }}
            </div>
        @endif
    </div>

    <div class="modal fade" id="filterModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="GET" action="{{ route('master-data.administration.business-types.index') }}">
                    <div class="modal-header">
                        <h6 class="modal-title">
                            <i class="fas fa-filter me-2"></i>
                            <span data-en="Filter Business Types">Filter Jenis Usaha</span>
                        </h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label" data-en="Keyword">Kata Kunci</label>
                                <input type="text" name="q" class="form-control"
                                    placeholder="Cari nama atau kode…" value="{{ request('q') }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label" data-en="Status">Status</label>
                                <select name="status" class="form-select">
                                    <option value="" data-en="— All Status —">— Semua Status —</option>
                                    <option value="1" {{ request('status')==='1'?'selected':'' }} data-en="Active">Aktif</option>
                                    <option value="0" {{ request('status')==='0'?'selected':'' }} data-en="Inactive">Tidak Aktif</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <a href="{{ route('master-data.administration.business-types.index') }}"
                            class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-times me-1"></i><span data-en="Reset">Reset</span>
                        </a>
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="fas fa-search me-1"></i><span data-en="Apply Filter">Terapkan Filter</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
