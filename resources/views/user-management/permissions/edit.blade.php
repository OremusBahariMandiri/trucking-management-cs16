@extends('layouts.admin')
@section('title', 'Edit Permission')
@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
@endpush
@section('content')

<div class="um-page-header mb-3">
    <div>
        <div class="um-title"><i class="fas fa-edit"></i> Edit Permission</div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
                <li class="breadcrumb-item"><a href="{{ route('user-management.permissions.index') }}">Permission</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('user-management.permissions.index') }}"
       class="btn btn-sm btn-outline-secondary d-flex align-items-center gap-1">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<form method="POST" action="{{ route('user-management.permissions.update', $permission) }}" id="permForm" novalidate>
    @csrf @method('PUT')
    @include('user-management.permissions._partials._form', ['permission' => $permission, 'groups' => $groups])
</form>

@include('user-management._partials._toast')
@endsection