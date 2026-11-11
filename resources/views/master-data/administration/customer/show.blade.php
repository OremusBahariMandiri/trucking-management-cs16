@extends('layouts.admin')
@section('title', 'Detail Pelanggan — ' . $customer->name)

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        /* ── Hero card ─────────────────────────────────────── */
        .cust-hero {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: .75rem;
            padding: 1.5rem;
            box-shadow: 0 1px 4px rgba(0,0,0,.06);
            margin-bottom: 1rem;
        }
        .cust-hero-name {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--text);
            line-height: 1.25;
        }
        .cust-hero-sub {
            font-size: .83rem;
            color: var(--text-muted);
            margin-top: .2rem;
        }
        .cust-hero-divider {
            border-color: var(--border);
            margin: 1rem 0;
        }

        /* ── Tab nav ───────────────────────────────────────── */
        .cust-tab-nav {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: .75rem .75rem 0 0;
            padding: .5rem .75rem 0;
            box-shadow: 0 1px 4px rgba(0,0,0,.06);
            overflow-x: auto;
            scrollbar-width: none;
        }
        .cust-tab-nav::-webkit-scrollbar { display: none; }
        .cust-tab-nav .nav-tabs {
            border-bottom: none;
            flex-wrap: nowrap;
            gap: .25rem;
            min-width: max-content;
        }
        .cust-tab-nav .nav-link {
            border: none;
            border-radius: .5rem .5rem 0 0;
            color: var(--text-muted);
            font-size: .82rem;
            font-weight: 500;
            padding: .6rem .9rem;
            white-space: nowrap;
            display: flex;
            align-items: center;
            gap: .4rem;
            transition: color .18s, background .18s;
            position: relative;
        }
        .cust-tab-nav .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0; left: .9rem; right: .9rem;
            height: 2px;
            border-radius: 2px 2px 0 0;
            background: #3b82f6;
            transform: scaleX(0);
            transition: transform .18s;
        }
        .cust-tab-nav .nav-link:hover {
            color: var(--text);
            background: var(--surface-2);
        }
        .cust-tab-nav .nav-link.active {
            color: #3b82f6;
            background: transparent;
            font-weight: 600;
        }
        .cust-tab-nav .nav-link.active::after { transform: scaleX(1); }

        /* ── Tab pane card ─────────────────────────────────── */
        .cust-tab-pane {
            background: var(--surface);
            border: 1px solid var(--border);
            border-top: none;
            border-radius: 0 0 .75rem .75rem;
            padding: 1.5rem;
            box-shadow: 0 1px 4px rgba(0,0,0,.06);
        }
        @media (max-width: 576px) { .cust-tab-pane { padding: 1rem; } }

        /* ── Info label / value ────────────────────────────── */
        .cust-label {
            font-size: .7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .06em;
            color: var(--text-muted);
            margin-bottom: .2rem;
        }
        .cust-value {
            font-size: .9rem;
            font-weight: 500;
            color: var(--text);
            word-break: break-word;
        }
        .cust-value-empty { color: var(--text-faint); font-style: italic; }

        /* ── PIC card ──────────────────────────────────────── */
        .pic-card {
            background: var(--surface-2);
            border: 1px solid var(--border);
            border-radius: .6rem;
            padding: 1rem 1.25rem;
        }
        .pic-card + .pic-card { margin-top: .75rem; }
        .pic-card-header {
            display: flex;
            align-items: center;
            gap: .5rem;
            margin-bottom: .75rem;
        }
        .pic-avatar {
            width: 36px; height: 36px;
            border-radius: 50%;
            background: #3b82f620;
            color: #3b82f6;
            display: flex; align-items: center; justify-content: center;
            font-size: .9rem;
            flex-shrink: 0;
        }
        .pic-name { font-weight: 600; color: var(--text); font-size: .92rem; }

        /* ── Meta footer ───────────────────────────────────── */
        .cust-meta {
            font-size: .78rem;
            color: var(--text-muted);
            border-top: 1px solid var(--border);
            margin-top: 1rem;
            padding-top: 1rem;
        }

        /* ── Empty state ───────────────────────────────────── */
        .cust-empty {
            text-align: center;
            padding: 2.5rem 1rem;
            color: var(--text-faint);
        }
        .cust-empty i { font-size: 2rem; margin-bottom: .5rem; display: block; }
    </style>
