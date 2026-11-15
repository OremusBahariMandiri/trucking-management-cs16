@extends('layouts.admin')

@section('title', 'Monitoring GPS Armada')

@push('styles')
{{-- Leaflet CSS --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" referrerpolicy="no-referrer" />
<style>
    #gps-map {
        height: calc(100vh - 180px);
        min-height: 500px;
        border-radius: 8px;
        z-index: 0;
    }
    #fleet-list {
        max-height: calc(100vh - 220px);
        overflow-y: auto;
    }
    .fleet-status { font-size: 11px; min-width: 36px; text-align: center; }

    /* ── Dark mode: card & sidebar ──────────────────────────────────── */
    .gps-card {
        background-color: var(--surface, #fff);
        border-color: var(--border, #dee2e6);
        color: var(--text, #212529);
    }
    .gps-card-header {
        background-color: var(--surface-2, #f8f9fa) !important;
        border-bottom-color: var(--border, #dee2e6) !important;
        color: var(--text, #212529);
    }
    #fleet-list .list-group-item {
        background-color: var(--surface, #fff);
        border-color: var(--border, #dee2e6);
        color: var(--text, #212529);
    }
    #fleet-list .list-group-item:hover {
        background-color: var(--surface-2, #f8f9fa);
    }
    #fleet-list .text-muted {
        color: var(--text-muted, #6c757d) !important;
    }
    .gps-page-title  { color: var(--text, #212529); }
    .gps-page-sub    { color: var(--text-muted, #6c757d); }
</style>
@endpush

@section('content')
<div class="container-fluid py-3">

    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <h4 class="mb-0 fw-bold gps-page-title" data-en="Fleet GPS Monitoring">Monitoring GPS Armada</h4>
            <small class="gps-page-sub">
                <span data-en="Status:">Status:</span>
                <span id="gps-connection-status" class="fw-semibold text-warning">Menghubungkan...</span>
                &nbsp;·&nbsp; {{ $fleets->count() }}
                <span data-en="vehicles registered with GPS">kendaraan terdaftar GPS</span>
            </small>
        </div>
        <a href="{{ route('data-management.fleet-data.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left"></i>
            <span data-en="Fleet Data">Data Armada</span>
        </a>
    </div>

    <div class="row g-3">

        {{-- Sidebar daftar kendaraan --}}
        <div class="col-12 col-md-3 col-xl-2">
            <div class="card shadow-sm h-100 gps-card">
                <div class="card-header py-2 gps-card-header">
                    <span class="fw-semibold small">
                        <span data-en="Fleet">Armada</span> ({{ $fleets->count() }})
                    </span>
                </div>
                <ul class="list-group list-group-flush" id="fleet-list">
                    {{-- Diisi oleh fleet-monitoring.js --}}
                </ul>
            </div>
        </div>

        {{-- Peta --}}
        <div class="col-12 col-md-9 col-xl-10">
            <div class="card shadow-sm gps-card">
                <div class="card-body p-2">
                    <div id="gps-map"
                         data-token-url="{{ route('data-management.gps-monitoring.api.ws-token') }}"
                         data-refresh-url="{{ route('data-management.gps-monitoring.api.ws-token.refresh') }}"
                         data-engine-url="{{ route('data-management.gps-monitoring.api.engine-event') }}"
                         data-history-url="{{ url('data-management/gps-monitoring/api/engine-history/__ID__') }}">
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- Data armada untuk JS (posisi terakhir dari DB) --}}
<script id="gps-fleet-data" type="application/json">
    {!! json_encode($fleets->values(), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>
@endsection

@push('scripts')
{{-- Leaflet JS --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js" referrerpolicy="no-referrer"></script>
{{-- Monitoring JS --}}
@vite('resources/js/pages/fleet-monitoring.js')
@endpush
