@extends('layouts.admin')

@section('title', $vehicle->vehicle_code ?? $vehicle->police_number ?? 'Detail Kendaraan')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
@endpush

@section('content')

{{-- ── Page Header ────────────────────────────────────────── --}}
<div class="veh-page-header mb-3">
    <div>
        <div class="veh-title">
            <i class="fas fa-truck"></i>
            <span data-en="Vehicle Detail">Detail Kendaraan</span>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" data-en="Home">Beranda</a></li>
                <li class="breadcrumb-item"><a href="{{ route('master-data.kendaraan.index') }}" data-en="Fleet">Kendaraan</a></li>
                <li class="breadcrumb-item active">{{ $vehicle->vehicle_code ?? $vehicle->police_number }}</li>
            </ol>
        </nav>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('master-data.kendaraan.edit', $vehicle) }}"
           class="btn btn-sm btn-primary d-flex align-items-center gap-1">
            <i class="fas fa-pen"></i>
            <span data-en="Edit">Edit</span>
        </a>
        <form method="POST" action="{{ route('master-data.kendaraan.destroy', $vehicle) }}"
              onsubmit="return confirm('Hapus kendaraan ini?')">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-sm btn-outline-danger">
                <i class="fas fa-trash"></i>
                <span data-en="Delete">Hapus</span>
            </button>
        </form>
    </div>
</div>

{{-- ── Hero: foto + info utama ─────────────────────────────── --}}
<div class="row g-3 mb-3">
    {{-- Foto --}}
    <div class="col-md-4">
        <div class="veh-show-hero">
            @php $primaryPhoto = $vehicle->photos->firstWhere('is_primary', true) ?? $vehicle->photos->first(); @endphp
            @if($primaryPhoto)
                <img src="{{ Storage::url($primaryPhoto->file_path) }}"
                     alt="{{ $vehicle->vehicle_code }}"
                     class="hero-photo w-100">
            @else
                <div class="hero-photo d-flex align-items-center justify-content-center"
                     style="height:220px;background:var(--surface-2)">
                    <i class="fas fa-truck" style="font-size:4rem;color:var(--text-faint)"></i>
                </div>
            @endif
        </div>
    </div>

    {{-- Info utama --}}
    <div class="col-md-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex flex-column">
                <div class="d-flex align-items-start justify-content-between mb-3">
                    <div>
                        <h4 class="mb-0 fw-bold" style="font-size:1.3rem;color:var(--text)">
                            {{ $vehicle->vehicle_code ?? '-' }}
                        </h4>
                        <div style="font-size:.88rem;color:var(--text-muted)">
                            {{ $vehicle->brand?->name }} {{ $vehicle->model?->name ?? $vehicle->model_name }}
                            @if($vehicle->manufacture_year) · {{ $vehicle->manufacture_year }} @endif
                        </div>
                    </div>
                    <span class="badge-status status-{{ $vehicle->vehicle_status ?? 'inactive' }}">
                        {{ $vehicle->status_label }}
                    </span>
                </div>

                <div class="row g-3 flex-grow-1">
                    @php
                        $heroFields = [
                            ['label'=>'Nomor Polisi',   'label_en'=>'Police Number',   'value'=>$vehicle->police_number],
                            ['label'=>'Nomor Lambung',  'label_en'=>'Hull Number',      'value'=>$vehicle->hull_number],
                            ['label'=>'Jenis',          'label_en'=>'Type',             'value'=>$vehicle->type?->name],
                            ['label'=>'Warna',          'label_en'=>'Color',            'value'=>$vehicle->color],
                            ['label'=>'Kepemilikan',    'label_en'=>'Ownership',        'value'=>$vehicle->ownership_label],
                            ['label'=>'GPS',            'label_en'=>'GPS',              'value'=>match($vehicle->gps_status){
                                'active'=>'Aktif','offline'=>'Offline','not_installed'=>'Tidak Terpasang',default=>'-'
                            }],
                        ];
                    @endphp
                    @foreach($heroFields as $f)
                    <div class="col-6 col-md-4">
                        <div class="info-label" data-en="{{ $f['label_en'] }}">{{ $f['label'] }}</div>
                        <div class="info-value">{{ $f['value'] ?? '-' }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── Tab Navigation ──────────────────────────────────────── --}}
