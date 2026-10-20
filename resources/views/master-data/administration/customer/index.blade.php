@extends('layouts.admin')
@section('title', 'Pelanggan')
@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
@endpush
@section('content')

    <div class="veh-page-header mb-4">
        <div>
            <div class="veh-title">
                <span data-en="Customers">Pelanggan</span>
            </div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" data-en="Home">Beranda</a></li>
                    <li class="breadcrumb-item" data-en="Master Data">Data Master</li>
                    <li class="breadcrumb-item" data-en="Administration">Administrasi</li>
                    <li class="breadcrumb-item active" data-en="Customers">Pelanggan</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex align-items-center gap-2">
            <button type="button" class="btn btn-secondary btn-sm d-flex align-items-center gap-2"
                data-bs-toggle="modal" data-bs-target="#filterModal">
                <i class="fas fa-filter"></i>
                <span data-en="Filter">Filter</span>
                @if(request()->hasAny(['q','status','customer_type_id','business_type_id']))
                    <span class="badge bg-primary rounded-pill">
                        {{ collect(['q','status','customer_type_id','business_type_id'])->filter(fn($k)=>request()->filled($k))->count() }}
                    </span>
                @endif
            </button>
            <a href="{{ route('master-data.administration.customers.create') }}"
                class="btn btn-primary btn-sm d-flex align-items-center gap-2">
                <i class="fas fa-plus"></i>
                <span data-en="Add">Tambah</span>
            </a>
        </div>
    </div>

    @include('components.alert')

    <div class="veh-table-card">
        <div class="veh-table-header">
            <span data-en="Customer List">Daftar Pelanggan</span>
            <small style="color:var(--text-faint)">
                {{ $customers->total() }} <span data-en="records">data</span>
            </small>
        </div>

        @if($customers->isEmpty())
            <div class="veh-empty">
                <div class="veh-empty-icon"><i class="fas fa-users"></i></div>
                <h6 data-en="No customers found">Belum ada data pelanggan</h6>
                <p data-en="Add the first customer.">Tambah pelanggan pertama.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th data-en="No">No</th>
                            <th data-en="Code">Kode</th>
                            <th data-en="Name">Nama</th>
                            <th data-en="Customer Type">Jenis Pelanggan</th>
                            <th data-en="Business Type">Jenis Usaha</th>
                            <th data-en="City">Kota</th>
                            <th data-en="PIC">PIC</th>
                            <th data-en="Status">Status</th>
                            <th class="text-end" data-en="Actions">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($customers as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->code ?: '—' }}</td>
                            <td>
                                <a href="{{ route('master-data.administration.customers.show', $item) }}">
                                    {{ $item->name }}
                                </a>
                            </td>
                            <td>{{ $item->customerType?->name ?: '—' }}</td>
                            <td>{{ $item->businessType?->name ?: '—' }}</td>
                            <td>{{ $item->city ?: '—' }}</td>
                            <td>{{ $item->primaryPic?->name ?: '—' }}</td>
                            <td>
                                <span data-en="{{ $item->is_active ? 'Active' : 'Inactive' }}">
                                    {{ $item->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex justify-content-end gap-1">
                                    <a href="{{ route('master-data.administration.customers.show', $item) }}"
                                        class="btn btn-sm btn-outline-info" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('master-data.administration.customers.edit', $item) }}"
                                        class="btn btn-sm btn-outline-primary" title="Edit">
                                        <i class="fas fa-pen"></i>
                                    </a>
                                    <form method="POST" action="{{ route('master-data.administration.customers.destroy', $item) }}"
                                        onsubmit="return confirm('Hapus pelanggan ini?')">
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
                    {{ $customers->firstItem() }}–{{ $customers->lastItem() }}
                    <span data-en="of">dari</span>
                    {{ $customers->total() }}
                </span>
                {{ $customers->withQueryString()->links() }}
            </div>
        @endif
    </div>

    {{-- Filter Modal --}}
    <div class="modal fade" id="filterModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="GET" action="{{ route('master-data.administration.customers.index') }}">
                    <div class="modal-header">
                        <h6 class="modal-title">
                            <i class="fas fa-filter me-2"></i>
                            <span data-en="Filter Customers">Filter Pelanggan</span>
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
                            <div class="col-md-6">
                                <label class="form-label" data-en="Customer Type">Jenis Pelanggan</label>
                                <select name="customer_type_id" class="form-select">
                                    <option value="" data-en="— All —">— Semua —</option>
                                    @foreach($customerTypes as $ct)
                                        <option value="{{ $ct->id }}" {{ request('customer_type_id')==$ct->id?'selected':'' }}>
                                            {{ $ct->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" data-en="Business Type">Jenis Usaha</label>
                                <select name="business_type_id" class="form-select">
                                    <option value="" data-en="— All —">— Semua —</option>
                                    @foreach($businessTypes as $bt)
                                        <option value="{{ $bt->id }}" {{ request('business_type_id')==$bt->id?'selected':'' }}>
                                            {{ $bt->name }}
                                        </option>
                                    @endforeach
                                </select>
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
                        <a href="{{ route('master-data.administration.customers.index') }}"
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
