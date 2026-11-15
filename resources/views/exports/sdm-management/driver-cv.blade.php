<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Profil Pengemudi – {{ $driver->driver_code }}</title>
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
            background: #1e3a5f;
            padding: 16px 0px;
            margin-bottom: 14px;
            text-align: center;
        }

        .doc-title {
            font-size: 18px;
            font-weight: bold;
            color: #ffffff;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .doc-subtitle {
            font-size: 8px;
            color: #7ab3d4;
            text-transform: uppercase;
            letter-spacing: .3px;
            margin-top: 2px;
        }

        /* ════════════════════════════════
           HERO / IDENTITY
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
            background: #fff;
            overflow: hidden;
        }

        .hero-photo-cell img {
            width: 170px;
            height: 200px;
            object-fit: cover;
            display: block;
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

        .hero-name {
            font-size: 14px;
            color: #1e3a5f;
            font-weight: bold;
            margin-top: 2px;
        }

        .hero-sub {
            font-size: 9px;
            color: #64748b;
            margin-top: 3px;
        }

        .hero-badge {
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

        .status-resigned {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
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

        /* ════════════════════════════════
           TWO COLUMN
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
           DOCUMENT / CERT TABLE
        ════════════════════════════════ */
        .doc-section {
            margin-bottom: 12px;
            padding: 0 38px;
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
            padding: 0 38px;
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
           FOOTER
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
           LAMPIRAN — GALLERY
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

        .gal-section {
            margin-bottom: 14px;
        }

        .gal-section-title {
            font-size: 7.5px;
            font-weight: bold;
            color: #1e3a5f;
            text-transform: uppercase;
            letter-spacing: .6px;
            border-bottom: 1px solid #dce6f0;
            padding-bottom: 3px;
            margin-bottom: 8px;
        }

        /* Grid 3-kolom untuk lampiran file gambar */
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

        /* Single wide image (KTP) */
        .ktp-wrap {
            width: 100%;
            text-align: center;
            margin-bottom: 6px;
        }

        .ktp-wrap img {
            max-width: 420px;
            width: 100%;
            height: auto;
            max-height: 220px;
            object-fit: contain;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            display: inline-block;
        }

        .ktp-label {
            font-size: 7px;
            color: #1e3a5f;
            font-weight: bold;
            margin-top: 3px;
            text-transform: uppercase;
            letter-spacing: .4px;
        }

        .no-file-tag {
            display: inline-block;
            padding: 3px 10px;
            background: #f1f5f9;
            color: #94a3b8;
            font-size: 7.5px;
            border: 1px dashed #cbd5e1;
            border-radius: 3px;
        }
    </style>
</head>

<body>
    <div class="page-pad">

        @php
            $statusClass = match ($driver->status ?? '') {
                'active' => 'status-active',
                'inactive' => 'status-inactive',
                'resigned' => 'status-resigned',
                default => 'status-inactive',
            };
            $statusLabel = match ($driver->status ?? '') {
                'active' => 'Aktif',
                'inactive' => 'Tidak Aktif',
                'resigned' => 'Keluar',
                default => '-',
            };
            $maritalLabels = [
                'single' => 'Belum Menikah',
                'married' => 'Menikah',
                'divorced' => 'Cerai',
                'widowed' => 'Duda / Janda',
            ];
            $maritalLabel = $maritalLabels[$driver->marital_status ?? ''] ?? ($driver->marital_status ?? '—');
            $age = $driver->birth_date ? $driver->birth_date->age : null;

            // Cek ekstensi file — hanya render image (jpg/png/gif/webp)
            $imageExts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            $isImage = fn($path) => $path && in_array(strtolower(pathinfo($path, PATHINFO_EXTENSION)), $imageExts);

            // Kumpulkan semua lampiran yang perlu ditampilkan di halaman 2
            $docsWithFile = $driver->documents->filter(fn($d) => $d->file_path);
            $certsWithFile = $driver->certificates->filter(fn($c) => $c->file_path);
            $hasAnyFile = $driver->ktp_file_path || $docsWithFile->count() || $certsWithFile->count();
        @endphp

        {{-- ═══ HEADER ═══ --}}
        <div class="doc-header">
            <div class="doc-title">Profil Pengemudi</div>
            <div class="doc-subtitle">Dokumen Data Pengemudi</div>
        </div>

        {{-- ═══ HERO ═══ --}}
        <div class="hero">
            <div class="hero-photo-cell">
                @if ($driver->photo_path)
                    <img src="{{ public_path('storage/' . $driver->photo_path) }}" alt="Foto Pengemudi">
                @else
                    <div class="hero-photo-none">👤</div>
                @endif
            </div>
            <div class="hero-info-cell">
                <div style="display:table;width:100%;margin-bottom:4px;">
                    <div style="display:table-cell;vertical-align:middle;">
                        <div class="hero-code">{{ $driver->driver_code ?? '—' }}</div>
                        <div class="hero-name">{{ $driver->full_name ?? '—' }}</div>
                    </div>
                </div>
                <table class="info-table" style="border-top:1px solid #dce6f0; margin-top:6px;">
                    <tr>
                        <td class="td-label">NIK</td>
                        <td class="td-sep">:</td>
                        <td class="td-value">
                            {{ $driver->nik ?? '—' }}
                        </td>
                        <td class="td-label" style="padding-left:16px;">Status Perkawinan</td>
                        <td class="td-sep">:</td>
                        <td class="td-value">{{ $maritalLabel }}</td>
                    </tr>
                    <tr>
                        <td class="td-label">Tempat Lahir</td>
                        <td class="td-sep">:</td>
                        <td class="td-value">
                            {{ $driver->birth_place ?? '—' }}
                        </td>
                        <td class="td-label" style="padding-left:16px;">Tanggal Lahir</td>
                        <td class="td-sep">:</td>
                        <td class="td-value">
                            {{ $driver->birth_date ? ', ' . $driver->birth_date->translatedFormat('d F Y') : '' }}</td>
                    </tr>
                    <tr>
                        <td class="td-label">No. Telepon</td>
                        <td class="td-sep">:</td>
                        <td class="td-value">
                            {{ $driver->phone ?? '—' }}
                        </td>
                        <td class="td-label" style="padding-left:16px;">Email</td>
                        <td class="td-sep">:</td>
                        <td class="td-value">
                            {{ $driver->email ?? '—' }}
                        </td>
                    </tr>
                    <tr>
                        <td class="td-label">Tgl Bergabung</td>
                        <td class="td-sep">:</td>
                        <td class="td-value">{{ $driver->join_date?->translatedFormat('d F Y') ?? '—' }}</td>
                        <td class="td-label" style="padding-left:16px;">Jumlah Tanggungan</td>
                        <td class="td-sep">:</td>
                        <td class="td-value">
                            {{ $driver->children_count !== null ? $driver->children_count . ' orang' : '—' }}</td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- ═══ TWO COLUMN: IDENTITAS KTP + KONTAK & DOMISILI ═══ --}}
        <div class="two-col">

            <div class="col-l">
                <div class="col-section">
                    <div class="section-head">
                        <div class="section-head-text">Alamat KTP</div>
                    </div>
                    <table class="info-table">
                        <tr>
                            <td class="td-label">Alamat KTP</td>
                            <td class="td-sep">:</td>
                            <td class="td-value">{{ $driver->ktp_address ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td class="td-label">Kelurahan</td>
                            <td class="td-sep">:</td>
                            <td class="td-value">{{ $driver->ktp_village ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td class="td-label">Kecamatan</td>
                            <td class="td-sep">:</td>
                            <td class="td-value">{{ $driver->ktp_district ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td class="td-label">Kota / Kabupaten</td>
                            <td class="td-sep">:</td>
                            <td class="td-value">{{ $driver->ktp_city ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td class="td-label">Provinsi</td>
                            <td class="td-sep">:</td>
                            <td class="td-value">{{ $driver->ktp_province ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td class="td-label">Kode Pos</td>
                            <td class="td-sep">:</td>
                            <td class="td-value">{{ $driver->ktp_postal_code ?? '—' }}</td>
                        </tr>
                    </table>
                </div>
            </div>


            <div class="col-section">
                <div class="section-head">
                    <div class="section-head-text">Alamat Domisili</div>
                </div>
                @if ($driver->domicile_same_as_ktp)
                    <table class="info-table">
                        <tr>
                            <td class="td-label" colspan="3" style="color:#64748b;font-style:italic;">
                                Sama dengan alamat KTP
                            </td>
                        </tr>
                    </table>
                @else
                    <table class="info-table">
                        <tr>
                            <td class="td-label">Alamat</td>
                            <td class="td-sep">:</td>
                            <td class="td-value">{{ $driver->domicile_address ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td class="td-label">Kelurahan</td>
                            <td class="td-sep">:</td>
                            <td class="td-value">{{ $driver->domicile_village ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td class="td-label">Kecamatan</td>
                            <td class="td-sep">:</td>
                            <td class="td-value">{{ $driver->domicile_district ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td class="td-label">Kota / Kabupaten</td>
                            <td class="td-sep">:</td>
                            <td class="td-value">{{ $driver->domicile_city ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td class="td-label">Provinsi</td>
                            <td class="td-sep">:</td>
                            <td class="td-value">{{ $driver->domicile_province ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td class="td-label">Kode Pos</td>
                            <td class="td-sep">:</td>
                            <td class="td-value">{{ $driver->domicile_postal_code ?? '—' }}</td>
                        </tr>
                    </table>
                @endif
            </div>
        </div>

    </div>{{-- /two-col --}}

    {{-- ═══ DOKUMEN ═══ --}}
    <div class="doc-section">
        <div class="section-head">
            <div class="section-head-text">Dokumen</div>
        </div>
        @if ($driver->documents->count())
            <table class="doc-table">
                <thead>
                    <tr>
                        <th style="width:4%">No</th>
                        <th style="width:22%">Nama Dokumen</th>
                        <th style="width:20%">Nomor</th>
                        <th style="width:15%">Tgl Terbit</th>
                        <th style="width:15%">Berlaku Hingga</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($driver->documents as $doc)
                        @php
                            $isExpired = $doc->expiry_date && $doc->expiry_date->isPast();
                            $isSoon = !$isExpired && $doc->expiry_date && $doc->expiry_date->diffInDays(now()) <= 30;
                            $expiryClass = $isExpired ? 'b-expired' : ($isSoon ? 'b-soon' : 'b-ok');
                            $expiryTag = $isExpired ? ' ✗' : ($isSoon ? ' !' : '');
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td style="font-weight:bold">{{ $doc->document_name }}</td>
                            <td style="font-family:monospace;font-size:8px">{{ $doc->document_number ?? '—' }}
                            </td>
                            <td>{{ $doc->issued_date?->format('d M Y') ?? '—' }}</td>
                            <td class="{{ $doc->expiry_date ? $expiryClass : 'b-none' }}">
                                {{ $doc->expiry_date?->format('d M Y') ?? '—' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="notes-body">Tidak ada dokumen tercatat.</div>
        @endif
    </div>

    {{-- ═══ SERTIFIKAT / PELATIHAN ═══ --}}
    <div class="doc-section">
        <div class="section-head">
            <div class="section-head-text">Sertifikat &amp; Pelatihan</div>
        </div>
        @if ($driver->certificates->count())
            <table class="doc-table">
                <thead>
                    <tr>
                        <th style="width:4%">No</th>
                        <th style="width:22%">Nama Sertifikat</th>
                        <th style="width:16%">Nomor</th>
                        <th style="width:16%">Penerbit</th>
                        <th style="width:13%">Tgl Terbit</th>
                        <th style="width:13%">Berlaku Hingga</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($driver->certificates as $cert)
                        @php
                            $isExpired = $cert->expiry_date && $cert->expiry_date->isPast();
                            $isSoon = !$isExpired && $cert->expiry_date && $cert->expiry_date->diffInDays(now()) <= 30;
                            $expiryClass = $isExpired ? 'b-expired' : ($isSoon ? 'b-soon' : 'b-ok');
                            $expiryTag = $isExpired ? ' ✗' : ($isSoon ? ' !' : '');
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td style="font-weight:bold">{{ $cert->certificate_name }}</td>
                            <td style="font-family:monospace;font-size:8px">{{ $cert->certificate_number ?? '—' }}
                            </td>
                            <td>{{ $cert->issuer ?? '—' }}</td>
                            <td>{{ $cert->issued_date?->format('d M Y') ?? '—' }}</td>
                            <td class="{{ $cert->expiry_date ? $expiryClass : 'b-none' }}">
                                {{ $cert->expiry_date?->format('d M Y') ?? '—' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="notes-body">Tidak ada sertifikat tercatat.</div>
        @endif
    </div>

    {{-- ═══ CATATAN ═══ --}}
    <div class="notes-section">
        <div class="section-head">
            <div class="section-head-text">Catatan</div>
        </div>
        <div class="notes-body">{{ $driver->notes ?: 'Tidak ada catatan.' }}</div>
    </div>


    </div>{{-- /page-pad --}}

    {{-- ════════════════════════════════════════════════════════════════
     HALAMAN 2 — LAMPIRAN FILE
════════════════════════════════════════════════════════════════ --}}
    @if ($hasAnyFile)
        <div class="page-break"></div>
        <div class="page-pad">

            {{-- Header halaman lampiran --}}
            <div class="doc-header">
                <div class="doc-title">Lampiran</div>
                <div class="doc-subtitle">{{ $driver->driver_code }} &nbsp;·&nbsp; {{ $driver->full_name }}
                    &nbsp;·&nbsp; Halaman 2</div>
            </div>

            <div class="gal-header">Lampiran Dokumen Pengemudi — {{ $driver->full_name }}</div>

            {{-- ── KTP ── --}}
            @if ($driver->ktp_file_path)
                <div class="gal-section">
                    <div class="gal-section-title">Kartu Tanda Penduduk (KTP)</div>
                    @if ($isImage($driver->ktp_file_path))
                        <div class="ktp-wrap">
                            <img src="{{ public_path('storage/' . $driver->ktp_file_path) }}" alt="KTP">
                            <div class="ktp-label">KTP — {{ $driver->full_name }} &nbsp;·&nbsp; NIK:
                                {{ $driver->nik ?? '—' }}</div>
                        </div>
                    @else
                        <div style="padding:8px 0;">
                            <span class="no-file-tag">File terlampir (bukan gambar — lihat file asli)</span>
                        </div>
                    @endif
                </div>
            @endif

            {{-- ── Dokumen ── --}}
            @if ($docsWithFile->count())
                <div class="gal-section">
                    <div class="gal-section-title">File Dokumen</div>
                    @php
                        $docChunks = $docsWithFile->values()->chunk(3);
                    @endphp
                    @foreach ($docChunks as $row)
                        <div class="gal-row">
                            @foreach ($row as $doc)
                                <div class="gal-cell">
                                    @if ($isImage($doc->file_path))
                                        <img src="{{ public_path('storage/' . $doc->file_path) }}"
                                            alt="{{ $doc->document_name }}">
                                    @else
                                        <div
                                            style="width:100%;height:100px;background:#f1f5f9;border:1px solid #e2e8f0;display:flex;align-items:center;justify-content:center;">
                                            <span style="font-size:7px;color:#94a3b8;">File terlampir</span>
                                        </div>
                                    @endif
                                    <div class="gal-cat">{{ $doc->document_name }}</div>
                                    @if ($doc->document_number)
                                        <div class="gal-caption">No: {{ $doc->document_number }}</div>
                                    @endif
                                    @if ($doc->expiry_date)
                                        <div class="gal-caption">Berlaku: {{ $doc->expiry_date->format('d M Y') }}
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                            @for ($i = $row->count(); $i < 3; $i++)
                                <div class="gal-cell"></div>
                            @endfor
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- ── Sertifikat ── --}}
            @if ($certsWithFile->count())
                <div class="gal-section">
                    <div class="gal-section-title">File Sertifikat &amp; Pelatihan</div>
                    @php
                        $certChunks = $certsWithFile->values()->chunk(3);
                    @endphp
                    @foreach ($certChunks as $row)
                        <div class="gal-row">
                            @foreach ($row as $cert)
                                <div class="gal-cell">
                                    @if ($isImage($cert->file_path))
                                        <img src="{{ public_path('storage/' . $cert->file_path) }}"
                                            alt="{{ $cert->certificate_name }}">
                                    @else
                                        <div
                                            style="width:100%;height:100px;background:#f1f5f9;border:1px solid #e2e8f0;display:flex;align-items:center;justify-content:center;">
                                            <span style="font-size:7px;color:#94a3b8;">File terlampir</span>
                                        </div>
                                    @endif
                                    <div class="gal-cat">{{ $cert->certificate_name }}</div>
                                    @if ($cert->issuer)
                                        <div class="gal-caption">{{ $cert->issuer }}</div>
                                    @endif
                                    @if ($cert->expiry_date)
                                        <div class="gal-caption">Berlaku: {{ $cert->expiry_date->format('d M Y') }}
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                            @for ($i = $row->count(); $i < 3; $i++)
                                <div class="gal-cell"></div>
                            @endfor
                        </div>
                    @endforeach
                </div>
            @endif

        </div>{{-- /page-pad p2 --}}
    @endif

</body>

</html>