<div class="veh-tab-nav mb-0">
    <ul class="nav nav-tabs" id="vehicleTabs" role="tablist">
        @php
            $tabs = [
                ['id'=>'tab-spek',       'icon'=>'fas fa-cogs',          'label'=>'Spesifikasi',        'label_en'=>'Specifications'],
                ['id'=>'tab-kepemilikan','icon'=>'fas fa-building',       'label'=>'Kepemilikan',        'label_en'=>'Ownership'],
                ['id'=>'tab-ops',        'icon'=>'fas fa-tachometer-alt', 'label'=>'Operasional',        'label_en'=>'Operational'],
                ['id'=>'tab-dokumen',    'icon'=>'fas fa-file-alt',       'label'=>'Dokumen & Legalitas','label_en'=>'Documents & Legal'],
                ['id'=>'tab-foto',       'icon'=>'fas fa-images',         'label'=>'Foto',               'label_en'=>'Photos'],
                ['id'=>'tab-catatan',    'icon'=>'fas fa-book',           'label'=>'Catatan',            'label_en'=>'Notes'],
            ];
        @endphp
        @foreach($tabs as $i => $tab)
        <li class="nav-item" role="presentation">
            <button class="nav-link {{ $i === 0 ? 'active' : '' }}"
                    data-bs-toggle="tab"
                    data-bs-target="#{{ $tab['id'] }}"
                    type="button">
                <i class="{{ $tab['icon'] }}"></i>
                @if($tab['id'] === 'tab-foto')
                    <span class="d-none d-sm-inline">
                        <span data-en="{{ $tab['label_en'] }}">{{ $tab['label'] }}</span>
                        ({{ $vehicle->photos->count() }})
                    </span>
                @else
                    <span class="d-none d-sm-inline" data-en="{{ $tab['label_en'] }}">{{ $tab['label'] }}</span>
                @endif
            </button>
        </li>
        @endforeach
    </ul>
</div>

