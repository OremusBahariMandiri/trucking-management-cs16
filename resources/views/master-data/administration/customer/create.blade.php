@extends('layouts.admin')
@section('title', 'Tambah Pelanggan')
@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
@endpush
@section('content')

    <div class="veh-page-header mb-3">
        <div>
            <div class="veh-title">
                <i class="fas fa-user-plus"></i>
                <span data-en="Add Customer">Tambah Pelanggan</span>
            </div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" data-en="Home">Beranda</a></li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('master-data.administration.customers.index') }}" data-en="Customers">Pelanggan</a>
                    </li>
                    <li class="breadcrumb-item active" data-en="Add">Tambah</li>
                </ol>
            </nav>
        </div>
    </div>

    <form method="POST" action="{{ route('master-data.administration.customers.store') }}"
        enctype="multipart/form-data" novalidate>
        @csrf
        @php $customer = null; @endphp
        @include('master-data.administration.customer._partials._form')
    </form>

@endsection
