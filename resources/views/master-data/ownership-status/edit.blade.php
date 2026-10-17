@extends('layouts.admin')

@section('title', 'Edit Ownership Status — ' . $ownershipStatus->name)

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
@endpush

@section('content')

<div class="veh-page-header mb-4">
    <div>
        <div class="veh-title">
            <span data-en="Edit Ownership Status">Edit Status Kepemilikan</span>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" data-en="Home">Beranda</a></li>
                <li class="breadcrumb-item">
                    <a href="{{ route('master-data.ownership-statuses.index') }}" data-en="Ownership Statuses">Status Kepemilikan</a>
                </li>
                <li class="breadcrumb-item active">{{ $ownershipStatus->name }}</li>
            </ol>
        </nav>
    </div>
</div>

<form method="POST"
      action="{{ route('master-data.ownership-statuses.update', $ownershipStatus) }}"
      novalidate>
    @csrf @method('PUT')

    <div class="lkp-form-card">
        <div class="lkp-form-header">
            <i class="fas fa-building"></i>
            <span data-en="Ownership Status Information">Informasi Status Kepemilikan</span>
        </div>
        <div class="lkp-form-body">
            <div class="row g-3">

                <div class="col-12 col-md-12">
                    <label class="form-label" data-en="Name">
                        Name <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="name"
                           class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name', $ownershipStatus->name) }}"
                           required autofocus>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label class="form-label" data-en="Description">Description</label>
                    <input type="text" name="description"
                           class="form-control @error('description') is-invalid @enderror"
                           value="{{ old('description', $ownershipStatus->description) }}"
                           placeholder="e.g. Vehicle owned directly by the company">
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 d-flex align-items-end">
                    <div class="lkp-toggle-wrap w-100">
                        <input class="form-check-input" type="checkbox"
                               id="is_active" name="is_active" value="1"
                               {{ old('is_active', $ownershipStatus->is_active) ? 'checked' : '' }}>
                        <label class="lkp-toggle-label" for="is_active">
                            <span data-en="Active">Aktif</span>
                            <small data-en="Can be selected in vehicle data">Dapat dipilih di data kendaraan</small>
                        </label>
                    </div>
                </div>

            </div>
        </div>
        <div class="lkp-form-actions">
            <a href="{{ route('master-data.ownership-statuses.index') }}"
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
