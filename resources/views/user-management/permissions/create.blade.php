@extends('layouts.admin')
@section('title', 'Tambah Permission')
@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
@endpush
@section('content')

<div class="um-page-header mb-3">
    <div>
        <div class="um-title">Tambah Permission</div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
                <li class="breadcrumb-item"><a href="{{ route('user-management.permissions.index') }}">Permission</a></li>
                <li class="breadcrumb-item active">Tambah</li>
            </ol>
        </nav>
    </div>
</div>

<form method="POST" action="{{ route('user-management.permissions.store') }}" id="permForm" novalidate>
    @csrf
    @include('user-management.permissions._partials._form', ['permission' => null, 'groups' => $groups])
</form>

@include('user-management._partials._toast')
@endsection