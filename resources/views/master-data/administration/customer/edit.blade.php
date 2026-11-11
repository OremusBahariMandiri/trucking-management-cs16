@extends('layouts.admin')
@section('title', 'Edit Pelanggan')
@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
@endpush
@section('content')

    <div class="veh-page-header mb-3">
        <div>
            <div class="veh-title">
                <i class="fas fa-user-edit"></i>
                <span data-en="Edit Customer">Edit Pelanggan</span>
            </div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" data-en="Home">Beranda</a></li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('master-data.administration.customers.index') }}" data-en="Customers">Pelanggan</a>
                    </li>
                    <li class="breadcrumb-item active" data-en="Edit">Edit</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('master-data.administration.customers.show', $customer) }}"
                class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-eye me-1"></i>
                <span data-en="View Detail">Lihat Detail</span>
            </a>
        </div>
    </div>

    <form method="POST" action="{{ route('master-data.administration.customers.update', $customer) }}"
        enctype="multipart/form-data" novalidate>
        @csrf @method('PUT')
        @include('master-data.administration.customer._partials._form')
    </form>

@endsection
