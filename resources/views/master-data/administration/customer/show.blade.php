@extends('layouts.admin')
@section('title', 'Detail Pelanggan')
@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
@endpush
@section('content')

    <div class="veh-page-header mb-4">
        <div>
            <div class="veh-title">
                <span data-en="Customer Detail">Detail Pelanggan</span>
            </div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" data-en="Home">Beranda</a></li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('master-data.administration.customers.index') }}" data-en="Customers">Pelanggan</a>
                    </li>
                    <li class="breadcrumb-item active">{{ $customer->name }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('master-data.administration.customers.edit', $customer) }}"
                class="btn btn-primary btn-sm">
                <i class="fas fa-pen me-1"></i>
                <span data-en="Edit">Edit</span>
            </a>
            <form method="POST" action="{{ route('master-data.administration.customers.destroy', $customer) }}"
                onsubmit="return confirm('Hapus pelanggan ini?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-outline-danger btn-sm">
                    <i class="fas fa-trash me-1"></i>
                    <span data-en="Delete">Hapus</span>
                </button>
            </form>
        </div>
    </div>

    @include('components.alert')

    {{-- Informasi Pelanggan --}}
    <div class="lkp-form-card mb-4">
        <div class="lkp-form-header">
            <i class="fas fa-building"></i>
            <span data-en="Customer Information">Informasi Pelanggan</span>
            <span class="ms-auto badge {{ $customer->is_active ? 'bg-success' : 'bg-secondary' }}">
                {{ $customer->is_active ? 'Aktif' : 'Tidak Aktif' }}
            </span>
        </div>
        <div class="lkp-form-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="text-muted small mb-1" data-en="Registration Date">Tanggal Registrasi</div>
                    <div>{{ $customer->registration_date?->format('d M Y') ?: '—' }}</div>
                </div>
                <div class="col-md-6">
                    <div class="text-muted small mb-1" data-en="Customer Code">Kode Pelanggan</div>
                    <div>{{ $customer->customer_code ?: ($customer->code ?: '—') }}</div>
                </div>
                <div class="col-md-12">
                    <div class="text-muted small mb-1" data-en="Customer Name">Nama Pelanggan</div>
                    <div class="fw-semibold fs-6">{{ $customer->name }}</div>
                </div>
                <div class="col-md-6">
                    <div class="text-muted small mb-1" data-en="Customer Type">Jenis Pelanggan</div>
                    <div>{{ $customer->customerType?->name ?: '—' }}</div>
                </div>
                <div class="col-md-6">
                    <div class="text-muted small mb-1" data-en="Business Type">Jenis Usaha</div>
                    <div>{{ $customer->businessType?->name ?: '—' }}</div>
                </div>
                <div class="col-md-6">
                    <div class="text-muted small mb-1" data-en="Phone">Telepon</div>
                    <div>{{ $customer->phone ?: '—' }}</div>
                </div>
                <div class="col-md-6">
                    <div class="text-muted small mb-1" data-en="Email">Email</div>
                    <div>{{ $customer->email ?: '—' }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Legalitas --}}
    <div class="lkp-form-card mb-4">
        <div class="lkp-form-header">
            <i class="fas fa-file-invoice"></i>
            <span data-en="Legality">Legalitas</span>
        </div>
        <div class="lkp-form-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="text-muted small mb-1" data-en="NPWP Number">Nomor NPWP</div>
                    <div>{{ $customer->npwp_number ?: ($customer->npwp ?: '—') }}</div>
                </div>
                <div class="col-md-6">
                    <div class="text-muted small mb-1" data-en="NPWP File">File NPWP</div>
                    <div>
                        @if($customer->npwp_file_name)
                            <i class="fas fa-file me-1 text-muted"></i>{{ $customer->npwp_file_name }}
                        @else
                            —
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="text-muted small mb-1" data-en="NIB Number">Nomor NIB</div>
                    <div>{{ $customer->nib_number ?: '—' }}</div>
                </div>
                <div class="col-md-6">
                    <div class="text-muted small mb-1" data-en="NIB File">File NIB</div>
                    <div>
                        @if($customer->nib_file_name)
                            <i class="fas fa-file me-1 text-muted"></i>{{ $customer->nib_file_name }}
                        @else
                            —
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Alamat --}}
    <div class="lkp-form-card mb-4">
        <div class="lkp-form-header">
            <i class="fas fa-map-marker-alt"></i>
            <span data-en="Address">Alamat</span>
        </div>
        <div class="lkp-form-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="text-muted small mb-1" data-en="Province">Provinsi</div>
                    <div>{{ $customer->province ?: '—' }}</div>
                </div>
                <div class="col-md-6">
                    <div class="text-muted small mb-1" data-en="City">Kota</div>
                    <div>{{ $customer->city ?: '—' }}</div>
                </div>
                <div class="col-md-6">
                    <div class="text-muted small mb-1" data-en="Kecamatan">Kecamatan</div>
                    <div>{{ $customer->kecamatan ?: '—' }}</div>
                </div>
                <div class="col-md-6">
                    <div class="text-muted small mb-1" data-en="Kelurahan">Kelurahan</div>
                    <div>{{ $customer->kelurahan ?: '—' }}</div>
                </div>
                <div class="col-md-6">
                    <div class="text-muted small mb-1" data-en="RT/RW">RT/RW</div>
                    <div>{{ $customer->rt_rw ?: '—' }}</div>
                </div>
                <div class="col-md-6">
                    <div class="text-muted small mb-1" data-en="Postal Code">Kode Pos</div>
                    <div>{{ $customer->postal_code ?: '—' }}</div>
                </div>
                <div class="col-12">
                    <div class="text-muted small mb-1" data-en="Street Address">Alamat Jalan</div>
                    <div>{{ $customer->address ?: '—' }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- PIC --}}
    <div class="lkp-form-card mb-4">
        <div class="lkp-form-header">
            <i class="fas fa-user-tie"></i>
            <span data-en="Person In Charge (PIC)">Penanggung Jawab (PIC)</span>
            <span class="ms-auto text-muted small">{{ $customer->pics->count() }} PIC</span>
        </div>
        <div class="lkp-form-body">
            @if($customer->pics->isEmpty())
                <div class="text-center py-3" style="color:var(--text-faint)">
                    <i class="fas fa-user-slash mb-2 d-block fs-4"></i>
                    <span data-en="No PIC registered">Belum ada PIC terdaftar</span>
                </div>
            @else
                @foreach($customer->pics as $idx => $pic)
                    <div class="{{ $idx > 0 ? 'mt-3 pt-3 border-top' : '' }}">
                        <div class="d-flex align-items-center mb-2">
                            <strong class="text-muted me-2">PIC #{{ $idx + 1 }}</strong>
                            @if($pic->is_primary)
                                <span class="badge bg-primary" data-en="Primary">Utama</span>
                            @endif
                        </div>
                        <div class="row g-3">
                            <div class="col-md-12">
                                <div class="text-muted small mb-1" data-en="Name">Nama</div>
                                <div>{{ $pic->name ?: '—' }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="text-muted small mb-1" data-en="Position">Jabatan</div>
                                <div>{{ $pic->position ?: '—' }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="text-muted small mb-1" data-en="Department">Departemen</div>
                                <div>{{ $pic->department ?: '—' }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="text-muted small mb-1" data-en="Phone">Telepon</div>
                                <div>{{ $pic->phone ?: '—' }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="text-muted small mb-1" data-en="Email">Email</div>
                                <div>{{ $pic->email ?: '—' }}</div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>

@endsection
