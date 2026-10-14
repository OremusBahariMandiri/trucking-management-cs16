@extends('layouts.admin')

@section('title', 'Tambah Kendaraan')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
@endpush

@section('content')

    <div class="veh-page-header mb-3">
        <div>
            <div class="veh-title">
                <i class="fas fa-plus-circle"></i>
                <span data-en="Add Vehicle">Tambah Kendaraan</span>
            </div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" data-en="Home">Beranda</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('data-management.fleet-data.index') }}"
                            data-en="Fleet">Kendaraan</a></li>
                    <li class="breadcrumb-item active" data-en="Add">Tambah</li>
                </ol>
            </nav>
        </div>
    </div>

    <form method="POST" action="{{ route('data-management.fleet-data.store') }}" enctype="multipart/form-data" id="vehicleForm"
        novalidate>
        @csrf
        @include('data-management.fleet-management.fleet-data._partials._form', ['vehicle' => null])
    </form>

@endsection

@push('scripts')
    @vite('resources/js/pages/vehicle.js')
@endpush
