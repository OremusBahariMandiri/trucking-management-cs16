@extends('layouts.admin')

@section('title', 'Edit Supir — ' . $driver->full_name)

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
@endpush

@section('content')

<div class="drv-page-header mb-3">
    <div>
        <div class="drv-title">
            <span data-en="Edit Driver">Edit Supir</span>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" data-en="Home">Beranda</a></li>
                <li class="breadcrumb-item">
                    <a href="{{ route('data-management.sdm-management.driver-data.index') }}" data-en="Drivers">Supir</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('data-management.sdm-management.driver-data.show', $driver) }}">
                        {{ $driver->full_name }}
                    </a>
                </li>
                <li class="breadcrumb-item active" data-en="Edit">Edit</li>
            </ol>
        </nav>
    </div>
</div>

<form method="POST"
      action="{{ route('data-management.sdm-management.driver-data.update', $driver) }}"
      enctype="multipart/form-data"
      id="driverForm"
      novalidate>
    @csrf
    @method('PUT')
    @include('data-management.sdm-management.driver-data._partials._form', ['driver' => $driver])
</form>

@endsection

@push('scripts')
    @vite('resources/js/pages/driver.js')
@endpush