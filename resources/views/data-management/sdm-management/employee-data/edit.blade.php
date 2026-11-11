@extends('layouts.admin')

@section('title', 'Edit Karyawan — ' . $employee->full_name)

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
@endpush

@section('content')

<div class="emp-page-header mb-3">
    <div>
        <div class="emp-title">
            <i class="fas fa-user-edit"></i>
            <span data-en="Edit Employee">Edit Karyawan</span>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" data-en="Home">Beranda</a></li>
                <li class="breadcrumb-item">
                    <a href="{{ route('data-management.sdm-management.employee-data.index') }}"
                       data-en="Employees">Karyawan</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('data-management.sdm-management.employee-data.show', $employee) }}">
                        {{ $employee->full_name }}
                    </a>
                </li>
                <li class="breadcrumb-item active" data-en="Edit">Edit</li>
            </ol>
        </nav>
    </div>
</div>

<form method="POST"
      action="{{ route('data-management.sdm-management.employee-data.update', $employee) }}"
      id="employeeForm"
      novalidate>
    @csrf
    @method('PUT')
    @include('data-management.sdm-management.employee-data._partials._form', ['employee' => $employee])
</form>

@endsection

@push('scripts')
    @vite('resources/js/pages/employee.js')
@endpush