<div class="tab-content">

    {{-- Tab: Spesifikasi --}}
    <div class="tab-pane fade show active" id="tab-spek">
        <div class="veh-form-card">
            <div class="row g-3">
                @php
                    $spekFields = [
                        ['label'=>'Nomor Rangka',       'label_en'=>'Chassis Number',     'value'=>$vehicle->chassis_number],
                        ['label'=>'Nomor Mesin',        'label_en'=>'Engine Number',       'value'=>$vehicle->engine_number],
                        ['label'=>'Bahan Bakar',        'label_en'=>'Fuel Type',           'value'=>$vehicle->fuelType?->name],
                        ['label'=>'Kapasitas Mesin',    'label_en'=>'Engine Capacity',     'value'=>$vehicle->engine_capacity_cc ? number_format($vehicle->engine_capacity_cc).' cc' : null],
                        ['label'=>'Konfigurasi Sumbu',  'label_en'=>'Axle Config',         'value'=>$vehicle->axis_configuration],
                        ['label'=>'Jumlah Roda',        'label_en'=>'Wheel Count',         'value'=>$vehicle->wheel_count ? $vehicle->wheel_count.' roda' : null],
                        ['label'=>'Kapasitas Muatan',   'label_en'=>'Load Capacity',       'value'=>$vehicle->load_capacity_ton ? $vehicle->load_capacity_ton.' ton' : null],
                        ['label'=>'Berat Kotor (GVW)',  'label_en'=>'Gross Weight (GVW)',  'value'=>$vehicle->gross_vehicle_weight_ton ? $vehicle->gross_vehicle_weight_ton.' ton' : null],
                        ['label'=>'Berat Kosong',       'label_en'=>'Tare Weight',         'value'=>$vehicle->tare_weight_ton ? $vehicle->tare_weight_ton.' ton' : null],
                        ['label'=>'Jenis Body',         'label_en'=>'Body Type',           'value'=>$vehicle->bodyType?->name],
                        ['label'=>'Dimensi Body',       'label_en'=>'Body Dimensions',     'value'=>$vehicle->body_dimension],
                    ];
                @endphp
                @foreach($spekFields as $f)
                <div class="col-6 col-md-3">
                    <div class="info-label" data-en="{{ $f['label_en'] }}">{{ $f['label'] }}</div>
                    <div class="info-value">{{ $f['value'] ?? '-' }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Tab: Kepemilikan --}}
    <div class="tab-pane fade" id="tab-kepemilikan">
        <div class="veh-form-card">
            <div class="row g-3">
                @php
                    $kepFields = [
                        ['label'=>'Status Kepemilikan', 'label_en'=>'Ownership Status',   'value'=>$vehicle->ownership_label],
                        ['label'=>'Tahun Perolehan',    'label_en'=>'Acquisition Year',   'value'=>$vehicle->acquisition_year],
                        ['label'=>'Nilai Perolehan',    'label_en'=>'Acquisition Value',  'value'=>$vehicle->acquisition_value ? 'Rp '.number_format($vehicle->acquisition_value,0,',','.') : null],
                        ['label'=>'Perusahaan Leasing', 'label_en'=>'Leasing Company',    'value'=>$vehicle->leasing_company],
                        ['label'=>'Akhir Leasing',      'label_en'=>'Leasing End',        'value'=>$vehicle->leasing_end_date?->format('d M Y')],
                        ['label'=>'Pengemudi',          'label_en'=>'Driver',             'value'=>$vehicle->driver?->name],
                    ];
                @endphp
                @foreach($kepFields as $f)
                <div class="col-6 col-md-4">
                    <div class="info-label" data-en="{{ $f['label_en'] }}">{{ $f['label'] }}</div>
                    <div class="info-value">{{ $f['value'] ?? '-' }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Tab: Operasional --}}
    <div class="tab-pane fade" id="tab-ops">
        <div class="veh-form-card">
            <div class="row g-3">
                @php
                    $opsFields = [
                        ['label'=>'Odometer Terakhir',  'label_en'=>'Last Odometer',    'value'=>$vehicle->last_odometer_km ? number_format($vehicle->last_odometer_km).' km' : null],
                        ['label'=>'Servis Terakhir',    'label_en'=>'Last Service',     'value'=>$vehicle->last_service_date?->format('d M Y')],
                        ['label'=>'Interval Servis',    'label_en'=>'Service Interval', 'value'=>$vehicle->service_interval_km ? number_format($vehicle->service_interval_km).' km' : null],
                        ['label'=>'ID GPS',             'label_en'=>'GPS ID',           'value'=>$vehicle->gps_tracker_id],
                        ['label'=>'Provider GPS',       'label_en'=>'GPS Provider',     'value'=>$vehicle->gps_provider],
                        ['label'=>'Status GPS',         'label_en'=>'GPS Status',       'value'=>match($vehicle->gps_status){'active'=>'Aktif','offline'=>'Offline','not_installed'=>'Tidak Terpasang',default=>'-'}],
                    ];
                @endphp
                @foreach($opsFields as $f)
                <div class="col-6 col-md-4">
                    <div class="info-label" data-en="{{ $f['label_en'] }}">{{ $f['label'] }}</div>
                    <div class="info-value">{{ $f['value'] ?? '-' }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Tab: Dokumen --}}
    <div class="tab-pane fade" id="tab-dokumen">
        <div class="veh-form-card">
            <div class="table-responsive">
                <table class="table table-hover" style="font-size:.87rem">
                    <thead>
                        <tr>
                            <th data-en="Document">Dokumen</th>
                            <th data-en="Number">Nomor</th>
                            <th data-en="Valid Until">Berlaku Hingga</th>
                            <th data-en="Status">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $docs = [
                                ['label'=>'STNK',      'number'=>$vehicle->stnk_number,             'expiry'=>$vehicle->tax_expiry_date],
                                ['label'=>'KIR',       'number'=>$vehicle->kir_number,               'expiry'=>$vehicle->kir_expiry_date],
                                ['label'=>'BPKB',      'number'=>$vehicle->bpkb_number,             'expiry'=>null],
                                ['label'=>'Asuransi',  'number'=>$vehicle->insurance_policy_number, 'expiry'=>$vehicle->insurance_expiry_date],
                            ];
                        @endphp
                        @foreach($docs as $doc)
                        <tr>
                            <td class="fw-semibold">{{ $doc['label'] }}</td>
                            <td style="font-family:monospace">{{ $doc['number'] ?? '-' }}</td>
                            <td>{{ $doc['expiry'] ? $doc['expiry']->format('d M Y') : '-' }}</td>
                            <td>
                                @if($doc['expiry'])
                                    @if($doc['expiry']->isPast())
                                        <span class="expiry-expired"><i class="fas fa-times-circle"></i>
                                            <span data-en="Expired">Kedaluwarsa</span>
                                        </span>
                                    @elseif($doc['expiry']->diffInDays(now()) <= 30)
                                        <span class="expiry-soon"><i class="fas fa-history"></i>
                                            <span data-en="Expiring soon">Segera habis</span>
                                        </span>
                                    @else
                                        <span class="expiry-ok"><i class="fas fa-check-circle"></i>
                                            <span data-en="Active">Aktif</span>
                                        </span>
                                    @endif
                                @else
                                    <span style="color:var(--text-faint)">—</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Tab: Foto --}}
    <div class="tab-pane fade" id="tab-foto">
        <div class="veh-form-card">
            @if($vehicle->photos->count())
                <div class="photo-preview-grid">
                    @foreach($vehicle->photos->sortByDesc('is_primary')->sortBy('sort_order') as $photo)
                    <div class="photo-item">
                        <img src="{{ Storage::url($photo->file_path) }}" alt="{{ $photo->caption }}">
                        @if($photo->is_primary)
                            <span class="photo-badge-primary" data-en="PRIMARY">UTAMA</span>
                        @endif
                        @if($photo->caption)
                            <div class="photo-caption">{{ $photo->caption }}</div>
                        @endif
                        <div class="photo-overlay">
                            <a href="{{ Storage::url($photo->file_path) }}" target="_blank"
                               class="btn btn-sm btn-light" title="Lihat asli">
                                <i class="fas fa-expand"></i>
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="mt-3 text-end">
                    <a href="{{ route('master-data.kendaraan.edit', $vehicle) }}#tab-foto"
                       class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-plus"></i>
                        <span data-en="Manage Photos">Kelola Foto</span>
                    </a>
                </div>
            @else
                <div class="veh-empty">
                    <div class="veh-empty-icon"><i class="fas fa-images"></i></div>
                    <h6 data-en="No photos yet">Belum ada foto</h6>
                    <p>
                        <a href="{{ route('master-data.kendaraan.edit', $vehicle) }}#tab-foto"
                           data-en="Upload now">Upload sekarang</a>
                    </p>
                </div>
            @endif
        </div>
    </div>

    {{-- Tab: Catatan --}}
    <div class="tab-pane fade" id="tab-catatan">
        <div class="veh-form-card">
            @if($vehicle->notes)
                <div style="white-space:pre-wrap;font-size:.9rem;line-height:1.7;color:var(--text)">{{ $vehicle->notes }}</div>
            @else
                <p style="color:var(--text-muted)" class="mb-0" data-en="No notes.">Tidak ada catatan.</p>
            @endif

            <div class="mt-4 pt-3 border-top row g-2" style="font-size:.8rem;color:var(--text-muted)">
                <div class="col-md-4">
                    <div class="info-label" data-en="Created by">Dibuat oleh</div>
                    <div>{{ $vehicle->createdBy?->name ?? '-' }}</div>
                </div>
                <div class="col-md-4">
                    <div class="info-label" data-en="Created on">Dibuat pada</div>
                    <div>{{ $vehicle->created_at?->format('d M Y H:i') ?? '-' }}</div>
                </div>
                <div class="col-md-4">
                    <div class="info-label" data-en="Last modified">Terakhir diubah</div>
                    <div>{{ $vehicle->updated_at?->format('d M Y H:i') ?? '-' }}</div>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script type="module" src="{{ asset('js/pages/vehicle.js') }}"></script>
@endpush