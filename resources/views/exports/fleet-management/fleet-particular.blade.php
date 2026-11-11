<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Vehicle Particular – {{ $fleet->vehicle_code ?? $fleet->police_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 9px;
            color: #1a202c;
            background: #fff;
        }

        .page-pad {
            padding: 10mm 10mm 8mm 10mm;
        }

        @page {
            margin: 0;
            size: A4 portrait;
        }

        .page-break {
            page-break-after: always;
        }

        /* ════════════════════════════════
   HEADER
════════════════════════════════ */
        .doc-header {
            width: 100%;
            box-sizing: border-box;
            background: #1e3a5f;
            padding: 16px 0px;
            margin-bottom: 14px;
            text-align: center;
        }

        .doc-title {
            font-size: 18px;
            font-weight: bold;
            color: #ffffff;
            letter-spacing: 5px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .doc-subtitle {
            font-size: 8px;
            color: #7ab3d4;
            letter-spacing: 2px;
            margin-top: 4px;
            text-transform: uppercase;
            letter-spacing: .3px;
            margin-top: 2px;
        }

        .doc-meta-row {
            margin-top: 6px;
        }

        .doc-meta-item {
            display: inline-block;
            font-size: 7.5px;
            color: #94a3b8;
            margin-left: 12px;
        }

        .doc-meta-item span {
            color: #475569;
            font-weight: bold;
        }

        /* ════════════════════════════════
   HERO / IDENTITY BLOCK
════════════════════════════════ */
        .hero {
            display: table;
            width: 100%;
            border: 1px solid #dce6f0;
            margin-bottom: 12px;
            overflow: hidden;
        }

        .hero-photo-cell {
            display: table-cell;
            width: 130px;
            vertical-align: top;
            padding: 0;
            margin: 0;
            border: none;
            overflow: hidden;
            background: #fff;
        }

        .hero-photo-cell img {
            width: 170px;
            height: 160px;
            object-fit: cover;
            display: block;
            border: none;
            padding: 0;
            margin: 0;
        }

        .hero-photo-label {
            display: none;
        }

        .hero-photo-none {
            width: 130px;
            height: 130px;
            background: #dce6f0;
            display: block;
            line-height: 130px;
            text-align: center;
            font-size: 36px;
        }

        .hero-info-cell {
            display: table-cell;
            vertical-align: top;
            padding: 12px 16px;
            background: #f0f4f9;
        }

        .hero-code {
            font-size: 22px;
            font-weight: bold;
            color: #1e3a5f;
            letter-spacing: 2px;
        }

        .hero-police {
            font-size: 11px;
            color: #475569;
            margin-top: 2px;
            letter-spacing: .5px;
        }

        .hero-brand {
            font-size: 9px;
            color: #64748b;
            margin-top: 4px;
        }

        .hero-status {
            display: inline-block;
            margin-top: 7px;
            padding: 3px 14px;
            border-radius: 20px;
            font-size: 8px;
            font-weight: bold;
            letter-spacing: .8px;
            text-transform: uppercase;
        }

        .status-active {
            background: #dcfce7;
            color: #15803d;
            border: 1px solid #86efac;
        }

        .status-inactive {
            background: #f1f5f9;
            color: #475569;
            border: 1px solid #cbd5e1;
        }

        .status-other {
            background: #fef3c7;
            color: #92400e;
            border: 1px solid #fcd34d;
        }

        /* 4-field summary bar */
        .hero-fields {
            display: table;
            width: 100%;
            margin-top: 10px;
            border-top: 1px solid #dce6f0;
            padding-top: 8px;
        }

        .hero-field {
            display: table-cell;
            text-align: left;
            padding-right: 12px;
            vertical-align: top;
        }

        .hero-field-label {
            font-size: 6.5px;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: .6px;
            margin-bottom: 2px;
        }

        .hero-field-value {
            font-size: 9px;
            font-weight: bold;
            color: #1e3a5f;
        }

        /* ════════════════════════════════
   SECTION TITLE
════════════════════════════════ */
        .section-head {
            display: table;
            width: 100%;
            background: #1e3a5f;
            margin-bottom: 0;
        }

        .section-head-text {
            display: table-cell;
            padding: 5px 10px;
            font-size: 7px;
            font-weight: bold;
            color: #ffffff;
            text-transform: uppercase;
            letter-spacing: 1px;
            vertical-align: middle;
        }

        .section-head-line {
            display: table-cell;
            vertical-align: middle;
            text-align: right;
            padding-right: 10px;
            font-size: 6.5px;
            color: #7ab3d4;
            letter-spacing: .3px;
        }

        /* ════════════════════════════════
   TWO COLUMN LAYOUT
════════════════════════════════ */
        .two-col {
            display: table;
            width: 100%;
            margin-bottom: 12px;
        }

        .col-l {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            border-right: 1px solid #e2e8f0;
        }

        .col-r {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }

        .col-section {
            margin-bottom: 10px;
        }

        .col-section:last-child {
            margin-bottom: 0;
        }

        .col-l .col-section {
            padding-right: 10px;
        }

        .col-r .col-section {
            padding-left: 10px;
        }

        /* ════════════════════════════════
   INFO TABLE
════════════════════════════════ */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8.5px;
        }

        .info-table tr {
            border-bottom: 1px solid #f1f5f9;
        }

        .info-table tr:last-child {
            border-bottom: none;
        }

        .info-table td {
            padding: 5px 8px;
            vertical-align: top;
            line-height: 1.35;
        }

        .td-label {
            color: #64748b;
            width: 42%;
        }

        .td-sep {
            color: #cbd5e1;
            width: 5%;
            text-align: center;
        }

        .td-value {
            color: #0f172a;
            font-weight: 600;
            width: 53%;
        }

        /* ════════════════════════════════
   DOCUMENT TABLE
════════════════════════════════ */
        .doc-section {
            margin-bottom: 12px;
        }

        .doc-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8.5px;
        }

        .doc-table th {
            background: #1e3a5f;
            color: #fff;
            padding: 5px 8px;
            font-size: 7px;
            text-transform: uppercase;
            letter-spacing: .5px;
            text-align: left;
        }

        .doc-table td {
            padding: 5px 8px;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }

        .doc-table tbody tr:nth-child(even) td {
            background: #fafbfc;
        }

        .b-ok {
            color: #15803d;
            font-weight: bold;
            font-size: 7.5px;
        }

        .b-soon {
            color: #b45309;
            font-weight: bold;
            font-size: 7.5px;
        }

        .b-expired {
            color: #dc2626;
            font-weight: bold;
            font-size: 7.5px;
        }

        .b-none {
            color: #94a3b8;
            font-size: 7.5px;
        }

        /* ════════════════════════════════
   NOTES
════════════════════════════════ */
        .notes-section {
            margin-bottom: 10px;
        }

        .notes-body {
            padding: 8px 10px;
            font-size: 8.5px;
            color: #475569;
            line-height: 1.55;
            background: #fafbfc;
            border: 1px solid #e2e8f0;
            border-top: none;
            min-height: 26px;
        }

        /* ════════════════════════════════
   FOOTER / AUDIT
════════════════════════════════ */
        .doc-footer {
            display: table;
            width: 100%;
            border-top: 2px solid #e2e8f0;
            padding-top: 6px;
            margin-top: 8px;
        }

        .df-left {
            display: table-cell;
            width: 40%;
            font-size: 7px;
            color: #94a3b8;
            vertical-align: bottom;
        }

        .df-mid {
            display: table-cell;
            width: 20%;
            font-size: 7px;
            color: #94a3b8;
            vertical-align: bottom;
            text-align: center;
        }

        .df-right {
            display: table-cell;
            width: 40%;
            font-size: 7px;
            color: #94a3b8;
            vertical-align: bottom;
            text-align: right;
        }

        /* ════════════════════════════════
   GALLERY (PAGE 2)
════════════════════════════════ */
        .gal-header {
            background: #1e3a5f;
            color: #fff;
            padding: 6px 10px;
            font-size: 8px;
            font-weight: bold;
            letter-spacing: .5px;
            margin-bottom: 10px;
        }

        .gal-row {
            display: table;
            width: 100%;
            margin-bottom: 10px;
        }

        .gal-cell {
            display: table-cell;
            width: 33.33%;
            padding: 0 5px;
            vertical-align: top;
            text-align: center;
        }

        .gal-cell img {
            width: 100%;
            height: auto;
            max-height: 140px;
            object-fit: contain;
            border: 1px solid #e2e8f0;
            display: block;
            background: #f8fafc;
        }

        .gal-cat {
            font-size: 7px;
            color: #1e3a5f;
            font-weight: bold;
            margin-top: 3px;
            text-transform: uppercase;
            letter-spacing: .4px;
        }

        .gal-caption {
            font-size: 6.5px;
            color: #64748b;
            margin-top: 1px;
        }
    </style>
