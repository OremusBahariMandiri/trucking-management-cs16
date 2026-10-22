<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Beranda') — {{ config('app.name') }}</title>
    <script>
        (function() {
            var t = localStorage.getItem('theme') || 'light';
            if (t === 'dark') document.documentElement.setAttribute('data-theme', 'dark');
            else if (t === 'light') document.documentElement.setAttribute('data-theme', 'light');
        })();
    </script>
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.2/css/responsive.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    @vite(['resources/sass/app.scss', 'resources/js/app.js', 'resources/js/admin.js'])
    @stack('styles')
</head>

<body>

    <div class="overlay" id="overlay" onclick="toggleSidebar()"></div>

    <div id="app">

        {{-- ── SIDEBAR ──────────────────────────────────────────────────────── --}}
        <aside id="sidebar">
            <div class="sb-header">
                <div class="sb-logo">
                    <div class="logo-mark" style="background:transparent;box-shadow:none;padding:0">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo CS16"
                            style="width:36px;height:36px;object-fit:contain;">
                    </div>
                    <div class="logo-text">
                        <div class="logo-name">CITRACK 16</div>
                        <div class="logo-tag" data-en="Management System">PT. CITRA SAMUDERA 16</div>
                    </div>
                </div>
            </div>

            <nav class="sb-nav">

                {{-- ── Beranda ── --}}
                <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                    <span class="n-icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round">
                            <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                            <polyline points="9 22 9 12 15 12 15 22" />
                        </svg>
                    </span>
                    <span class="n-label" data-en="Home">Beranda</span>
                </a>

                <div class="nav-sep"></div>

                {{-- ══════════════════════════════════════════════════
                     MANAJEMEN PENGGUNA
                     Tampil hanya jika punya akses ke minimal 1 sub-menu
                ═══════════════════════════════════════════════════════ --}}
                @php
                    $umOpen = request()->routeIs('user-management.*');
                    $canUmUsers = Auth::user()->hasAccessToMenu('user-management.users');
                    $canUmRoles = Auth::user()->hasAccessToMenu('user-management.roles');
                    $canUmPermissions = Auth::user()->isSuperAdmin();
                    $showUmGroup = $canUmUsers || $canUmRoles || $canUmPermissions;
                @endphp

                @if ($showUmGroup)
                    <div class="nav-group">
                        <div class="nav-link has-sub {{ $umOpen ? 'active open' : '' }}" onclick="toggleSub(this)">
                            <span class="n-icon">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round">
                                    <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" />
                                    <circle cx="9" cy="7" r="4" />
                                    <path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75" />
                                </svg>
                            </span>
                            <span class="n-label" data-en="User Management">Manajemen Pengguna</span>
                            <svg class="n-arrow {{ $umOpen ? 'open' : '' }}" width="14" height="14"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                                stroke-linecap="round">
                                <polyline points="9 18 15 12 9 6" />
                            </svg>
                        </div>
                        <div class="n-sub {{ $umOpen ? 'open' : '' }}">
                            @if ($canUmUsers)
                                <a href="{{ route('user-management.users.index') }}"
                                    class="sub-link {{ request()->routeIs('user-management.users.*') ? 'active' : '' }}"
                                    data-en="Users">Pengguna</a>
                            @endif
                            @if ($canUmRoles)
                                <a href="{{ route('user-management.roles.index') }}"
                                    class="sub-link {{ request()->routeIs('user-management.roles.*') ? 'active' : '' }}"
                                    data-en="Roles">Peran</a>
                            @endif
                            @if ($canUmPermissions)
                                <a href="{{ route('user-management.permissions.index') }}"
                                    class="sub-link {{ request()->routeIs('user-management.permissions.*') ? 'active' : '' }}"
                                    data-en="Permissions">Permission</a>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- ══════════════════════════════════════════════════
                     DATA MASTER
                     Tampil hanya jika punya akses ke minimal 1 sub-menu
                ═══════════════════════════════════════════════════════ --}}
                @php
                    $canVehicleTypes = Auth::user()->hasAccessToMenu('master-data.vehicle-types');
                    $canVehicleBrands = Auth::user()->hasAccessToMenu('master-data.vehicle-brands');
                    $canVehicleModels = Auth::user()->hasAccessToMenu('master-data.vehicle-models');
                    $canFuelTypes = Auth::user()->hasAccessToMenu('master-data.fuel-types');
                    $canBodyTypes = Auth::user()->hasAccessToMenu('master-data.body-types');
                    $canAxisConfigurations = Auth::user()->hasAccessToMenu('master-data.axis-configurations');
                    $canOwnershipStatuses = Auth::user()->hasAccessToMenu('master-data.ownership-statuses');
                    // Administrasi
                    $canCustomerTypes = Auth::user()->hasAccessToMenu('master-data.administration.customer-types');
                    $canBusinessTypes = Auth::user()->hasAccessToMenu('master-data.administration.business-types');
                    $canCustomers = Auth::user()->hasAccessToMenu('master-data.administration.customers');
                    $showAdminSub = $canCustomerTypes || $canBusinessTypes || $canCustomers;
                    $showKendaraanSub =
                        $canVehicleTypes ||
                        $canVehicleBrands ||
                        $canVehicleModels ||
                        $canFuelTypes ||
                        $canBodyTypes ||
                        $canAxisConfigurations ||
                        $canOwnershipStatuses;
                    $showMasterGroup = $showKendaraanSub || $showAdminSub;

                    $masterOpen = request()->routeIs(
                        'master-data.vehicle-types.*',
                        'master-data.vehicle-brands.*',
                        'master-data.vehicle-models.*',
                        'master-data.fuel-types.*',
                        'master-data.body-types.*',
                        'master-data.axis-configurations.*',
                        'master-data.ownership-statuses.*',
                        'master-data.administration.*',
                    );
                    $kendaraanOpen = request()->routeIs(
                        'master-data.vehicle-types.*',
                        'master-data.vehicle-brands.*',
                        'master-data.vehicle-models.*',
                        'master-data.fuel-types.*',
                        'master-data.body-types.*',
                        'master-data.axis-configurations.*',
                        'master-data.ownership-statuses.*',
                    );
                    $adminOpen = request()->routeIs('master-data.administration.*');
                @endphp

                @if ($showMasterGroup)
                    <div class="nav-group">
                        <div class="nav-link has-sub {{ $masterOpen ? 'active open' : '' }}" onclick="toggleSub(this)">
                            <span class="n-icon">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round">
                                    <ellipse cx="12" cy="5" rx="9" ry="3" />
                                    <path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3" />
                                    <path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5" />
                                </svg>
                            </span>
                            <span class="n-label" data-en="Master Data">Data Master</span>
                            <svg class="n-arrow {{ $masterOpen ? 'open' : '' }}" width="14" height="14"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                                stroke-linecap="round">
                                <polyline points="9 18 15 12 9 6" />
                            </svg>
                        </div>

                        <div class="n-sub {{ $masterOpen ? 'open' : '' }}">
                            @if ($showKendaraanSub)
                                <div class="sub-group-hd {{ $kendaraanOpen ? 'open' : '' }}"
                                    onclick="toggleSubGroup(this)">
                                    <span class="n-icon" style="width:16px;height:16px;flex-shrink:0">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <rect x="1" y="3" width="15" height="13" />
                                            <polygon points="16 8 20 8 23 11 23 16 16 16 16 8" />
                                            <circle cx="5.5" cy="18.5" r="2.5" />
                                            <circle cx="18.5" cy="18.5" r="2.5" />
                                        </svg>
                                    </span>
                                    <span data-en="Vehicles">Kendaraan</span>
                                    <svg class="sub-arrow {{ $kendaraanOpen ? 'open' : '' }}" width="13"
                                        height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2.5" stroke-linecap="round">
                                        <polyline points="9 18 15 12 9 6" />
                                    </svg>
                                </div>
                                <div class="sub-group-body {{ $kendaraanOpen ? 'open' : '' }}">
                                    @if ($canVehicleTypes)
                                        <a href="{{ route('master-data.vehicle-types.index') }}"
                                            class="sub-link2 {{ request()->routeIs('master-data.vehicle-types.*') ? 'active' : '' }}"
                                            data-en="Vehicle Types">Tipe Kendaraan</a>
                                    @endif
                                    @if ($canVehicleBrands)
                                        <a href="{{ route('master-data.vehicle-brands.index') }}"
                                            class="sub-link2 {{ request()->routeIs('master-data.vehicle-brands.*') ? 'active' : '' }}"
                                            data-en="Vehicle Brands">Merek Kendaraan</a>
                                    @endif
                                    @if ($canVehicleModels)
                                        <a href="{{ route('master-data.vehicle-models.index') }}"
                                            class="sub-link2 {{ request()->routeIs('master-data.vehicle-models.*') ? 'active' : '' }}"
                                            data-en="Vehicle Models">Model Kendaraan</a>
                                    @endif
                                    @if ($canFuelTypes)
                                        <a href="{{ route('master-data.fuel-types.index') }}"
                                            class="sub-link2 {{ request()->routeIs('master-data.fuel-types.*') ? 'active' : '' }}"
                                            data-en="Fuel Types">Bahan Bakar</a>
                                    @endif
                                    @if ($canBodyTypes)
                                        <a href="{{ route('master-data.body-types.index') }}"
                                            class="sub-link2 {{ request()->routeIs('master-data.body-types.*') ? 'active' : '' }}"
                                            data-en="Body Types">Jenis Karoseri</a>
                                    @endif
                                    @if ($canAxisConfigurations)
                                        <a href="{{ route('master-data.axis-configurations.index') }}"
                                            class="sub-link2 {{ request()->routeIs('master-data.axis-configurations.*') ? 'active' : '' }}"
                                            data-en="Axis Configurations">Konfigurasi Sumbu</a>
                                    @endif
                                    @if ($canOwnershipStatuses)
                                        <a href="{{ route('master-data.ownership-statuses.index') }}"
                                            class="sub-link2 {{ request()->routeIs('master-data.ownership-statuses.*') ? 'active' : '' }}"
                                            data-en="Ownership Statuses">Status Kepemilikan</a>
                                    @endif
                                </div>
                            @endif

                            {{-- ── Administrasi ──────────────────────── --}}
                            @if ($showAdminSub)
                                <div class="sub-group-hd {{ $adminOpen ? 'open' : '' }}"
                                    onclick="toggleSubGroup(this)">
                                    <span class="n-icon" style="width:16px;height:16px;flex-shrink:0">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                                            <circle cx="9" cy="7" r="4" />
                                            <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                                            <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                                        </svg>
                                    </span>
                                    <span data-en="Administration">Administrasi</span>
                                    <svg class="sub-arrow {{ $adminOpen ? 'open' : '' }}" width="13"
                                        height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2.5" stroke-linecap="round">
                                        <polyline points="9 18 15 12 9 6" />
                                    </svg>
                                </div>
                                <div class="sub-group-body {{ $adminOpen ? 'open' : '' }}">
                                    @if ($canCustomerTypes)
                                        <a href="{{ route('master-data.administration.customer-types.index') }}"
                                            class="sub-link2 {{ request()->routeIs('master-data.administration.customer-types.*') ? 'active' : '' }}"
                                            data-en="Customer Types">
                                            Jenis Pelanggan
                                        </a>
                                    @endif
                                    @if ($canBusinessTypes)
                                        <a href="{{ route('master-data.administration.business-types.index') }}"
                                            class="sub-link2 {{ request()->routeIs('master-data.administration.business-types.*') ? 'active' : '' }}"
                                            data-en="Business Types">
                                            Jenis Usaha
                                        </a>
                                    @endif
                                    @if ($canCustomers)
                                        <a href="{{ route('master-data.administration.customers.index') }}"
                                            class="sub-link2 {{ request()->routeIs('master-data.administration.customers.*') ? 'active' : '' }}"
                                            data-en="Customer Data">
                                            Data Pelanggan
                                        </a>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- ══════════════════════════════════════════════════
                     MANAJEMEN DATA
                     Tampil hanya jika punya akses ke minimal 1 sub-menu
                ═══════════════════════════════════════════════════════ --}}
                @php
                    $canFleetData = Auth::user()->hasAccessToMenu('data-management.fleet-data');
                    $canEmployeeData = Auth::user()->hasAccessToMenu('data-management.sdm-management.employee-data');
                    $canDriverData = Auth::user()->hasAccessToMenu('data-management.sdm-management.driver-data');
                    $showSdmSub = $canEmployeeData || $canDriverData;
                    $showManajemenGroup = $canFleetData || $showSdmSub;

                    $manajemenOpen = request()->routeIs(
                        'data-management.fleet-data.*',
                        'data-management.sdm-management.*',
                    );
                    $sdmOpen = request()->routeIs('data-management.sdm-management.*');
                @endphp

                @if ($showManajemenGroup)
                    <div class="nav-group">
                        <div class="nav-link has-sub {{ $manajemenOpen ? 'active open' : '' }}"
                            onclick="toggleSub(this)">
                            <span class="n-icon">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <rect x="2" y="3" width="20" height="14" rx="2" />
                                    <line x1="8" y1="21" x2="16" y2="21" />
                                    <line x1="12" y1="17" x2="12" y2="21" />
                                    <path d="M9 8l3 3 3-3" />
                                </svg>
                            </span>
                            <span class="n-label" data-en="Data Management">Manajemen Data</span>
                            <svg class="n-arrow {{ $manajemenOpen ? 'open' : '' }}" width="14" height="14"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                                stroke-linecap="round">
                                <polyline points="9 18 15 12 9 6" />
                            </svg>
                        </div>

                        <div class="n-sub {{ $manajemenOpen ? 'open' : '' }}">
                            @if ($canFleetData)
                                <a href="{{ route('data-management.fleet-data.index') }}"
                                    class="sub-group-hd {{ request()->routeIs('data-management.fleet-data.*') ? 'active' : '' }}">
                                    <span class="n-icon" style="width:16px;height:16px;flex-shrink:0">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <rect x="1" y="3" width="15" height="13" />
                                            <polygon points="16 8 20 8 23 11 23 16 16 16 16 8" />
                                            <circle cx="5.5" cy="18.5" r="2.5" />
                                            <circle cx="18.5" cy="18.5" r="2.5" />
                                        </svg>
                                    </span>
                                    <span data-en="Fleet">Kendaraan</span>
                                </a>
                            @endif

                            @if ($showSdmSub)
                                <div class="sub-group-hd {{ $sdmOpen ? 'open' : '' }}"
                                    onclick="toggleSubGroup(this)">
                                    <span class="n-icon" style="width:16px;height:16px;flex-shrink:0">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round">
                                            <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" />
                                            <circle cx="9" cy="7" r="4" />
                                            <path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75" />
                                        </svg>
                                    </span>
                                    <span data-en="HR">SDM</span>
                                    <svg class="sub-arrow {{ $sdmOpen ? 'open' : '' }}" width="13"
                                        height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2.5" stroke-linecap="round">
                                        <polyline points="9 18 15 12 9 6" />
                                    </svg>
                                </div>
                                <div class="sub-group-body {{ $sdmOpen ? 'open' : '' }}">
                                    @if ($canEmployeeData)
                                        <a href="{{ route('data-management.sdm-management.employee-data.index') }}"
                                            class="sub-link2 {{ request()->routeIs('data-management.sdm-management.employee-data.*') ? 'active' : '' }}"
                                            data-en="Employee Data">Data Karyawan</a>
                                    @endif
                                    @if ($canDriverData)
                                        <a href="{{ route('data-management.sdm-management.driver-data.index') }}"
                                            class="sub-link2 {{ request()->routeIs('data-management.sdm-management.driver-data.*') ? 'active' : '' }}"
                                            data-en="Driver Data">Data Supir</a>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- ── Laporan ── --}}
                <a href="#" class="nav-link">
                    <span class="n-icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round">
                            <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z" />
                            <polyline points="14 2 14 8 20 8" />
                            <line x1="16" y1="13" x2="8" y2="13" />
                            <line x1="16" y1="17" x2="8" y2="17" />
                            <polyline points="10 9 9 9 8 9" />
                        </svg>
                    </span>
                    <span class="n-label" data-en="Reports">Laporan</span>
                </a>

                <div class="nav-sep"></div>

                {{-- ── Pengaturan ── --}}
                <a href="#" class="nav-link">
                    <span class="n-icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round">
                            <circle cx="12" cy="12" r="3" />
                            <path
                                d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-2 2 2 2 0 01-2-2v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06-.06a2 2 0 01-2.83 0 2 2 0 010-2.83l.06-.06A1.65 1.65 0 004.68 15a1.65 1.65 0 00-1.51-1H3a2 2 0 01-2-2 2 2 0 012-2h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 010-2.83 2 2 0 012.83 0l.06.06A1.65 1.65 0 009 4.68a1.65 1.65 0 001-1.51V3a2 2 0 012-2 2 2 0 012 2v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 0 2 2 0 010 2.83l-.06.06A1.65 1.65 0 0019.4 9a1.65 1.65 0 001.51 1H21a2 2 0 012 2 2 2 0 01-2 2h-.09a1.65 1.65 0 00-1.51 1z" />
                        </svg>
                    </span>
                    <span class="n-label" data-en="Settings">Pengaturan</span>
                </a>

                @stack('sidebar-menu')
            </nav>
        </aside>

        {{-- ── MAIN ──────────────────────────────────────────────────────────── --}}
        <div id="main">

            <header id="navbar">
                <button class="hamburger" onclick="toggleSidebar()">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2.2" stroke-linecap="round">
                        <line x1="3" y1="6" x2="21" y2="6" />
                        <line x1="3" y1="12" x2="21" y2="12" />
                        <line x1="3" y1="18" x2="21" y2="18" />
                    </svg>
                </button>

                <div class="breadcrumb">
                    <span data-en="Home">Beranda</span>
                    <span class="bc-sep">/</span>
                    <span class="cur">@yield('breadcrumb', 'Beranda')</span>
                </div>

                <div class="nav-actions">

                    <div class="lang-pill" translate="no">
                        <button class="lang-btn" id="btn-en" onclick="setLang('en')">EN</button>
                        <button class="lang-btn active" id="btn-id" onclick="setLang('id')">ID</button>
                    </div>

                    <div class="theme-pill">
                        <button class="theme-btn" id="t-light" onclick="setTheme('light')" title="Mode Terang">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2.2" stroke-linecap="round">
                                <circle cx="12" cy="12" r="5" />
                                <line x1="12" y1="1" x2="12" y2="3" />
                                <line x1="12" y1="21" x2="12" y2="23" />
                                <line x1="4.22" y1="4.22" x2="5.64" y2="5.64" />
                                <line x1="18.36" y1="18.36" x2="19.78" y2="19.78" />
                                <line x1="1" y1="12" x2="3" y2="12" />
                                <line x1="21" y1="12" x2="23" y2="12" />
                                <line x1="4.22" y1="19.78" x2="5.64" y2="18.36" />
                                <line x1="18.36" y1="5.64" x2="19.78" y2="4.22" />
                            </svg>
                        </button>
                        <button class="theme-btn active" id="t-system" onclick="setTheme('system')"
                            title="Ikuti Sistem">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2.2" stroke-linecap="round">
                                <rect x="2" y="3" width="20" height="14" rx="2" />
                                <line x1="8" y1="21" x2="16" y2="21" />
                                <line x1="12" y1="17" x2="12" y2="21" />
                            </svg>
                        </button>
                        <button class="theme-btn" id="t-dark" onclick="setTheme('dark')" title="Mode Gelap">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2.2" stroke-linecap="round">
                                <path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z" />
                            </svg>
                        </button>
                    </div>

                    <div class="vr"></div>

                    <div class="user-menu" onclick="toggleDropdown(event)">
                        <div class="u-avatar">
                            {{ strtoupper(substr(Auth::user()->full_name, 0, 2)) }}
                        </div>
                        <div class="u-info">
                            <div class="u-name">{{ Auth::user()->full_name }}</div>
                            <div class="u-role">
                                {{ Auth::user()->roles->first()?->name ?? 'Pengguna' }}
                            </div>
                        </div>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2.5" stroke-linecap="round"
                            style="color:var(--text-faint);margin-left:2px">
                            <polyline points="6 9 12 15 18 9" />
                        </svg>

                        <div class="u-dropdown" id="userDrop">
                            <a href="#" class="dd-item">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round">
                                    <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2" />
                                    <circle cx="12" cy="7" r="4" />
                                </svg>
                                <span data-en="My Profile">Profil Saya</span>
                            </a>
                            <a href="#" class="dd-item">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round">
                                    <circle cx="12" cy="12" r="3" />
                                    <path
                                        d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-2 2 2 2 0 01-2-2v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06-.06a2 2 0 01-2.83 0 2 2 0 010-2.83l.06-.06A1.65 1.65 0 004.68 15a1.65 1.65 0 00-1.51-1H3a2 2 0 01-2-2 2 2 0 012-2h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 010-2.83 2 2 0 012.83 0l.06.06A1.65 1.65 0 009 4.68a1.65 1.65 0 001-1.51V3a2 2 0 012-2 2 2 0 012 2v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 0 2 2 0 010 2.83l-.06.06A1.65 1.65 0 0019.4 9a1.65 1.65 0 001.51 1H21a2 2 0 012 2 2 2 0 01-2 2h-.09a1.65 1.65 0 00-1.51 1z" />
                                </svg>
                                <span data-en="Settings">Pengaturan</span>
                            </a>
                            <div class="dd-div"></div>
                            <a href="#" class="dd-item danger"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round">
                                    <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9" />
                                </svg>
                                <span data-en="Logout">Keluar</span>
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </div>

                </div>
            </header>

            <main id="content">
                @yield('content')
            </main>

        </div>
    </div>

    {{-- sebelum </body> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.2/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.2/js/responsive.bootstrap5.min.js"></script>
    <script>
    function initDataTable(tableId, options) {
        var defaults = {
            pageLength: 10,
            lengthMenu: [[5, 10, 25, 50, -1], ['5', '10', '25', '50', 'Semua']],
            dom: '<"dt-top-bar"lf>rt<"dt-bottom-bar"ip>',
            language: {
                lengthMenu:   'Show _MENU_ data',
                zeroRecords:  'Tidak ada data ditemukan',
                info:         'Menampilkan _START_–_END_ dari _TOTAL_ data',
                infoEmpty:    'Menampilkan 0 dari 0 data',
                infoFiltered: '(difilter dari _MAX_ total data)',
                search:       '',
                searchPlaceholder: 'Cari...',
                paginate: { first: '«', last: '»', next: '›', previous: '‹' }
            },
            responsive: true,
            autoWidth: false
        };
        return $('#' + tableId).DataTable(Object.assign({}, defaults, options || {}));
    }
    </script>
    {{-- ── SweetAlert2 + Truck ──────────────────────────── --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.5/dist/sweetalert2.all.min.js"></script>
    <style>
        @keyframes truckSlideIn {
            0%   { transform: translateX(calc(-50% - 300px)); }
            65%  { transform: translateX(calc(-50% + 8px));   }
            82%  { transform: translateX(calc(-50% - 4px));   }
            100% { transform: translateX(-50%);                }
        }
        @keyframes badgePop {
            0%   { transform: translate(-50%,-50%) scale(0);   opacity:0; }
            70%  { transform: translate(-50%,-50%) scale(1.25); opacity:1; }
            100% { transform: translate(-50%,-50%) scale(1);    opacity:1; }
        }
        /* ── SweetAlert truck popup ── */
        .swal2-popup.swal-truck-popup {
            border-radius: 20px !important;
            padding: 1.75rem 1.5rem 1.5rem !important;
            max-width: 420px !important;
            background: #ffffff !important;
            color: #111827 !important;
        }
        :root[data-theme="dark"] .swal2-popup.swal-truck-popup,
        :root:not([data-theme="light"]) .swal2-popup.swal-truck-popup {
            background: #1e2330 !important;
            color: #e5e7eb !important;
            box-shadow: 0 8px 32px rgba(0,0,0,.5) !important;
        }
        /* sembunyikan icon bawaan sweetalert */
        .swal2-popup.swal-truck-popup .swal2-icon { display:none !important; }
        .swal2-popup.swal-truck-popup .swal2-title {
            font-size: 1.1rem !important;
            margin: .5rem 0 .15rem !important;
            padding: 0 !important;
            color: inherit !important;
        }
        .swal2-popup.swal-truck-popup .swal2-html-container {
            font-size: .875rem !important;
            color: #6b7280 !important;
            margin: 0 0 .25rem !important;
        }
        :root[data-theme="dark"] .swal2-popup.swal-truck-popup .swal2-html-container,
        :root:not([data-theme="light"]) .swal2-popup.swal-truck-popup .swal2-html-container {
            color: #9ca3af !important;
        }
        .swal2-popup.swal-truck-popup .swal2-actions {
            margin-top: 1.1rem !important;
            gap: .5rem;
        }
        /* road line dark mode */
        :root[data-theme="dark"] .stw-road,
        :root:not([data-theme="light"]) .stw-road {
            background: linear-gradient(90deg,transparent,#374151 15%,#374151 85%,transparent) !important;
        }
        /* scene wrapper */
        .stw-scene-wrap {
            overflow: hidden;
            padding-top: 28px;
            margin-top: .25rem;
            margin-bottom: .5rem;
        }
        .stw-scene {
            position: relative;
            height: 118px;
            overflow: visible;
        }
        .stw-truck {
            position: absolute;
            bottom: 0; left: 50%;
            animation: truckSlideIn .7s cubic-bezier(.22,.68,0,1.1) .1s forwards;
        }
        /* badge hasil (✓ / ✕ dll) muncul setelah truck berhenti */
        .stw-badge {
            position: absolute;
            top: 8px; left: 50%;
            transform: translate(-50%,-50%) scale(0);
            width: 42px; height: 42px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.25rem; font-weight: 700; color: #fff;
            box-shadow: 0 4px 16px rgba(0,0,0,.18);
            animation: badgePop .45s cubic-bezier(.34,1.56,.64,1) .72s forwards;
            z-index: 10;
        }
        .stw-road {
            position: absolute;
            bottom: 0; left: -10px; right: -10px;
            height: 2px;
            background: linear-gradient(90deg,transparent,#e5e7eb 15%,#e5e7eb 85%,transparent);
        }
    </style>
    <script>
    (function() {
        /* warna & badge per tipe */
        var _cfg = {
            success:  { color:'#10b981', soft:'rgba(16,185,129,.15)', icon:'&#10003;' },
            error:    { color:'#ef4444', soft:'rgba(239,68,68,.12)',   icon:'&#10005;' },
            warning:  { color:'#f59e0b', soft:'rgba(245,158,11,.13)',  icon:'!' },
            info:     { color:'#59a6f5', soft:'rgba(59,130,246,.12)',  icon:'i' },
            question: { color:'#6366f1', soft:'rgba(99,102,241,.12)',  icon:'?' },
        };

        function _makeTruckSvg(c, s) {
            return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 62" width="190" height="59">'
                /* ground */
                + '<line x1="0" y1="55" x2="200" y2="55" stroke="#e5e7eb" stroke-width="1.2"/>'
                /* trailer body */
                + '<rect x="2" y="13" width="112" height="38" rx="5" fill="' + s + '" stroke="' + c + '" stroke-width="1.5"/>'
                /* trailer panel lines */
                + '<line x1="40" y1="13" x2="40" y2="51" stroke="' + c + '" stroke-width=".8" opacity=".3"/>'
                + '<line x1="76" y1="13" x2="76" y2="51" stroke="' + c + '" stroke-width=".8" opacity=".3"/>'
                /* hitch */
                + '<rect x="112" y="34" width="10" height="7" rx="2" fill="' + c + '" opacity=".55"/>'
                /* cab body */
                + '<rect x="118" y="21" width="58" height="30" rx="5" fill="' + c + '"/>'
                /* cab roof */
                + '<path d="M122 21 Q127 10 147 10 L176 10 L176 21 Z" fill="' + c + '"/>'
                /* windshield */
                + '<path d="M126 21 Q130 13 147 13 L172 13 L172 21 Z" fill="white" opacity=".45"/>'
                /* side window */
                + '<rect x="120" y="24" width="13" height="11" rx="2" fill="white" opacity=".3"/>'
                /* exhaust */
                + '<rect x="170" y="3" width="5" height="11" rx="2.5" fill="' + c + '" opacity=".8"/>'
                /* headlight */
                + '<rect x="173" y="30" width="5" height="8" rx="2" fill="#fef9c3"/>'
                /* bumper */
                + '<rect x="173" y="44" width="17" height="5" rx="2" fill="' + c + '" opacity=".7"/>'
                /* wheels */
                + '<circle cx="32"  cy="54" r="8" fill="#1f2937"/><circle cx="32"  cy="54" r="4.5" fill="#6b7280"/><circle cx="32"  cy="54" r="1.8" fill="#374151"/>'
                + '<circle cx="82"  cy="54" r="8" fill="#1f2937"/><circle cx="82"  cy="54" r="4.5" fill="#6b7280"/><circle cx="82"  cy="54" r="1.8" fill="#374151"/>'
                + '<circle cx="136" cy="54" r="8" fill="#1f2937"/><circle cx="136" cy="54" r="4.5" fill="#6b7280"/><circle cx="136" cy="54" r="1.8" fill="#374151"/>'
                + '<circle cx="166" cy="54" r="8" fill="#1f2937"/><circle cx="166" cy="54" r="4.5" fill="#6b7280"/><circle cx="166" cy="54" r="1.8" fill="#374151"/>'
                + '</svg>';
        }

        function _buildHtml(type, msgHtml) {
            var cfg = _cfg[type] || _cfg.success;
            return '<div class="stw-scene-wrap"><div class="stw-scene">'
                + '<div class="stw-road"></div>'
                + '<div class="stw-badge" style="background:' + cfg.color + '">' + cfg.icon + '</div>'
                + '<div class="stw-truck">' + _makeTruckSvg(cfg.color, cfg.soft) + '</div>'
                + '</div></div>'
                + (msgHtml ? '<div>' + msgHtml + '</div>' : '');
        }

        window.truckAlert = function(opts) {
            var type = opts.icon || 'success';
            return Swal.fire({
                customClass: { popup: 'swal-truck-popup' },
                html:  _buildHtml(type, opts.text || ''),
                title: opts.title || (function(){
                    var isEn = (typeof lang !== 'undefined' ? lang : (localStorage.getItem('lang')||'id')) === 'en';
                    return isEn
                        ? { success:'Success!', error:'Failed!', warning:'Warning', info:'Info' }[type]
                        : { success:'Berhasil!', error:'Gagal!', warning:'Perhatian', info:'Info' }[type];
                })() || 'Info',
                timer: opts.timer !== undefined ? opts.timer : 2000,
                timerProgressBar: opts.timer !== 0,
                showConfirmButton: !!opts.showConfirmButton,
            });
        };

        window.truckConfirm = function(opts) {
            var isEn = (typeof lang !== 'undefined' ? lang : (localStorage.getItem('lang')||'id')) === 'en';
            return Swal.fire({
                customClass: {
                    popup:         'swal-truck-popup',
                    confirmButton: 'btn btn-danger btn-sm me-1',
                    cancelButton:  'btn btn-secondary btn-sm',
                },
                buttonsStyling:   false,
                html:             _buildHtml('question', opts.text || (isEn ? 'Are you sure?' : 'Yakin ingin menghapus?')),
                title:            opts.title || (isEn ? 'Confirm' : 'Konfirmasi'),
                showCancelButton: true,
                confirmButtonText: opts.confirmText || (isEn ? 'Yes, Delete' : 'Ya, Hapus'),
                cancelButtonText:  opts.cancelText  || (isEn ? 'Cancel' : 'Batal'),
            });
        };

        /* auto flash session */
        @if(session('success'))
            truckAlert({ icon:'success', title:'Berhasil!', text:@json(session('success')) });
        @endif
        @if(session('error'))
            truckAlert({ icon:'error', title:'Gagal!', text:@json(session('error')), timer:4000 });
        @endif
        @if(session('warning'))
            truckAlert({ icon:'warning', title:'Perhatian', text:@json(session('warning')), timer:4000 });
        @endif
        @if(session('info'))
            truckAlert({ icon:'info', title:'Info', text:@json(session('info')) });
        @endif
    })();
    </script>
    @stack('scripts')
</body>

</html>