@endpush

@section('content')

{{-- ── Page Header ──────────────────────────────────────────── --}}
<div class="veh-page-header mb-3">
    <div>
        <div class="veh-title">Detail Pelanggan</div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
                <li class="breadcrumb-item">
                    <a href="{{ route('master-data.administration.customers.index') }}">Pelanggan</a>
                </li>
                <li class="breadcrumb-item active">{{ $customer->name }}</li>
            </ol>
        </nav>
    </div>
</div>

@include('components.alert')

{{-- ── Hero ─────────────────────────────────────────────────── --}}
<div class="cust-hero">
    <div class="d-flex align-items-start justify-content-between gap-3 flex-wrap">
        <div>
            <div class="cust-hero-name">{{ $customer->name }}</div>
            <div class="cust-hero-sub">
                {{ $customer->customer_code }}
                @if($customer->customerType)
                    &nbsp;·&nbsp;{{ $customer->customerType->name }}
                @endif
                @if($customer->businessType)
                    &nbsp;·&nbsp;{{ $customer->businessType->name }}
                @endif
            </div>
        </div>
        <span class="badge {{ $customer->is_active ? 'bg-success' : 'bg-secondary' }} fs-6 fw-normal px-3 py-2">
            {{ $customer->is_active ? 'Aktif' : 'Tidak Aktif' }}
        </span>
    </div>

    <hr class="cust-hero-divider">

    <div class="row g-3">
        <div class="col-6 col-md-3">
            <div class="cust-label">Tgl. Registrasi</div>
            <div class="cust-value">{{ $customer->registration_date?->format('d M Y') ?: '—' }}</div>
        </div>
        <div class="col-6 col-md-3">
            <div class="cust-label">Telepon</div>
            <div class="cust-value">
                @if($customer->phone)
                    <a href="tel:{{ $customer->phone }}" class="text-decoration-none" style="color:var(--text)">
                        {{ $customer->phone }}
                    </a>
                @else —
                @endif
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="cust-label">Email</div>
            <div class="cust-value">
                @if($customer->email)
                    <a href="mailto:{{ $customer->email }}" class="text-decoration-none" style="color:var(--text)">
                        {{ $customer->email }}
                    </a>
                @else —
                @endif
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="cust-label">PIC Utama</div>
            <div class="cust-value">
                @php $primaryPic = $customer->pics->firstWhere('is_primary', true) ?? $customer->pics->first(); @endphp
                {{ $primaryPic?->name ?: '—' }}
            </div>
        </div>
    </div>
</div>

{{-- ── Tabs ─────────────────────────────────────────────────── --}}
<div class="cust-tab-nav">
    <ul class="nav nav-tabs" id="custTabs" role="tablist">
        @php
            $tabs = [
                ['id' => 'tab-info',      'icon' => 'fas fa-building',       'label' => 'Informasi'],
                ['id' => 'tab-legalitas', 'icon' => 'fas fa-file-invoice',   'label' => 'Legalitas'],
                ['id' => 'tab-alamat',    'icon' => 'fas fa-map-marker-alt', 'label' => 'Alamat'],
                ['id' => 'tab-pic',       'icon' => 'fas fa-user-tie',       'label' => 'PIC (' . $customer->pics->count() . ')'],
            ];
        @endphp
        @foreach($tabs as $i => $tab)
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $i === 0 ? 'active' : '' }}"
                    data-bs-toggle="tab"
                    data-bs-target="#{{ $tab['id'] }}"
                    type="button">
                    <i class="{{ $tab['icon'] }}"></i>
                    <span class="d-none d-sm-inline">{{ $tab['label'] }}</span>
                </button>
            </li>
        @endforeach
    </ul>
</div>