</head>

<body>

    <div class="page-pad">
        @php
            $primaryPhoto = $fleet->photos->firstWhere('is_primary', true) ?? $fleet->photos->first();
            $statusClass = match ($fleet->vehicle_status ?? '') {
                'active' => 'status-active',
                'inactive' => 'status-inactive',
                default => 'status-other',
            };
        @endphp

        {{-- ═══ HEADER ═══ --}}
        <div class="doc-header">
            <div class="doc-title">Fleet Particular</div>
            <div class="doc-subtitle">Dokumen Data Kendaraan</div>
        </div>

        {{-- ═══ HERO ═══ --}}
        <div class="hero">
            <div class="hero-photo-cell">
                @if ($primaryPhoto)
                    <img src="{{ public_path('storage/' . $primaryPhoto->file_path) }}" alt="Foto Utama">
                @else
                    <div class="hero-photo-none">🚛</div>
                @endif
            </div>
            <div class="hero-info-cell">
                {{-- Baris atas: judul + status --}}
                <div style="display:table;width:100%;margin-bottom:8px;">
                    <div style="display:table-cell;vertical-align:middle;">
                        <div class="hero-code">{{ $fleet->vehicle_code ?? '—' }}</div>
                        <div class="hero-police">{{ $fleet->police_number ?? '—' }}</div>
                        <div class="hero-brand">
                            {{ $fleet->brand?->name ?? '' }}
                            @if ($fleet->model?->name ?? $fleet->model_name)
                                &nbsp;·&nbsp; {{ $fleet->model?->name ?? $fleet->model_name }}
                            @endif
                            @if ($fleet->manufacture_year)
                                &nbsp;·&nbsp; {{ $fleet->manufacture_year }}
                            @endif
                        </div>
                    </div>
                </div>
                {{-- Tabel detail sama seperti section bawah --}}
                <table class="info-table" style="border-top:1px solid #dce6f0;">
                    <tr>
                        <td class="td-label">No. Lambung</td>
                        <td class="td-sep">:</td>
                        <td class="td-value">{{ $fleet->hull_number ?? '—' }}</td>
                        <td class="td-label" style="padding-left:16px;">Jenis Kendaraan</td>
                        <td class="td-sep">:</td>
                        <td class="td-value">{{ $fleet->type?->name ?? '—' }}</td>
                    </tr>
                    <tr>
                        <td class="td-label">Warna</td>
                        <td class="td-sep">:</td>
                        <td class="td-value">{{ $fleet->color ?? '—' }}</td>
                        <td class="td-label" style="padding-left:16px;">Status Kepemilikan</td>
                        <td class="td-sep">:</td>
                        <td class="td-value">{{ $fleet->ownership_label }}</td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- ═══ TWO-COLUMN: TEKNIS + FINANSIAL & OPERASIONAL ═══ --}}
        <div class="two-col">

            {{-- Kiri: Spesifikasi Teknis --}}
            <div class="col-l">
                <div class="section-head">
                    <div class="section-head-text">Spesifikasi Teknis</div>
                </div>
                <table class="info-table">
                    <tr>
                        <td class="td-label">No. Rangka</td>
                        <td class="td-sep">:</td>
                        <td class="td-value">{{ $fleet->chassis_number ?? '—' }}</td>
                    </tr>
                    <tr>
                        <td class="td-label">No. Mesin</td>
                        <td class="td-sep">:</td>
                        <td class="td-value">{{ $fleet->engine_number ?? '—' }}</td>
                    </tr>
                    <tr>
                        <td class="td-label">Bahan Bakar</td>
                        <td class="td-sep">:</td>
                        <td class="td-value">{{ $fleet->fuelType?->name ?? '—' }}</td>
                    </tr>
                    <tr>
                        <td class="td-label">Kapasitas Mesin</td>
                        <td class="td-sep">:</td>
                        <td class="td-value">
                            {{ $fleet->engine_capacity_cc ? number_format($fleet->engine_capacity_cc) . ' cc' : '—' }}
                        </td>
                    </tr>
                    <tr>
                        <td class="td-label">Konfigurasi Sumbu</td>
                        <td class="td-sep">:</td>
                        <td class="td-value">{{ $fleet->axisConfiguration?->name ?? '—' }}</td>
                    </tr>
                    <tr>
                        <td class="td-label">Jumlah Roda</td>
                        <td class="td-sep">:</td>
                        <td class="td-value">{{ $fleet->wheel_count ? $fleet->wheel_count . ' roda' : '—' }}</td>
                    </tr>
                    <tr>
                        <td class="td-label">Kapasitas Muatan</td>
                        <td class="td-sep">:</td>
                        <td class="td-value">{{ $fleet->load_capacity_ton ? $fleet->load_capacity_ton . ' ton' : '—' }}
                        </td>
                    </tr>
                    <tr>
                        <td class="td-label">GVW</td>
                        <td class="td-sep">:</td>
                        <td class="td-value">
                            {{ $fleet->gross_vehicle_weight_ton ? $fleet->gross_vehicle_weight_ton . ' ton' : '—' }}</td>
                    </tr>
                    <tr>
                        <td class="td-label">Berat Kosong</td>
                        <td class="td-sep">:</td>
                        <td class="td-value">{{ $fleet->tare_weight_ton ? $fleet->tare_weight_ton . ' ton' : '—' }}</td>
                    </tr>
                    <tr>
                        <td class="td-label">Jenis Body</td>
                        <td class="td-sep">:</td>
                        <td class="td-value">{{ $fleet->bodyType?->name ?? '—' }}</td>
                    </tr>
                    <tr>
                        <td class="td-label">Dimensi Body</td>
                        <td class="td-sep">:</td>
                        <td class="td-value">{{ $fleet->body_dimension ?? '—' }}</td>
                    </tr>
                </table>
            </div>

            {{-- Kanan: Kepemilikan + Operasional --}}
            <div class="col-r">
                <div class="col-section">
                    <div class="section-head">
                        <div class="section-head-text">Kepemilikan &amp; Finansial</div>
                    </div>
                    <table class="info-table">
                        <tr>
                            <td class="td-label">Status Kepemilikan</td>
                            <td class="td-sep">:</td>
                            <td class="td-value">{{ $fleet->ownership_label }}</td>
                        </tr>
                        <tr>
                            <td class="td-label">Tahun Perolehan</td>
                            <td class="td-sep">:</td>
                            <td class="td-value">{{ $fleet->acquisition_year ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td class="td-label">Nilai Perolehan</td>
                            <td class="td-sep">:</td>
                            <td class="td-value">
                                {{ $fleet->acquisition_value ? 'Rp ' . number_format($fleet->acquisition_value, 0, ',', '.') : '—' }}
                            </td>
                        </tr>
                        <tr>
                            <td class="td-label">Perusahaan Leasing</td>
                            <td class="td-sep">:</td>
                            <td class="td-value">{{ $fleet->leasing_company ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td class="td-label">Akhir Leasing</td>
                            <td class="td-sep">:</td>
                            <td class="td-value">{{ $fleet->leasing_end_date?->format('d M Y') ?? '—' }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-section" style="margin-top:10px;">
                    <div class="section-head">
                        <div class="section-head-text">Data Operasional</div>
                    </div>
                    <table class="info-table">
                        <tr>
                            <td class="td-label">Odometer Terakhir</td>
                            <td class="td-sep">:</td>
                            <td class="td-value">
                                {{ $fleet->last_odometer_km ? number_format($fleet->last_odometer_km) . ' km' : '—' }}
                            </td>
                        </tr>
                        <tr>
                            <td class="td-label">Servis Terakhir</td>
                            <td class="td-sep">:</td>
                            <td class="td-value">{{ $fleet->last_service_date?->format('d M Y') ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td class="td-label">Interval Servis</td>
                            <td class="td-sep">:</td>
                            <td class="td-value">
                                {{ $fleet->service_interval_km ? number_format($fleet->service_interval_km) . ' km' : '—' }}
                            </td>
                        </tr>
                        <tr>
                            <td class="td-label">GPS Tracker ID</td>
                            <td class="td-sep">:</td>
                            <td class="td-value">{{ $fleet->gps_tracker_id ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td class="td-label">Provider GPS</td>
                            <td class="td-sep">:</td>
                            <td class="td-value">{{ $fleet->gps_provider ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td class="td-label">Status GPS</td>
                            <td class="td-sep">:</td>
                            <td class="td-value">
                                {{ match ($fleet->gps_status ?? '') {'active' => 'Aktif','offline' => 'Offline','not_installed' => 'Tidak Terpasang',default => '—'} }}
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

        </div>{{-- /two-col --}}

        {{-- ═══ DOKUMEN & LEGALITAS ═══ --}}
        <div class="doc-section">
            <div class="section-head">
                <div class="section-head-text">Dokumen &amp; Legalitas</div>
            </div>
            @php
                $docFiles = $fleet->documents->keyBy('document_type');
                $docs = [
                    [
                        'label' => 'STNK',
                        'type' => 'stnk',
                        'number' => $fleet->stnk_number,
                        'expiry' => $fleet->tax_expiry_date,
                    ],
                    [
                        'label' => 'KIR',
                        'type' => 'kir',
                        'number' => $fleet->kir_number,
                        'expiry' => $fleet->kir_expiry_date,
                    ],
                    ['label' => 'BPKB', 'type' => 'bpkb', 'number' => $fleet->bpkb_number, 'expiry' => null],
                    [
                        'label' => 'Asuransi',
                        'type' => 'asuransi',
                        'number' => $fleet->insurance_policy_number,
                        'expiry' => $fleet->insurance_expiry_date,
                    ],
                ];
            @endphp
            <table class="doc-table">
                <thead>
                    <tr>
                        <th style="width:5%">No</th>
                        <th style="width:20%">Dokumen</th>
                        <th style="width:20%">Nomor</th>
                        <th style="width:20%">Berlaku Hingga</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($docs as $doc)
                        @php
                            $rec = $docFiles->get($doc['type']);
                            $hasFile = $rec && $rec->file_name;
                        @endphp
                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <td style="font-weight:bold">{{ $doc['label'] }}</td>
                            <td style="font-family:monospace;font-size:8px">{{ $doc['number'] ?? '—' }}</td>
                            <td>{{ $doc['expiry'] ? $doc['expiry']->format('d M Y') : '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- ═══ CATATAN ═══ --}}
        <div class="notes-section">
            <div class="section-head">
                <div class="section-head-text">Catatan</div>
            </div>
            <div class="notes-body">{{ $fleet->notes ?: 'Tidak ada catatan.' }}</div>
        </div>


        {{-- ═══ HALAMAN 2 — LAMPIRAN FOTO ═══ --}}
        @php
            $otherPhotos = $fleet->photos->filter(fn($p) => !($primaryPhoto && $p->id === $primaryPhoto->id));
        @endphp
    </div>{{-- /page-pad --}}
    @if ($otherPhotos->count())
        <div class="page-break"></div>
        <div class="page-pad">
            <div class="doc-header">
                <div class="doc-title">Lampiran</div>
                <div class="doc-subtitle">{{ $fleet->vehicle_code ?? $fleet->police_number }} &nbsp;·&nbsp; Halaman 2</div>
            </div>

            <div class="gal-header">Dokumentasi Foto Kendaraan — {{ $fleet->vehicle_code ?? $fleet->police_number }} —
                {{ $fleet->photos->count() }} foto</div>

            @php
                $catLabels = \App\Models\MasterData\Vehicle\VehiclePhoto::CATEGORIES;
                // Foto utama dimasukkan pertama, lalu foto lainnya
                $allPhotos = collect();
                if ($primaryPhoto) {
                    $allPhotos->push($primaryPhoto);
                }
                foreach ($otherPhotos as $p) {
                    $allPhotos->push($p);
                }
                $chunks = $allPhotos->values()->chunk(3);
            @endphp
            @foreach ($chunks as $row)
                <div class="gal-row">
                    @foreach ($row as $photo)
                        @php
                            $isPrimary = $primaryPhoto && $photo->id === $primaryPhoto->id;
                            $catLabel = $isPrimary
                                ? 'Foto Utama'
                                : $catLabels[$photo->category] ?? ucfirst($photo->category ?? 'Lainnya');
                        @endphp
                        <div class="gal-cell">
                            <img src="{{ public_path('storage/' . $photo->file_path) }}" alt="{{ $catLabel }}"
                                style="{{ $isPrimary ? 'border:2px solid #1e3a5f;' : '' }}">
                            <div class="gal-cat" style="{{ $isPrimary ? 'color:#1e3a5f;font-weight:bold;' : '' }}">
                                {{ $catLabel }}{{ $isPrimary ? ' ★' : '' }}
                            </div>
                            @if ($photo->caption)
                                <div class="gal-caption">{{ $photo->caption }}</div>
                            @endif
                        </div>
                    @endforeach
                    @for ($i = $row->count(); $i < 3; $i++)
                        <div class="gal-cell"></div>
                    @endfor
                </div>
            @endforeach

        </div>{{-- /page-pad p2 --}}
    @endif

</body>

</html>
