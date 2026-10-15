@extends('layouts.admin')

@section('title', 'Tambah Jenis Kendaraan')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
@endpush

@section('content')

    <div class="veh-page-header mb-4"
        style="background-image: url('{{ asset('images/bg-card3.png') }}'); background-size: cover; background-position: center; background-repeat: no-repeat;">
        <div>
            <div class="veh-title">
                <i class="fas fa-plus-circle"></i>
                <span data-en="Add Vehicle Type">Tambah Jenis Kendaraan</span>
            </div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" data-en="Home">Beranda</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('master-data.vehicle-types.index') }}"
                            data-en="Vehicle Types">Jenis Kendaraan</a></li>
                    <li class="breadcrumb-item active" data-en="Add">Tambah</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('master-data.vehicle-types.index') }}"
            class="btn btn-sm btn-primary d-flex align-items-center gap-1">
            <i class="fas fa-arrow-left"></i>
            <span data-en="Back">Kembali</span>
        </a>
    </div>

    <form method="POST" action="{{ route('master-data.vehicle-types.store') }}" novalidate>
        @csrf

        <div class="lkp-form-card">
            <div class="lkp-form-header">
                <i class="fas fa-tags"></i>
                <span data-en="Type Information">Informasi Jenis</span>
            </div>
            <div class="lkp-form-body">
                <div class="row g-3">

                    {{-- Name --}}
                    <div class="col-12 col-md-12">
                        <label class="form-label" data-en="Type Name">
                            Nama Jenis <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="name" id="name"
                            class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}"
                            placeholder="cth: Truk, Bus, Pick Up…" data-en-placeholder="e.g. Truck, Bus, Pick Up…" autofocus
                            required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Description --}}
                    <div class="col-12">
                        <label class="form-label" data-en="Description">Deskripsi</label>
                        <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror"
                            rows="3" placeholder="Deskripsi singkat jenis kendaraan (opsional)…"
                            data-en-placeholder="Brief description of this vehicle type (optional)…">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Status --}}
                    <div class="col-12 col-md-12 d-flex align-items-end">
                        <div class="lkp-toggle-wrap w-100">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
                                {{ old('is_active', true) ? 'checked' : '' }}>
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
                    <span data-en="Save">Simpan</span>
                </button>
            </div>
        </div>

    </form>

@endsection