<div class="tab-content">

    {{-- ── Tab: Informasi Umum ───────────────────────────── --}}
    <div class="tab-pane fade show active cust-tab-pane" id="tab-info">
        <div class="row g-3">
            <div class="col-6 col-md-3">
                <div class="cust-label">Kode Pelanggan</div>
                <div class="cust-value" style="font-family:monospace">{{ $customer->customer_code ?: '—' }}</div>
            </div>
            <div class="col-6 col-md-3">
                <div class="cust-label">Tanggal Registrasi</div>
                <div class="cust-value">{{ $customer->registration_date?->format('d M Y') ?: '—' }}</div>
            </div>
            <div class="col-6 col-md-3">
                <div class="cust-label">Jenis Pelanggan</div>
                <div class="cust-value">{{ $customer->customerType?->name ?: '—' }}</div>
            </div>
            <div class="col-6 col-md-3">
                <div class="cust-label">Jenis Usaha</div>
                <div class="cust-value">{{ $customer->businessType?->name ?: '—' }}</div>
            </div>
            <div class="col-12">
                <div class="cust-label">Nama Pelanggan</div>
                <div class="cust-value fw-semibold fs-6">{{ $customer->name }}</div>
            </div>
            <div class="col-6 col-md-4">
                <div class="cust-label">Telepon</div>
                <div class="cust-value">
                    @if($customer->phone)
                        <a href="tel:{{ $customer->phone }}" class="text-decoration-none" style="color:var(--text)">
                            <i class="fas fa-phone fa-xs me-1 text-muted"></i>{{ $customer->phone }}
                        </a>
                    @else —
                    @endif
                </div>
            </div>
            <div class="col-6 col-md-4">
                <div class="cust-label">Email</div>
                <div class="cust-value">
                    @if($customer->email)
                        <a href="mailto:{{ $customer->email }}" class="text-decoration-none" style="color:var(--text)">
                            <i class="fas fa-envelope fa-xs me-1 text-muted"></i>{{ $customer->email }}
                        </a>
                    @else —
                    @endif
                </div>
            </div>
            <div class="col-6 col-md-4">
                <div class="cust-label">Status</div>
                <div class="cust-value">
                    <span class="badge {{ $customer->is_active ? 'bg-success' : 'bg-secondary' }}">
                        {{ $customer->is_active ? 'Aktif' : 'Tidak Aktif' }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Meta --}}
        <div class="cust-meta row g-2">
            <div class="col-md-4">
                <div class="cust-label">Dibuat oleh</div>
                <div>{{ $customer->createdBy?->name ?? '—' }}</div>
            </div>
            <div class="col-md-4">
                <div class="cust-label">Dibuat pada</div>
                <div>{{ $customer->created_at?->format('d M Y H:i') ?? '—' }}</div>
            </div>
            <div class="col-md-4">
                <div class="cust-label">Terakhir diubah</div>
                <div>{{ $customer->updated_at?->format('d M Y H:i') ?? '—' }}</div>
            </div>
        </div>
    </div>

    {{-- ── Tab: Legalitas ────────────────────────────────── --}}
    <div class="tab-pane fade cust-tab-pane" id="tab-legalitas">
        <div class="row g-3">
            <div class="col-md-6">
                <div class="cust-label">Nomor NPWP</div>
                <div class="cust-value" style="font-family:monospace">
                    {{ $customer->npwp_number ?: '—' }}
                </div>
            </div>
            <div class="col-md-6">
                <div class="cust-label">File NPWP</div>
                <div class="cust-value mt-1">
                    @if($customer->npwp_file_name)
                        <a href="{{ $customer->npwp_file_url }}" target="_blank" rel="noopener"
                            class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-file me-1"></i>{{ $customer->npwp_file_name }}
                            <i class="fas fa-external-link-alt ms-1"></i>
                        </a>
                    @else
                        <span class="cust-value-empty">Belum diunggah</span>
                    @endif
                </div>
            </div>
            <div class="col-md-6">
                <div class="cust-label">Nomor NIB</div>
                <div class="cust-value" style="font-family:monospace">
                    {{ $customer->nib_number ?: '—' }}
                </div>
            </div>
            <div class="col-md-6">
                <div class="cust-label">File NIB</div>
                <div class="cust-value mt-1">
                    @if($customer->nib_file_name)
                        <a href="{{ $customer->nib_file_url }}" target="_blank" rel="noopener"
                            class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-file me-1"></i>{{ $customer->nib_file_name }}
                            <i class="fas fa-external-link-alt ms-1"></i>
                        </a>
                    @else
                        <span class="cust-value-empty">Belum diunggah</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ── Tab: Alamat ───────────────────────────────────── --}}
    <div class="tab-pane fade cust-tab-pane" id="tab-alamat">
        <div class="row g-3">
            <div class="col-6 col-md-4">
                <div class="cust-label">Provinsi</div>
                <div class="cust-value">{{ $customer->province ?: '—' }}</div>
            </div>
            <div class="col-6 col-md-4">
                <div class="cust-label">Kota</div>
                <div class="cust-value">{{ $customer->city ?: '—' }}</div>
            </div>
            <div class="col-6 col-md-4">
                <div class="cust-label">Kode Pos</div>
                <div class="cust-value">{{ $customer->postal_code ?: '—' }}</div>
            </div>
            <div class="col-6 col-md-4">
                <div class="cust-label">Kecamatan</div>
                <div class="cust-value">{{ $customer->kecamatan ?: '—' }}</div>
            </div>
            <div class="col-6 col-md-4">
                <div class="cust-label">Kelurahan</div>
                <div class="cust-value">{{ $customer->kelurahan ?: '—' }}</div>
            </div>
            <div class="col-6 col-md-4">
                <div class="cust-label">RT/RW</div>
                <div class="cust-value">{{ $customer->rt_rw ?: '—' }}</div>
            </div>
            <div class="col-12">
                <div class="cust-label">Alamat Lengkap</div>
                <div class="cust-value">{{ $customer->address ?: '—' }}</div>
            </div>
            @if($customer->full_address && $customer->city)
            <div class="col-12 mt-1">
                <a href="https://maps.google.com/?q={{ urlencode($customer->full_address) }}"
                    target="_blank" rel="noopener" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-map-marker-alt me-1"></i>Buka di Google Maps
                    <i class="fas fa-external-link-alt ms-1"></i>
                </a>
            </div>
            @endif
        </div>
    </div>

    {{-- ── Tab: PIC ───────────────────────────────────────── --}}
    <div class="tab-pane fade cust-tab-pane" id="tab-pic">
        @if($customer->pics->isEmpty())
            <div class="cust-empty">
                <i class="fas fa-user-slash"></i>
                <div>Belum ada PIC terdaftar</div>
            </div>
        @else
            @foreach($customer->pics as $pic)
                <div class="pic-card">
                    <div class="pic-card-header">
                        <div class="pic-avatar">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="pic-name">{{ $pic->name }}</div>
                            @if($pic->position || $pic->department)
                                <div style="font-size:.8rem;color:var(--text-muted)">
                                    {{ collect([$pic->position, $pic->department])->filter()->implode(' · ') }}
                                </div>
                            @endif
                        </div>
                        @if($pic->is_primary)
                            <span class="badge bg-primary">Utama</span>
                        @endif
                    </div>
                    <div class="row g-2">
                        <div class="col-6">
                            <div class="cust-label">Telepon</div>
                            <div class="cust-value">
                                @if($pic->phone)
                                    <a href="tel:{{ $pic->phone }}" class="text-decoration-none" style="color:var(--text)">
                                        {{ $pic->phone }}
                                    </a>
                                @else <span class="cust-value-empty">—</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="cust-label">Email</div>
                            <div class="cust-value">
                                @if($pic->email)
                                    <a href="mailto:{{ $pic->email }}" class="text-decoration-none" style="color:var(--text)">
                                        {{ $pic->email }}
                                    </a>
                                @else <span class="cust-value-empty">—</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>

</div>

@endsection

@push('scripts')
<script>
    // Simpan posisi tab aktif ke sessionStorage
    document.addEventListener('DOMContentLoaded', function () {
        const nav = document.getElementById('custTabs');
        if (!nav) return;
        const key = 'cust_tab_' + window.location.pathname;
        const saved = sessionStorage.getItem(key);
        if (saved) {
            const t = nav.querySelector('[data-bs-target="' + saved + '"]');
            if (t) bootstrap.Tab.getOrCreateInstance(t).show();
        }
        nav.addEventListener('shown.bs.tab', function (e) {
            sessionStorage.setItem(key, e.target.dataset.bsTarget);
        });
    });
</script>
@endpush
