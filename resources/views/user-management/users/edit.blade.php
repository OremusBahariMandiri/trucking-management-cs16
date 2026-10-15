@extends('layouts.admin')
@section('title', 'Edit User — ' . $user->full_name)
@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
@endpush

@section('content')

<div class="um-page-header mb-3">
    <div>
        <div class="um-title"><i class="fas fa-user-edit"></i> Edit User</div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
                <li class="breadcrumb-item"><a href="{{ route('user-management.users.index') }}">User</a></li>
                <li class="breadcrumb-item">
                    <a href="{{ route('user-management.users.show', $user) }}">{{ $user->full_name }}</a>
                </li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('user-management.users.index', $user) }}"
       class="btn btn-sm btn-outline-secondary d-flex align-items-center gap-1">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<form method="POST" action="{{ route('user-management.users.update', $user) }}"
      id="userForm" novalidate>
    @csrf @method('PUT')
    @include('user-management.users._partials._form', ['user' => $user, 'roles' => $roles])
</form>

@include('user-management._partials._toast')
@endsection