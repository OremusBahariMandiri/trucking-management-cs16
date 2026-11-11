@extends('layouts.admin')

@section('title', 'Edit Model — ' . $vehicleModel->name)

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
@endpush

@section('content')

<div class="veh-page-header mb-4">
    <div>
        <div class="veh-title">
            <span data-en="Edit Vehicle Model">Edit Model Kendaraan</span>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" data-en="Home">Beranda</a></li>
                <li class="breadcrumb-item"><a href="{{ route('master-data.vehicle-models.index') }}" data-en="Vehicle Models">Model Kendaraan</a></li>
                <li class="breadcrumb-item active">{{ $vehicleModel->name }}</li>
            </ol>
        </nav>
    </div>
</div>

<form method="POST" action="{{ route('master-data.vehicle-models.update', $vehicleModel) }}" novalidate>
    @csrf @method('PUT')

    <div class="lkp-form-card">
        <div class="lkp-form-header">
            <i class="fas fa-car-side"></i>
            <span data-en="Model Information">Informasi Model</span>
        </div>
        <div class="lkp-form-body">
            <div class="row g-3">

                <div class="col-12 col-md-12">
                    <label class="form-label" data-en="Brand">
                        Merek <span class="text-danger">*</span>
                    </label>
                    <select name="vehicle_brand_id"
                            class="form-select @error('vehicle_brand_id') is-invalid @enderror" required>
                        <option value="" data-en="— Select Brand —">— Pilih Merek —</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}"
                                    {{ old('vehicle_brand_id', $vehicleModel->vehicle_brand_id) == $brand->id ? 'selected' : '' }}>
                                {{ $brand->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('vehicle_brand_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 col-md-12">
                    <label class="form-label" data-en="Code">
                        Kode
                    </label>
                    <input type="text" name="code" id="code"
                           class="form-control @error('code') is-invalid @enderror"
                           value="{{ old('code', $vehicleModel->code) }}"
                           placeholder="maks. 10 karakter"
                           maxlength="10">
                    @error('code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 col-md-12">
                    <label class="form-label" data-en="Model Name">
                        Nama Model <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="name"
                           class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name', $vehicleModel->name) }}"
                           required autofocus>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 col-md-12">
                    <label class="form-label" data-en="Vehicle Type (optional)">Jenis Kendaraan <small class="text-muted">(opsional)</small></label>
                    <select name="vehicle_type_id"
                            class="form-select @error('vehicle_type_id') is-invalid @enderror">
                        <option value="" data-en="— Select Type —">— Pilih Jenis —</option>
                        @foreach($vehicleTypes ?? [] as $type)
                            <option value="{{ $type->id }}"
                                    {{ old('vehicle_type_id', $vehicleModel->vehicle_type_id) == $type->id ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('vehicle_type_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Description --}}
                <div class="col-12">
                    <label class="form-label" data-en="Description">Deskripsi</label>
                    <textarea name="description" id="description"
                              class="form-control @error('description') is-invalid @enderror"
                              rows="3"
                              placeholder="Deskripsi singkat (opsional)…">{{ old('description', $vehicleModel->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 col-md-12 d-flex align-items-end">
                    <div class="lkp-toggle-wrap w-100">
                        <input class="form-check-input" type="checkbox"
                               id="is_active" name="is_active" value="1"
                               {{ old('is_active', $vehicleModel->is_active) ? 'checked' : '' }}>
                        <label class="lkp-toggle-label" for="is_active">
                            <span data-en="Active">Aktif</span>
                            <small data-en="Model can be used in vehicle data">Model dapat digunakan di data kendaraan</small>
                        </label>
                    </div>
                </div>

            </div>
        </div>
        <div class="lkp-form-actions">
            <a href="{{ route('master-data.vehicle-models.index') }}"
               class="btn btn-secondary btn-sm">
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