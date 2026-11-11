@extends('layouts.admin')
@section('title', 'Tambah Jenis Pelanggan')
@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
@endpush
@section('content')

    <div class="veh-page-header mb-4">
        <div>
            <div class="veh-title">
                <span data-en="Add Customer Type">Tambah Jenis Pelanggan</span>
            </div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" data-en="Home">Beranda</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('master-data.administration.customer-types.index') }}" data-en="Customer Types">Jenis Pelanggan</a></li>
                    <li class="breadcrumb-item active" data-en="Add">Tambah</li>
                </ol>
            </nav>
        </div>
    </div>

    <form method="POST" action="{{ route('master-data.administration.customer-types.store') }}" novalidate>
        @csrf
        <div class="lkp-form-card">
            <div class="lkp-form-header">
                <i class="fas fa-tags"></i>
                <span data-en="Customer Type Information">Informasi Jenis Pelanggan</span>
            </div>
            <div class="lkp-form-body">
                <div class="row g-3">
                    <div class="col-12 col-md-12">
                        <label class="form-label" data-en="Code">Kode</label>
                        <input type="text" name="code" class="form-control @error('code') is-invalid @enderror"
                            value="{{ old('code') }}" placeholder="maks. 20 karakter" maxlength="20">
                        @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12 col-md-12">
                        <label class="form-label" data-en="Name">Nama <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name') }}" autofocus required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label" data-en="Description">Deskripsi</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror"
                            rows="3">{{ old('description') }}</textarea>
                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <div class="lkp-toggle-wrap">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
                                {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="lkp-toggle-label" for="is_active">
                                <span data-en="Active">Aktif</span>
                                <small data-en="Type can be used in customer data">Jenis dapat digunakan di data pelanggan</small>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="lkp-form-actions">
                <a href="{{ route('master-data.administration.customer-types.index') }}"
                    class="btn btn-secondary btn-sm">
                    <i class="fas fa-times me-1"></i><span data-en="Cancel">Batal</span>
                </a>
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="fas fa-save me-1"></i><span data-en="Save">Simpan</span>
                </button>
            </div>
        </div>
    </form>

@endsection
