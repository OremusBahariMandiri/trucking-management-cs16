@extends('layouts.admin')

@section('title', 'Merek Kendaraan')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
@endpush

@section('content')

<div class="veh-page-header mb-4">
    <div>
        <div class="veh-title">
            <span data-en="Vehicle Brands">Merek Kendaraan</span>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" data-en="Home">Beranda</a></li>
                <li class="breadcrumb-item active" data-en="Vehicle Brands">Merek Kendaraan</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('master-data.vehicle-brands.create') }}"
       class="btn btn-primary btn-sm d-flex align-items-center gap-2">
        <i class="fas fa-plus"></i>
        <span data-en="Add Brand">Tambah</span>
    </a>
</div>

{{-- Table --}}
<div class="veh-table-card">
    <div class="veh-table-header">
            <span data-en="Brand List">Daftar Merek Kendaraan</span>
        </h6>
        <small style="color:var(--text-faint)">
            {{ $vehicleBrands->total() }} <span data-en="records">data</span>
        </small>
    </div>

    @if($vehicleBrands->isEmpty())
    <div class="veh-empty">
        <div class="veh-empty-icon"><i class="fas fa-trademark"></i></div>
        <h6 data-en="No brands found">Belum ada merek kendaraan</h6>
        <p data-en="Add the first vehicle brand.">Tambah merek kendaraan pertama.</p>
    </div>
    @else
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th data-en="No">No</th>
                    <th data-en="Code">Kode</th>
                    <th data-en="Brand Name">Nama Merek</th>
                    <th data-en="Country of Origin">Negara Asal</th>
                    <th data-en="Models Count">Jml Model</th>
                    <th data-en="Status">Status</th>
                    <th class="text-end" data-en="Actions">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($vehicleBrands as $item)
                <tr>
                    <td class="lkp-sub">{{ $loop->iteration }}</td>
                    <td>
                        @if($item->code)
                            <span class="lkp-code-badge">{{ $item->code }}</span>
                        @else
                            <span class="lkp-sub">—</span>
                        @endif
                    </td>
                    <td><span class="lkp-name">{{ $item->name }}</span></td>
                    <td>
                        <span class="lkp-sub">
                            {{ $item->country_of_origin ?: '—' }}
                        </span>
                    </td>
                    <td>
                        <span class="lkp-code-badge">{{ $item->vehicle_models_count ?? 0 }}</span>
                    </td>
                    <td>
                        <span class="badge-status {{ $item->is_active ? 'status-active' : 'status-inactive' }}">
                            <span data-en="{{ $item->is_active ? 'Active' : 'Inactive' }}">
                                {{ $item->is_active ? 'Aktif' : 'Tidak Aktif' }}
                            </span>
                        </span>
                    </td>
                    <td>
                        <div class="d-flex justify-content-end gap-1">
                            <a href="{{ route('master-data.vehicle-brands.edit', $item) }}"
                               class="btn btn-sm btn-outline-primary" title="Edit">
                                <i class="fas fa-pen"></i>
                            </a>
                            <form method="POST"
                                  action="{{ route('master-data.vehicle-brands.destroy', $item) }}"
                                  onsubmit="return confirm('Hapus merek ini?')">
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
            {{ $vehicleBrands->firstItem() }}–{{ $vehicleBrands->lastItem() }}
            <span data-en="of">dari</span>
            {{ $vehicleBrands->total() }}
        </span>
        {{ $vehicleBrands->withQueryString()->links() }}
    </div>
    @endif
</div>

@endsection