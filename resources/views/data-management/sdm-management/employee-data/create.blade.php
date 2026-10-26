@extends('layouts.admin')

@section('title', 'Tambah Karyawan')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
@endpush

@section('content')

<div class="emp-page-header mb-3">
    <div>
        <div class="emp-title">
            <span data-en="Add Employee">Tambah Karyawan</span>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" data-en="Home">Beranda</a></li>
                <li class="breadcrumb-item">
                    <a href="{{ route('data-management.sdm-management.employee-data.index') }}"
                       data-en="Employees">Karyawan</a>
                </li>
                <li class="breadcrumb-item active" data-en="Add">Tambah</li>
            </ol>
        </nav>
    </div>
</div>

<form method="POST"
      action="{{ route('data-management.sdm-management.employee-data.store') }}"
      id="employeeForm"
      novalidate>
    @csrf
    @include('data-management.sdm-management.employee-data._partials._form', ['employee' => null])
</form>

@endsection

@push('scripts')
    @vite('resources/js/pages/employee.js')
@endpush