@extends('layouts.admin')

@section('title', $employee->full_name . ' — Detail Karyawan')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
@endpush

@section('content')

{{-- ── Page Header ──────────────────────────────────────────── --}}
<div class="emp-page-header mb-3">
    <div>
        <div class="emp-title">
            <i class="fas fa-user"></i>
            <span data-en="Employee Detail">Detail Karyawan</span>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" data-en="Home">Beranda</a></li>
                <li class="breadcrumb-item">
                    <a href="{{ route('data-management.sdm-management.employee-data.index') }}"
                       data-en="Employees">Karyawan</a>
                </li>
                <li class="breadcrumb-item active">{{ $employee->full_name }}</li>
            </ol>
        </nav>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('data-management.sdm-management.employee-data.edit', $employee) }}"
           class="btn btn-sm btn-primary d-flex align-items-center gap-1">
            <i class="fas fa-pen"></i>
            <span data-en="Edit">Edit</span>
        </a>
        <form method="POST"
              action="{{ route('data-management.sdm-management.employee-data.destroy', $employee) }}"
              onsubmit="return confirm('Hapus karyawan ini?')">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-sm btn-outline-danger">
                <i class="fas fa-trash"></i>
                <span data-en="Delete">Hapus</span>
            </button>
        </form>
    </div>
</div>

{{-- ── Detail Card ──────────────────────────────────────────── --}}
<div class="emp-detail-card">

    {{-- Header dalam card --}}
    <div class="emp-detail-hero">
        <div class="emp-show-avatar">
            <i class="fas fa-user fa-2x"></i>
        </div>
        <div>
            <h4 class="emp-show-name">{{ $employee->full_name }}</h4>
            <span class="emp-show-nrk"><i class="fas fa-id-badge me-1"></i>{{ $employee->nrk }}</span>
        </div>
    </div>

    <div class="emp-detail-divider"></div>

    {{-- Fields grid --}}
    <div class="row g-4 px-3 pb-3">
        @php
            $fields = [
                ['label' => 'NRK',        'label_en' => 'NRK',        'value' => $employee->nrk,        'icon' => 'fas fa-id-badge'],
                ['label' => 'Nama',       'label_en' => 'Name',       'value' => $employee->full_name,  'icon' => 'fas fa-user'],
                ['label' => 'Departemen', 'label_en' => 'Department', 'value' => $employee->department, 'icon' => 'fas fa-building'],
                ['label' => 'Jabatan',    'label_en' => 'Position',   'value' => $employee->position,   'icon' => 'fas fa-briefcase'],
                ['label' => 'Wilker',     'label_en' => 'Work Area',  'value' => $employee->wilker,     'icon' => 'fas fa-map-marker-alt'],
            ];
        @endphp
        @foreach($fields as $f)
        <div class="col-sm-6 col-md-4">
            <div class="emp-info-item">
                <div class="emp-info-icon"><i class="{{ $f['icon'] }}"></i></div>
                <div>
                    <div class="info-label" data-en="{{ $f['label_en'] }}">{{ $f['label'] }}</div>
                    <div class="info-value">{{ $f['value'] ?? '—' }}</div>
                </div>
            </div>
        </div>
        @endforeach

        {{-- Catatan --}}
        @if($employee->notes)
        <div class="col-12">
            <div class="emp-info-item">
                <div class="emp-info-icon"><i class="fas fa-sticky-note"></i></div>
                <div style="flex:1">
                    <div class="info-label" data-en="Notes">Catatan</div>
                    <div class="info-value" style="white-space:pre-wrap">{{ $employee->notes }}</div>
                </div>
            </div>
        </div>
        @endif
    </div>

    {{-- Footer audit --}}
    <div class="emp-detail-footer">
        <span>
            <i class="fas fa-clock me-1"></i>
            <span data-en="Created">Dibuat</span>
            {{ $employee->created_at?->format('d M Y H:i') ?? '-' }}
        </span>
        <span>
            <i class="fas fa-edit me-1"></i>
            <span data-en="Updated">Diperbarui</span>
            {{ $employee->updated_at?->format('d M Y H:i') ?? '-' }}
        </span>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show position-fixed bottom-0 start-50 translate-middle-x mb-4"
     style="z-index:9999;min-width:320px" role="alert">
    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@endsection

@push('scripts')
    @vite('resources/js/pages/employee.js')
@endpush