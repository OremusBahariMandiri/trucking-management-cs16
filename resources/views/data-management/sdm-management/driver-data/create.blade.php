@extends('layouts.admin')

@section('title', 'Tambah Supir')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
@endpush

@section('content')

<div class="drv-page-header mb-3">
    <div>
        <div class="drv-title">
            <i class="fas fa-user-plus"></i>
            <span data-en="Add Driver">Tambah Supir</span>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" data-en="Home">Beranda</a></li>
                <li class="breadcrumb-item">
                    <a href="{{ route('data-management.sdm-management.driver-data.index') }}" data-en="Drivers">Supir</a>
                </li>
                <li class="breadcrumb-item active" data-en="Add">Tambah</li>
            </ol>
        </nav>
    </div>
</div>

<form method="POST"
      action="{{ route('data-management.sdm-management.driver-data.store') }}"
      enctype="multipart/form-data"
      id="driverForm"
      novalidate>
    @csrf
    @include('data-management.sdm-management.driver-data._partials._form', ['driver' => null])
</form>

@endsection

@push('scripts')
    @vite('resources/js/pages/driver.js')
@endpush