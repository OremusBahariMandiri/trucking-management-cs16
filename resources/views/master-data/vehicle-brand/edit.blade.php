@extends('layouts.admin')

@section('title', 'Edit Merek — ' . $vehicleBrand->name)

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
@endpush

@section('content')

<div class="veh-page-header mb-4">
    <div>
        <div class="veh-title">
            <i class="fas fa-pen"></i>
            <span data-en="Edit Vehicle Brand">Edit Merek Kendaraan</span>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" data-en="Home">Beranda</a></li>
                <li class="breadcrumb-item"><a href="{{ route('master-data.vehicle-brands.index') }}" data-en="Vehicle Brands">Merek Kendaraan</a></li>
                <li class="breadcrumb-item active">{{ $vehicleBrand->name }}</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('master-data.vehicle-brands.index') }}"
       class="btn btn-sm btn-outline-secondary d-flex align-items-center gap-1">
        <i class="fas fa-arrow-left"></i>
        <span data-en="Back">Kembali</span>
    </a>
</div>

<form method="POST" action="{{ route('master-data.vehicle-brands.update', $vehicleBrand) }}" novalidate>
    @csrf @method('PUT')

    <div class="lkp-form-card">
        <div class="lkp-form-header">
            <i class="fas fa-trademark"></i>
            <span data-en="Brand Information">Informasi Merek</span>
        </div>
        <div class="lkp-form-body">
            <div class="row g-3">

                <div class="col-12 col-md-12">
                    <label class="form-label" data-en="Brand Name">
                        Nama Merek <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="name" id="name"
                           class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name', $vehicleBrand->name) }}"
                           required autofocus>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 col-md-12">
                    <label class="form-label" data-en="Country of Origin">Negara Asal</label>
                    <input type="text" name="country_of_origin"
                           class="form-control @error('country_of_origin') is-invalid @enderror"
                           value="{{ old('country_of_origin', $vehicleBrand->country_of_origin) }}">
                    @error('country_of_origin')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 col-md-12 d-flex align-items-end">
                    <div class="lkp-toggle-wrap w-100">
                        <input class="form-check-input" type="checkbox"
                               id="is_active" name="is_active" value="1"
                               {{ old('is_active', $vehicleBrand->is_active) ? 'checked' : '' }}>
                        <label class="lkp-toggle-label" for="is_active">
                            <span data-en="Active">Aktif</span>
                            <small data-en="Brand can be used in vehicle data">Merek dapat digunakan di data kendaraan</small>
                        </label>
                    </div>
                </div>

            </div>
        </div>
        <div class="lkp-form-actions">
            <a href="{{ route('master-data.vehicle-brands.index') }}"
               class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-times me-1"></i>
                <span data-en="Cancel">Batal</span>
            </a>
            <button type="submit" class="btn btn-primary btn-sm">
                <i class="fas fa-save me-1"></i>
                <span data-en="Update">Perbarui</span>
            </button>
        </div>
    </div>

</form>

@endsection