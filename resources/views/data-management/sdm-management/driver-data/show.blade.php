@extends('layouts.admin')

@section('title', $driver->full_name . ' — Detail Supir')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
@endpush

@section('content')

    {{-- ── Page Header ──────────────────────────────────────────── --}}
    <div class="drv-page-header mb-3">
        <div>
            <div class="drv-title">
                <span data-en="Driver Detail">Detail Supir</span>
            </div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" data-en="Home">Beranda</a></li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('data-management.sdm-management.driver-data.index') }}"
                            data-en="Drivers">Supir</a>
                    </li>
                    <li class="breadcrumb-item active">{{ $driver->full_name }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('data-management.sdm-management.driver-data.pdf', $driver) }}" target="_blank"
                class="btn btn-sm btn-danger d-flex align-items-center gap-1">
                <i class="fas fa-file-pdf"></i>
                <span data-en="Export CV">Export CV</span>
            </a>
        </div>
    </div>

    {{-- ── Hero: foto + info utama ──────────────────────────────── --}}
    <div class="row g-3 mb-3">
        {{-- Foto: card kotak, foto circle di tengah --}}
        <div class="col-md-2 col-lg-2" style="min-width:160px;max-width:200px">
            <div class="card border-0 shadow-sm h-100 d-flex align-items-center justify-content-center"
                style="padding:1.25rem 0">
                {{--
                id="driverPhotoThumb" → target JS lightbox
                data-src              → URL foto full
                data-name             → nama supir (caption lightbox)
            --}}
                <div id="driverPhotoThumb" data-src="{{ $driver->photo_path ? Storage::url($driver->photo_path) : '' }}"
                    data-name="{{ $driver->full_name }}"
                    style="width:120px;height:120px;border-radius:50%;overflow:hidden;
                        background:var(--surface-alt,#2a2d3e);
                        border:3px solid var(--border,rgba(255,255,255,.12));
                        {{ $driver->photo_path ? 'cursor:zoom-in' : '' }}">
                    @if ($driver->photo_path)
                        <img src="{{ Storage::url($driver->photo_path) }}" alt="{{ $driver->full_name }}"
                            style="width:100%;height:100%;object-fit:cover;display:block">
                    @else
                        <div
                            style="width:100%;height:100%;display:flex;align-items:center;
                                justify-content:center;color:var(--text-muted)">
                            <i class="fas fa-user fa-3x"></i>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Info utama --}}
        <div class="col">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex align-items-start justify-content-between mb-3">
                        <div>
                            <h4 class="mb-0 fw-bold" style="font-size:1.3rem;color:var(--text)">
                                {{ $driver->full_name }}
                            </h4>
                        </div>
                        <span class="badge-status status-drv-{{ $driver->status }}">
                            {{ $driver->status_label }}
                        </span>
                    </div>
                    <div class="row g-3 flex-grow-1">
                        @php
                            $heroFields = [
                                [
                                    'label' => 'Kode Driver',
                                    'label_en' => 'Driver Code',
                                    'value' => $driver->driver_code,
                                ],
                                [
                                    'label' => 'NIK',
                                    'label_en' => 'NIK',
                                    'value' => $driver->nik,
                                ],
                                [
                                    'label' => 'No. Telepon',
                                    'label_en' => 'Phone Number',
                                    'value' => $driver->phone,
                                ],
                                ['label' => 'Jenis Kelamin', 'label_en' => 'Gender', 'value' => $driver->gender_label],
                                [
                                    'label' => 'Tgl Lahir',
                                    'label_en' => 'Birth Date',
                                    'value' => $driver->birth_date?->format('d M Y'),
                                ],
                                [
                                    'label' => 'Tempat Lahir',
                                    'label_en' => 'Birth Place',
                                    'value' => $driver->birth_place,
                                ],
                                [
                                    'label' => 'Status Nikah',
                                    'label_en' => 'Marital',
                                    'value' => $driver->marital_status_label ?? $driver->marital_status,
                                ],
                                [
                                    'label' => 'Tgl Bergabung',
                                    'label_en' => 'Join Date',
                                    'value' => $driver->join_date?->format('d M Y'),
                                ],
                                ['label' => 'Email', 'label_en' => 'Email', 'value' => $driver->email],
                            ];
                        @endphp
                        @foreach ($heroFields as $f)
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

    {{-- ── Tab Navigation ───────────────────────────────────────── --}}
    <div class="drv-tab-nav mb-0">
        <ul class="nav nav-tabs" id="driverTabs" role="tablist">
            @php
                $tabs = [
                    [
                        'id' => 'tab-ktp',
                        'icon' => 'fas fa-id-card',
                        'label' => 'Alamat KTP',
                        'label_en' => 'ID Card Adress',
                    ],
                    [
                        'id' => 'tab-alamat',
                        'icon' => 'fas fa-map-marker-alt',
                        'label' => 'Alamat Domisili',
                        'label_en' => 'Residential Address',
                    ],
                    [
                        'id' => 'tab-dokumen',
                        'icon' => 'fas fa-file-alt',
                        'label' => 'Dokumen',
                        'label_en' => 'Documents',
                        'badge' => $driver->documents->count(),
                    ],
                    [
                        'id' => 'tab-sertifikat',
                        'icon' => 'fas fa-certificate',
                        'label' => 'Sertifikat',
                        'label_en' => 'Certificates',
                        'badge' => $driver->certificates->count(),
                    ],
                    [
                        'id' => 'tab-kontak-darurat',
                        'icon' => 'fas fa-phone-alt',
                        'label' => 'Kontak Darurat',
                        'label_en' => 'Emergency Contacts',
                        'badge' => $driver->emergencyContacts->count(),
                    ],
                    ['id' => 'tab-catatan', 'icon' => 'fas fa-book', 'label' => 'Catatan', 'label_en' => 'Notes'],
                ];
            @endphp
            @foreach ($tabs as $i => $tab)
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $i === 0 ? 'active' : '' }}" data-bs-toggle="tab"
                        data-bs-target="#{{ $tab['id'] }}" type="button">
                        <i class="{{ $tab['icon'] }}"></i>
                        <span class="d-none d-sm-inline" data-en="{{ $tab['label_en'] }}">{{ $tab['label'] }}</span>
                        @if (!empty($tab['badge']) && $tab['badge'] > 0)
                            <span class="drv-tab-badge">{{ $tab['badge'] }}</span>
                        @endif
                    </button>
                </li>
            @endforeach
        </ul>
    </div>

    <div class="tab-content">

        {{-- Tab: KTP --}}
        <div class="tab-pane fade show active" id="tab-ktp">
            <div class="drv-form-card">
                <div class="row g-3">
                    @php
                        $ktpFields = [
                            ['label' => 'Provinsi', 'label_en' => 'Province', 'value' => $driver->ktp_province],
                            ['label' => 'Kota/Kab.', 'label_en' => 'City', 'value' => $driver->ktp_city],
                            ['label' => 'Kecamatan', 'label_en' => 'District', 'value' => $driver->ktp_district],
                            ['label' => 'Kelurahan', 'label_en' => 'Village', 'value' => $driver->ktp_village],
                            ['label' => 'RT / RW', 'label_en' => 'RT/RW', 'value' => $driver->ktp_rt_rw],
                            ['label' => 'Kode Pos', 'label_en' => 'Postal Code', 'value' => $driver->ktp_postal_code],
                            [
                                'label' => 'Alamat KTP',
                                'label_en' => 'KTP Address',
                                'value' => $driver->ktp_address,
                                'full' => true,
                            ],
                        ];
                    @endphp
                    @foreach ($ktpFields as $f)
                        <div class="{{ $f['full'] ?? false ? 'col-12' : 'col-6 col-md-6' }}">
                            <div class="info-label" data-en="{{ $f['label_en'] }}">{{ $f['label'] }}</div>
                            <div class="info-value">{{ $f['value'] ?? '-' }}</div>
                        </div>
                    @endforeach

                    @if ($driver->ktp_file_path)
                        <div class="col-12 mt-2">
                            <div class="info-label" data-en="KTP File">File KTP</div>
                            <a href="{{ Storage::url($driver->ktp_file_path) }}" target="_blank"
                                class="btn btn-sm btn-outline-secondary mt-1">
                                <i class="fas fa-file-image me-1"></i>
                                <span data-en="View File">Lihat File</span>
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Tab: Alamat --}}
        <div class="tab-pane fade" id="tab-alamat">
            <div class="drv-form-card">
                @if ($driver->domicile_same_as_ktp)
                    <div class="alert alert-info d-flex align-items-center gap-2 mb-3" style="font-size:.875rem">
                        <i class="fas fa-info-circle"></i>
                        <span data-en="Domicile address is the same as KTP address.">Alamat domisili sama dengan alamat
                            KTP.</span>
                    </div>
                @endif
                <div class="row g-3">
                    @php
                        $domFields = [
                            ['label' => 'Provinsi', 'label_en' => 'Province', 'value' => $driver->domicile_province],
                            ['label' => 'Kota/Kab.', 'label_en' => 'City', 'value' => $driver->domicile_city],
                            ['label' => 'Kecamatan', 'label_en' => 'District', 'value' => $driver->domicile_district],
                            ['label' => 'Kelurahan', 'label_en' => 'Village', 'value' => $driver->domicile_village],
                            [
                                'label' => 'RT / RW',
                                'label_en' => 'RT/RW',
                                'value' => $driver->domicile_rt_rw,
                            ],
                            [
                                'label' => 'Kode Pos',
                                'label_en' => 'Postal Code',
                                'value' => $driver->domicile_postal_code,
                            ],
                            [
                                'label' => 'Alamat Domisili',
                                'label_en' => 'Domicile Address',
                                'value' => $driver->domicile_address,
                                'full' => true,
                            ],
                        ];
                    @endphp
                    @foreach ($domFields as $f)
                        <div class="{{ $f['full'] ?? false ? 'col-12' : 'col-6 col-md-6' }}">
                            <div class="info-label" data-en="{{ $f['label_en'] }}">{{ $f['label'] }}</div>
                            <div class="info-value">{{ $f['value'] ?? '-' }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Tab: Dokumen --}}
        <div class="tab-pane fade" id="tab-dokumen">
            <div class="drv-form-card">
                @if ($driver->documents->isEmpty())
                    <div class="drv-empty">
                        <div class="drv-empty-icon"><i class="fas fa-file-alt"></i></div>
                        <h6 data-en="No documents">Belum ada dokumen</h6>
                        <p>
                            <a href="{{ route('data-management.sdm-management.driver-data.edit', $driver) }}#tab-dokumen"
                                data-en="Add now">Tambah sekarang</a>
                        </p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover" style="font-size:.87rem">
                            <thead>
                                <tr>
                                    <th data-en="Document Name">Nama Dokumen</th>
                                    <th data-en="Number">Nomor</th>
                                    <th data-en="Issued">Terbit</th>
                                    <th data-en="Expiry">Kadaluarsa</th>
                                    <th data-en="File">File</th>
                                    <th data-en="Status">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($driver->documents as $doc)
                                    <tr>
                                        <td class="fw-semibold">{{ $doc->document_name }}</td>
                                        <td style="font-family:monospace">{{ $doc->document_number ?? '-' }}</td>
                                        <td>{{ $doc->issued_date?->format('d M Y') ?? '-' }}</td>
                                        <td>{{ $doc->expiry_date?->format('d M Y') ?? '-' }}</td>
                                        <td>
                                            @if ($doc->file_path)
                                                <a href="{{ Storage::url($doc->file_path) }}" target="_blank"
                                                    class="btn btn-xs btn-outline-primary"
                                                    style="font-size:.75rem;padding:2px 8px">
                                                    <i class="fas fa-file me-1"></i>Lihat
                                                </a>
                                            @else
                                                <span style="color:var(--text-faint)">—</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($doc->expiry_date)
                                                @if ($doc->is_expired)
                                                    <span class="expiry-expired"><i class="fas fa-times-circle"></i> <span
                                                            data-en="Expired">Kedaluwarsa</span></span>
                                                @elseif($doc->is_expiring_soon)
                                                    <span class="expiry-soon"><i class="fas fa-history"></i> <span
                                                            data-en="Expiring soon">Segera habis</span></span>
                                                @else
                                                    <span class="expiry-ok"><i class="fas fa-check-circle"></i> <span
                                                            data-en="Active">Aktif</span></span>
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
                @endif
            </div>
        </div>

        {{-- Tab: Sertifikat --}}
        <div class="tab-pane fade" id="tab-sertifikat">
            <div class="drv-form-card">
                @if ($driver->certificates->isEmpty())
                    <div class="drv-empty">
                        <div class="drv-empty-icon"><i class="fas fa-certificate"></i></div>
                        <h6 data-en="No certificates">Belum ada sertifikat</h6>
                        <p>
                            <a href="{{ route('data-management.sdm-management.driver-data.edit', $driver) }}#tab-sertifikat"
                                data-en="Add now">Tambah sekarang</a>
                        </p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover" style="font-size:.87rem">
                            <thead>
                                <tr>
                                    <th data-en="Certificate">Sertifikat</th>
                                    <th data-en="Number">Nomor</th>
                                    <th data-en="Issuer">Penerbit</th>
                                    <th data-en="Expiry">Kadaluarsa</th>
                                    <th data-en="File">File</th>
                                    <th data-en="Status">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($driver->certificates as $cert)
                                    <tr>
                                        <td class="fw-semibold">{{ $cert->certificate_name }}</td>
                                        <td style="font-family:monospace">{{ $cert->certificate_number ?? '-' }}</td>
                                        <td>{{ $cert->issuer ?? '-' }}</td>
                                        <td>{{ $cert->expiry_date?->format('d M Y') ?? '-' }}</td>
                                        <td>
                                            @if ($cert->file_path)
                                                <a href="{{ Storage::url($cert->file_path) }}" target="_blank"
                                                    class="btn btn-xs btn-outline-primary"
                                                    style="font-size:.75rem;padding:2px 8px">
                                                    <i class="fas fa-file me-1"></i>Lihat
                                                </a>
                                            @else
                                                <span style="color:var(--text-faint)">—</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($cert->expiry_date)
                                                @if ($cert->is_expired)
                                                    <span class="expiry-expired"><i class="fas fa-times-circle"></i> <span
                                                            data-en="Expired">Kedaluwarsa</span></span>
                                                @elseif($cert->is_expiring_soon)
                                                    <span class="expiry-soon"><i class="fas fa-history"></i> <span
                                                            data-en="Expiring soon">Segera habis</span></span>
                                                @else
                                                    <span class="expiry-ok"><i class="fas fa-check-circle"></i> <span
                                                            data-en="Active">Aktif</span></span>
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
                @endif
            </div>
        </div>

        {{-- Tab: Kontak Darurat --}}
        <div class="tab-pane fade" id="tab-kontak-darurat">
            <div class="drv-form-card">
                @if ($driver->emergencyContacts->isEmpty())
                    <div class="drv-empty">
                        <div class="drv-empty-icon"><i class="fas fa-phone-alt"></i></div>
                        <h6 data-en="No emergency contacts">Belum ada kontak darurat</h6>
                        <p>
                            <a href="{{ route('data-management.sdm-management.driver-data.edit', $driver) }}#tab-kontak-darurat"
                                class="btn btn-sm btn-outline-primary mt-2">
                                <i class="fas fa-plus me-1"></i> Tambah Kontak Darurat
                            </a>
                        </p>
                    </div>
                @else
                    @foreach ($driver->emergencyContacts as $ec)
                        <div class="ec-show-card mb-4">
                            {{-- Card header --}}
                            <div class="ec-show-header d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="ec-show-avatar">
                                        <i class="fas fa-user-shield"></i>
                                    </div>
                                    <div>
                                        <div class="ec-show-name">{{ $ec->full_name }}</div>
                                        <div class="ec-show-meta">
                                            <span class="ec-show-type-badge">{{ $ec->contact_type_label }}</span>
                                            &bull;
                                            <span>{{ $ec->phone_primary }}</span>
                                        </div>
                                    </div>
                                </div>
                                <span
                                    class="{{ $ec->contact_status === 'aktif' ? 'ec-show-status-aktif' : 'ec-show-status-nonaktif' }}">
                                    {{ $ec->contact_status_label }}
                                </span>
                            </div>

                            {{-- Card body --}}
                            <div class="ec-show-body">
                                <div class="form-section-title" style="font-size:.7rem;margin-bottom:.75rem">
                                    <i class="fas fa-info-circle me-1"></i><span>Identitas</span>
                                </div>
                                <div class="row g-3 mb-3">
                                    <div class="col-md-6">
                                        <div class="info-label" data-en="Full Name">Nama Lengkap</div>
                                        <div class="info-value">{{ $ec->full_name ?? '-' }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-label">NIK</div>
                                        <div class="info-value">{{ $ec->nik ?? '-' }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-label" data-en="Place of Birth">Tempat Lahir</div>
                                        <div class="info-value">{{ $ec->birth_place ?? '-' }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-label" data-en="Date of Birth">Tanggal Lahir</div>
                                        <div class="info-value">{{ $ec->birth_date?->format('d M Y') ?? '-' }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-label" data-en="Gender">Jenis Kelamin</div>
                                        <div class="info-value">{{ $ec->gender_label ?? '-' }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-label" data-en="Religion">Agama</div>
                                        <div class="info-value">{{ $ec->religion_label ?? '-' }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-label" data-en="Marital Status">Status Pernikahan</div>
                                        <div class="info-value">{{ $ec->marital_status_label ?? '-' }}</div>
                                    </div>
                                </div>

                                <div class="form-section-title" style="font-size:.7rem;margin-bottom:.75rem">
                                    <i class="fas fa-phone me-1"></i><span>Kontak</span>
                                </div>
                                <div class="row g-3 mb-3">
                                    <div class="col-md-6">
                                        <div class="info-label" data-en="Primary Phone">No. Telepon Utama</div>
                                        <div class="info-value">{{ $ec->phone_primary ?? '-' }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-label" data-en="Alternative Phone">No. Telepon Alternatif</div>
                                        <div class="info-value">{{ $ec->phone_alternative ?? '-' }}</div>
                                    </div>
                                </div>

                                <div class="form-section-title" style="font-size:.7rem;margin-bottom:.75rem">
                                    <i class="fas fa-map-marked-alt me-1"></i><span>Alamat</span>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="info-label" data-en="Province">Provinsi</div>
                                        <div class="info-value">{{ $ec->province ?? '-' }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-label" data-en="City">Kota/Kabupaten</div>
                                        <div class="info-value">{{ $ec->city ?? '-' }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-label" data-en="District">Kecamatan</div>
                                        <div class="info-value">{{ $ec->district ?? '-' }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-label" data-en="Village">Kelurahan/Desa</div>
                                        <div class="info-value">{{ $ec->village ?? '-' }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-label">RT/RW</div>
                                        <div class="info-value">{{ $ec->rt_rw ?? '-' }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-label" data-en="Postal Code">Kode Pos</div>
                                        <div class="info-value">{{ $ec->postal_code ?? '-' }}</div>
                                    </div>
                                    <div class="col-12">
                                        <div class="info-label" data-en="Full Address">Alamat Lengkap</div>
                                        <div class="info-value">{{ $ec->full_address ?? '-' }}</div>
                                    </div>
                                </div>
                            </div>{{-- /ec-show-body --}}
                        </div>{{-- /ec-show-card --}}
                    @endforeach
                @endif
            </div>
        </div>

        <style>
            .ec-show-card {
                border: 1px solid var(--border);
                border-radius: var(--drv-radius, 14px);
                overflow: hidden;
                background: var(--surface);
                box-shadow: var(--drv-shadow);
                transition: box-shadow var(--drv-transition, .18s ease);
            }

            .ec-show-card:hover {
                box-shadow: var(--drv-shadow-hover);
            }

            .ec-show-header {
                padding: .875rem 1.25rem;
                background: var(--surface-2, rgba(255, 255, 255, .04));
                border-bottom: 1px solid var(--border);
                border-left: 3px solid var(--drv-accent);
            }

            .ec-show-avatar {
                width: 40px;
                height: 40px;
                border-radius: 50%;
                background: var(--drv-accent-soft);
                border: 1.5px solid var(--drv-accent);
                display: flex;
                align-items: center;
                justify-content: center;
                color: var(--drv-accent);
                font-size: .95rem;
                flex-shrink: 0;
            }

            .ec-show-name {
                font-weight: 600;
                font-size: .9rem;
                color: var(--text);
                line-height: 1.3;
            }

            .ec-show-meta {
                font-size: .78rem;
                color: var(--text-muted);
                margin-top: 1px;
                display: flex;
                align-items: center;
                gap: .35rem;
                flex-wrap: wrap;
            }

            .ec-show-type-badge {
                display: inline-block;
                padding: 1px 7px;
                border-radius: 20px;
                font-size: .7rem;
                font-weight: 600;
                background: var(--drv-accent-soft);
                color: var(--drv-accent);
                border: 1px solid var(--drv-accent);
            }

            .ec-show-status-aktif {
                padding: 2px 10px;
                border-radius: 20px;
                font-size: .7rem;
                font-weight: 600;
                background: rgba(22, 163, 74, .15);
                color: #16a34a;
                border: 1px solid rgba(22, 163, 74, .35);
            }

            .ec-show-status-nonaktif {
                padding: 2px 10px;
                border-radius: 20px;
                font-size: .7rem;
                font-weight: 600;
                background: rgba(100, 116, 139, .12);
                color: var(--text-muted);
                border: 1px solid rgba(100, 116, 139, .25);
            }

            .ec-show-body {
                padding: 1.25rem;
            }
        </style>

        {{-- Tab: Catatan --}}
        <div class="tab-pane fade" id="tab-catatan">
            <div class="drv-form-card">
                @if ($driver->notes)
                    <div style="white-space:pre-wrap;font-size:.9rem;line-height:1.7;color:var(--text)">
                        {{ $driver->notes }}
                    </div>
                @else
                    <p style="color:var(--text-muted)" class="mb-0" data-en="No notes.">Tidak ada catatan.</p>
                @endif

                <div class="mt-4 pt-3 border-top row g-2" style="font-size:.8rem;color:var(--text-muted)">
                    <div class="col-md-4">
                        <div class="info-label" data-en="Created by">Dibuat oleh</div>
                        <div>{{ $driver->createdBy?->name ?? '-' }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-label" data-en="Created on">Dibuat pada</div>
                        <div>{{ $driver->created_at?->format('d M Y H:i') ?? '-' }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-label" data-en="Last modified">Terakhir diubah</div>
                        <div>{{ $driver->updated_at?->format('d M Y H:i') ?? '-' }}</div>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection

@push('scripts')
    @vite('resources/js/pages/driver.js')
@endpush
