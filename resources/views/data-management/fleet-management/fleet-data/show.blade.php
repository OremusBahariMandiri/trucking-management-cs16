@extends('layouts.admin')

@section('title', $fleet->vehicle_code ?? $fleet->police_number ?? 'Detail Kendaraan')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
@endpush

@section('content')

{{-- ── Page Header ────────────────────────────────────────── --}}
<div class="veh-page-header mb-3">
    <div>
        <div class="veh-title">
            <span data-en="Vehicle Detail">Detail Kendaraan</span>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" data-en="Home">Beranda</a></li>
                <li class="breadcrumb-item"><a href="{{ route('data-management.fleet-data.index') }}" data-en="Fleet">Kendaraan</a></li>
                <li class="breadcrumb-item active">{{ $fleet->vehicle_code ?? $fleet->police_number }}</li>
            </ol>
        </nav>
    </div>
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('data-management.fleet-data.pdf', $fleet) }}"
           class="btn btn-danger btn-sm d-flex align-items-center gap-2"
           target="_blank"
           title="Generate Vehicle Particular PDF">
            <i class="fas fa-file-pdf"></i>
            <span data-en="Vehicle Particular">Fleet Particular</span>
        </a>
    </div>
</div>

{{-- ── Hero: foto + info utama ─────────────────────────────── --}}
<div class="row g-3 mb-3">
    {{-- Foto --}}
    <div class="col-md-4">
        <div class="veh-show-hero">
            @php $primaryPhoto = $fleet->photos->firstWhere('is_primary', true) ?? $fleet->photos->first(); @endphp
            @if($primaryPhoto)
                <img src="{{ Storage::url($primaryPhoto->file_path) }}"
                     alt="{{ $fleet->vehicle_code }}"
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
                            {{ $fleet->vehicle_code ?? '-' }}
                        </h4>
                        <div style="font-size:.88rem;color:var(--text-muted)">
                            {{ $fleet->brand?->name }} {{ $fleet->model?->name ?? $fleet->model_name }}
                            @if($fleet->manufacture_year) · {{ $fleet->manufacture_year }} @endif
                        </div>
                    </div>
                    <span class="badge-status status-{{ $fleet->vehicle_status ?? 'inactive' }}">
                        {{ $fleet->status_label }}
                    </span>
                </div>

                <div class="row g-3 flex-grow-1">
                    @php
                        $heroFields = [
                            ['label'=>'Nomor Polisi',   'label_en'=>'Police Number',   'value'=>$fleet->police_number],
                            ['label'=>'Nomor Lambung',  'label_en'=>'Hull Number',      'value'=>$fleet->hull_number],
                            ['label'=>'Jenis',          'label_en'=>'Type',             'value'=>$fleet->type?->name],
                            ['label'=>'Warna',          'label_en'=>'Color',            'value'=>$fleet->color],
                            ['label'=>'Kepemilikan',    'label_en'=>'Ownership',        'value'=>$fleet->ownership_label],
                            ['label'=>'GPS',            'label_en'=>'GPS',              'value'=>match($fleet->gps_status){
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
                        ({{ $fleet->photos->count() }})
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
                        ['label'=>'Nomor Rangka',       'label_en'=>'Chassis Number',     'value'=>$fleet->chassis_number],
                        ['label'=>'Nomor Mesin',        'label_en'=>'Engine Number',       'value'=>$fleet->engine_number],
                        ['label'=>'Bahan Bakar',        'label_en'=>'Fuel Type',           'value'=>$fleet->fuelType?->name],
                        ['label'=>'Kapasitas Mesin',    'label_en'=>'Engine Capacity',     'value'=>$fleet->engine_capacity_cc ? number_format($fleet->engine_capacity_cc).' cc' : null],
                        ['label'=>'Konfigurasi Sumbu',  'label_en'=>'Axle Config',         'value'=>$fleet->axis_configuration],
                        ['label'=>'Jumlah Roda',        'label_en'=>'Wheel Count',         'value'=>$fleet->wheel_count ? $fleet->wheel_count.' roda' : null],
                        ['label'=>'Kapasitas Muatan',   'label_en'=>'Load Capacity',       'value'=>$fleet->load_capacity_ton ? $fleet->load_capacity_ton.' ton' : null],
                        ['label'=>'Berat Kotor (GVW)',  'label_en'=>'Gross Weight (GVW)',  'value'=>$fleet->gross_vehicle_weight_ton ? $fleet->gross_vehicle_weight_ton.' ton' : null],
                        ['label'=>'Berat Kosong',       'label_en'=>'Tare Weight',         'value'=>$fleet->tare_weight_ton ? $fleet->tare_weight_ton.' ton' : null],
                        ['label'=>'Jenis Body',         'label_en'=>'Body Type',           'value'=>$fleet->bodyType?->name],
                        ['label'=>'Dimensi Body',       'label_en'=>'Body Dimensions',     'value'=>$fleet->body_dimension],
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
                        ['label'=>'Status Kepemilikan', 'label_en'=>'Ownership Status',   'value'=>$fleet->ownership_label],
                        ['label'=>'Tahun Perolehan',    'label_en'=>'Acquisition Year',   'value'=>$fleet->acquisition_year],
                        ['label'=>'Nilai Perolehan',    'label_en'=>'Acquisition Value',  'value'=>$fleet->acquisition_value ? 'Rp '.number_format($fleet->acquisition_value,0,',','.') : null],
                        ['label'=>'Perusahaan Leasing', 'label_en'=>'Leasing Company',    'value'=>$fleet->leasing_company],
                        ['label'=>'Akhir Leasing',      'label_en'=>'Leasing End',        'value'=>$fleet->leasing_end_date?->format('d M Y')],
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
                        ['label'=>'Odometer Terakhir',  'label_en'=>'Last Odometer',    'value'=>$fleet->last_odometer_km ? number_format($fleet->last_odometer_km).' km' : null],
                        ['label'=>'Servis Terakhir',    'label_en'=>'Last Service',     'value'=>$fleet->last_service_date?->format('d M Y')],
                        ['label'=>'Interval Servis',    'label_en'=>'Service Interval', 'value'=>$fleet->service_interval_km ? number_format($fleet->service_interval_km).' km' : null],
                        ['label'=>'ID GPS',             'label_en'=>'GPS ID',           'value'=>$fleet->gps_tracker_id],
                        ['label'=>'Provider GPS',       'label_en'=>'GPS Provider',     'value'=>$fleet->gps_provider],
                        ['label'=>'Status GPS',         'label_en'=>'GPS Status',       'value'=>match($fleet->gps_status){'active'=>'Aktif','offline'=>'Offline','not_installed'=>'Tidak Terpasang',default=>'-'}],
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

            @php
                // Index dokumen dari relasi supaya mudah di-lookup per tipe
                $docFiles = $fleet->documents->keyBy('document_type');

                $docs = [
                    [
                        'label'    => 'STNK',
                        'label_en' => 'STNK',
                        'type'     => 'stnk',
                        'number'   => $fleet->stnk_number,
                        'expiry'   => $fleet->tax_expiry_date,
                    ],
                    [
                        'label'    => 'KIR',
                        'label_en' => 'KIR',
                        'type'     => 'kir',
                        'number'   => $fleet->kir_number,
                        'expiry'   => $fleet->kir_expiry_date,
                    ],
                    [
                        'label'    => 'BPKB',
                        'label_en' => 'BPKB',
                        'type'     => 'bpkb',
                        'number'   => $fleet->bpkb_number,
                        'expiry'   => null,
                    ],
                    [
                        'label'    => 'Asuransi',
                        'label_en' => 'Insurance',
                        'type'     => 'asuransi',
                        'number'   => $fleet->insurance_policy_number,
                        'expiry'   => $fleet->insurance_expiry_date,
                    ],
                ];
            @endphp

            <div class="table-responsive">
                <table class="table table-hover align-middle" style="font-size:.87rem">
                    <thead>
                        <tr>
                            <th data-en="Document">Dokumen</th>
                            <th data-en="Number">Nomor</th>
                            <th data-en="Valid Until">Berlaku Hingga</th>
                            <th data-en="Status">Status</th>
                            <th data-en="File" class="text-center">File</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($docs as $doc)
                        @php
                            $docRecord = $docFiles->get($doc['type']);
                            $fileUrl   = $docRecord?->file_url;
                            $fileName  = $docRecord?->file_name;
                            $isPdf     = $fileName && str_ends_with(strtolower($fileName), '.pdf');
                        @endphp
                        <tr>
                            <td class="fw-semibold">{{ $doc['label'] }}</td>
                            <td style="font-family:monospace">{{ $doc['number'] ?? '-' }}</td>
                            <td>{{ $doc['expiry'] ? $doc['expiry']->format('d M Y') : '-' }}</td>
                            <td>
                                @if($doc['expiry'])
                                    @if($doc['expiry']->isPast())
                                        <span class="expiry-expired">
                                            <i class="fas fa-times-circle"></i>
                                            <span data-en="Expired">Kedaluwarsa</span>
                                        </span>
                                    @elseif($doc['expiry']->diffInDays(now()) <= 30)
                                        <span class="expiry-soon">
                                            <i class="fas fa-history"></i>
                                            <span data-en="Expiring soon">Segera habis</span>
                                        </span>
                                    @else
                                        <span class="expiry-ok">
                                            <i class="fas fa-check-circle"></i>
                                            <span data-en="Active">Aktif</span>
                                        </span>
                                    @endif
                                @else
                                    <span style="color:var(--text-faint)">—</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($fileUrl)
                                    <a href="{{ $fileUrl }}"
                                       target="_blank"
                                       rel="noopener noreferrer"
                                       class="btn btn-sm btn-primary d-inline-flex align-items-center gap-1"
                                       title="{{ $fileName }}">
                                        <i class="fas {{ $isPdf ? 'fa-file-pdf' : 'fa-file-image' }}"></i>
                                        <span data-en="Open">Buka</span>
                                    </a>
                                @else
                                    <span class="text-muted" style="font-size:.8rem">
                                        <i class="fas fa-minus"></i>
                                    </span>
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

            @php
                $photos    = $fleet->photos->sortByDesc('is_primary')->sortBy('sort_order');
                $catLabels = \App\Models\MasterData\Vehicle\VehiclePhoto::CATEGORIES;
            @endphp

            @if($photos->count())

            <div class="table-responsive">
                <table class="table table-hover align-middle" style="font-size:.87rem">
                    <thead>
                        <tr>
                            <th data-en="Category">Kategori</th>
                            <th data-en="Caption">Keterangan</th>
                            <th data-en="File" class="text-center">File</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($photos as $photo)
                        @php
                            $catLabel = $catLabels[$photo->category] ?? ucfirst($photo->category ?? 'lainnya');
                            $sizeStr  = $photo->file_size_kb
                                ? ($photo->file_size_kb >= 1024
                                    ? number_format($photo->file_size_kb / 1024, 1).' MB'
                                    : $photo->file_size_kb.' KB')
                                : '-';
                        @endphp
                        <tr>
                            <td class="fw-semibold">{{ $catLabel }}</td>

                            {{-- Keterangan --}}
                            <td style="color:{{ $photo->caption ? 'var(--text)' : 'var(--text-faint)' }};
                                       font-style:{{ $photo->caption ? 'normal' : 'italic' }}">
                                {{ $photo->caption ?? 'Tanpa keterangan' }}
                            </td>

                            {{-- Buka --}}
                            <td class="text-center">
                                <a href="{{ Storage::url($photo->file_path) }}"
                                   target="_blank"
                                   rel="noopener noreferrer"
                                   class="btn btn-sm btn-primary d-inline-flex align-items-center gap-1"
                                   title="{{ $photo->file_name }}">
                                    <i class="fas fa-file-image"></i>
                                    <span data-en="Open">Buka</span>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-2 text-end">
                <a href="{{ route('data-management.fleet-data.edit', $fleet) }}"
                   class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-pen me-1"></i>
                    <span data-en="Manage Photos">Kelola Foto</span>
                </a>
            </div>

            @else
            <div class="veh-empty">
                <div class="veh-empty-icon"><i class="fas fa-images"></i></div>
                <h6 data-en="No photos yet">Belum ada foto</h6>
                <p>
                    <a href="{{ route('data-management.fleet-data.edit', $fleet) }}"
                       data-en="Upload now">Upload sekarang</a>
                </p>
            </div>
            @endif

        </div>
    </div>

    {{-- Tab: Catatan --}}
    <div class="tab-pane fade" id="tab-catatan">
        <div class="veh-form-card">
            @if($fleet->notes)
                <div style="white-space:pre-wrap;font-size:.9rem;line-height:1.7;color:var(--text)">{{ $fleet->notes }}</div>
            @else
                <p style="color:var(--text-muted)" class="mb-0" data-en="No notes.">Tidak ada catatan.</p>
            @endif

            <div class="mt-4 pt-3 border-top row g-2" style="font-size:.8rem;color:var(--text-muted)">
                <div class="col-md-4">
                    <div class="info-label" data-en="Created by">Dibuat oleh</div>
                    <div>{{ $fleet->createdBy?->name ?? '-' }}</div>
                </div>
                <div class="col-md-4">
                    <div class="info-label" data-en="Created on">Dibuat pada</div>
                    <div>{{ $fleet->created_at?->format('d M Y H:i') ?? '-' }}</div>
                </div>
                <div class="col-md-4">
                    <div class="info-label" data-en="Last modified">Terakhir diubah</div>
                    <div>{{ $fleet->updated_at?->format('d M Y H:i') ?? '-' }}</div>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script type="module" src="{{ asset('js/pages/vehicle.js') }}"></script>
@endpush