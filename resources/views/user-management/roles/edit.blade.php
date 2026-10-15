@extends('layouts.admin')
@section('title', 'Edit Role — ' . $role->name)
@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
@endpush
@section('content')

<div class="um-page-header mb-3">
    <div>
        <div class="um-title"><i class="fas fa-edit"></i> Edit Role</div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
                <li class="breadcrumb-item"><a href="{{ route('user-management.roles.index') }}">Role</a></li>
                <li class="breadcrumb-item">
                    <a href="{{ route('user-management.roles.show', $role) }}">{{ $role->name }}</a>
                </li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('user-management.roles.index', $role) }}"
       class="btn btn-sm btn-outline-secondary d-flex align-items-center gap-1">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<form method="POST" action="{{ route('user-management.roles.update', $role) }}" id="roleForm" novalidate>
    @csrf @method('PUT')
    @include('user-management.roles._partials._form', ['role' => $role])
</form>

@include('user-management._partials._toast')
@endsection