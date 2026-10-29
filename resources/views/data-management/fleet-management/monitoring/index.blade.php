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
</style>
@endpush

@section('content')
<div class="container-fluid py-3">

    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <h4 class="mb-0 fw-bold">Monitoring GPS Armada</h4>
            <small class="text-muted">
                Status: <span id="gps-connection-status" class="fw-semibold text-warning">Menghubungkan...</span>
                &nbsp;·&nbsp; {{ $fleets->count() }} kendaraan terdaftar GPS
            </small>
        </div>
        <a href="{{ route('data-management.fleet-data.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Data Armada
        </a>
    </div>

    <div class="row g-3">

        {{-- Sidebar daftar kendaraan --}}
        <div class="col-12 col-md-3 col-xl-2">
            <div class="card shadow-sm h-100">
                <div class="card-header py-2 bg-white border-bottom">
                    <span class="fw-semibold small">Armada ({{ $fleets->count() }})</span>
                </div>
                <ul class="list-group list-group-flush" id="fleet-list">
                    {{-- Diisi oleh fleet-monitoring.js --}}
                </ul>
            </div>
        </div>

        {{-- Peta --}}
        <div class="col-12 col-md-9 col-xl-10">
            <div class="card shadow-sm">
                <div class="card-body p-2">
                    <div id="gps-map"
                         data-token-url="{{ route('data-management.gps-monitoring.api.ws-token') }}"
                         data-refresh-url="{{ route('data-management.gps-monitoring.api.ws-token.refresh') }}">
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
