@extends('layouts.admin')

@section('title', 'Edit Kendaraan — ' . ($fleet->vehicle_code ?? $fleet->police_number))

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
@endpush

@section('content')

<div class="veh-page-header mb-3">
    <div>
        <div class="veh-title">
            <span data-en="Edit Vehicle">Edit Kendaraan</span>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" data-en="Home">Beranda</a></li>
                <li class="breadcrumb-item"><a href="{{ route('data-management.fleet-data.index') }}" data-en="Fleet">Kendaraan</a></li>
                <li class="breadcrumb-item">
                    <a href="{{ route('data-management.fleet-data.show', $fleet) }}">
                        {{ $fleet->vehicle_code ?? $fleet->police_number }}
                    </a>
                </li>
                <li class="breadcrumb-item active" data-en="Edit">Edit</li>
            </ol>
        </nav>
    </div>
</div>

<form method="POST"
      action="{{ route('data-management.fleet-data.update', $fleet) }}"
      enctype="multipart/form-data"
      id="vehicleForm"
      novalidate>
    @csrf
    @method('PUT')
    @include('data-management.fleet-management.fleet-data._partials._form', ['fleet' => $fleet])
</form>

@endsection

@push('scripts')
    @vite('resources/js/pages/vehicle.js')
@endpush