@extends('layouts.admin')

@section('title', 'Tambah Merek Kendaraan')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
@endpush

@section('content')

<div class="veh-page-header mb-4">
    <div>
        <div class="veh-title">
            <span data-en="Add Vehicle Brand">Tambah Merek Kendaraan</span>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" data-en="Home">Beranda</a></li>
                <li class="breadcrumb-item"><a href="{{ route('master-data.vehicle-brands.index') }}" data-en="Vehicle Brands">Merek Kendaraan</a></li>
                <li class="breadcrumb-item active" data-en="Add">Tambah</li>
            </ol>
        </nav>
    </div>
</div>

<form method="POST" action="{{ route('master-data.vehicle-brands.store') }}" novalidate>
    @csrf

    <div class="lkp-form-card">
        <div class="lkp-form-header">
            <i class="fas fa-trademark"></i>
            <span data-en="Brand Information">Informasi Merek</span>
        </div>
        <div class="lkp-form-body">
            <div class="row g-3">

                <div class="col-12 col-md-12">
                    <label class="form-label" data-en="Code">
                        Kode
                    </label>
                    <input type="text" name="code" id="code"
                           class="form-control @error('code') is-invalid @enderror"
                           value="{{ old('code') }}"
                           placeholder="maks. 10 karakter"
                           maxlength="10">
                    @error('code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 col-md-12">
                    <label class="form-label" data-en="Brand Name">
                        Nama Merek <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="name" id="name"
                           class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name') }}"
                           placeholder="cth: Toyota, Hino, Mercedes-Benz…"
                           data-en-placeholder="e.g. Toyota, Hino, Mercedes-Benz…"
                           autofocus required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 col-md-12">
                    <label class="form-label" data-en="Country of Origin">Negara Asal</label>
                    <input type="text" name="country_of_origin" id="country_of_origin"
                           class="form-control @error('country_of_origin') is-invalid @enderror"
                           value="{{ old('country_of_origin') }}"
                           placeholder="cth: Jepang, Jerman, Korea…"
                           data-en-placeholder="e.g. Japan, Germany, Korea…">
                    @error('country_of_origin')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                

                {{-- Description --}}
                <div class="col-12">
                    <label class="form-label" data-en="Description">Deskripsi</label>
                    <textarea name="description" id="description"
                              class="form-control @error('description') is-invalid @enderror"
                              rows="3"
                              placeholder="Deskripsi singkat (opsional)…">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 col-md-12 d-flex align-items-end">
                    <div class="lkp-toggle-wrap w-100">
                        <input class="form-check-input" type="checkbox"
                               id="is_active" name="is_active" value="1"
                               {{ old('is_active', true) ? 'checked' : '' }}>
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
               class="btn btn-secondary btn-sm">
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