@extends('layouts.admin')

@section('title', 'Edit Jenis — ' . $vehicleType->name)

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
@endpush

@section('content')

    <div class="veh-page-header mb-4">
        <div>
            <div class="veh-title">
                <i class="fas fa-pen"></i>
                <span data-en="Edit Vehicle Type">Edit Jenis Kendaraan</span>
            </div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" data-en="Home">Beranda</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('master-data.vehicle-types.index') }}"
                            data-en="Vehicle Types">Jenis Kendaraan</a></li>
                    <li class="breadcrumb-item active">{{ $vehicleType->name }}</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('master-data.vehicle-types.index') }}"
            class="btn btn-sm btn-outline-secondary d-flex align-items-center gap-1">
            <i class="fas fa-arrow-left"></i>
            <span data-en="Back">Kembali</span>
        </a>
    </div>

    <form method="POST" action="{{ route('master-data.vehicle-types.update', $vehicleType) }}" novalidate>
        @csrf @method('PUT')

        <div class="lkp-form-card">
            <div class="lkp-form-header">
                <i class="fas fa-tags"></i>
                <span data-en="Type Information">Informasi Jenis</span>
            </div>
            <div class="lkp-form-body">
                <div class="row g-3">

                    {{-- Code --}}
                    <div class="col-12 col-md-12">
                        <label class="form-label" data-en="Code">Kode</label>
                        <input type="text"
                               class="form-control @error('code') is-invalid @enderror"
                               id="code"
                               name="code"
                               value="{{ old('code', $vehicleType->code) }}"
                               maxlength="10"
                               placeholder="Maks 10 karakter">
                        @error('code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Name --}}
                    <div class="col-12 col-md-12">
                        <label class="form-label" data-en="Type Name">
                            Nama Jenis <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="name" id="name"
                            class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $vehicleType->name) }}" required autofocus>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label" data-en="Description">Deskripsi</label>
                        <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror"
                            rows="3">{{ old('description', $vehicleType->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 col-md-12 d-flex align-items-end">
                        <div class="lkp-toggle-wrap w-100">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
                                {{ old('is_active', $vehicleType->is_active) ? 'checked' : '' }}>
                            <label class="lkp-toggle-label" for="is_active">
                                <span data-en="Active">Aktif</span>
                                <small data-en="Type can be used in vehicle data">Jenis dapat digunakan di data
                                    kendaraan</small>
                            </label>
                        </div>
                    </div>

                </div>
            </div>
            <div class="lkp-form-actions">
                <a href="{{ route('master-data.vehicle-types.index') }}" class="btn btn-outline-secondary btn-sm">
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
