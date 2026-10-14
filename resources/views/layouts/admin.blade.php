<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Beranda') — {{ config('app.name') }}</title>
    {{-- Terapkan theme SEBELUM render supaya tidak flash --}}
    <script>
        (function() {
            var t = localStorage.getItem('theme') || 'system';
            if (t === 'dark') document.documentElement.setAttribute('data-theme', 'dark');
            else if (t === 'light') document.documentElement.setAttribute('data-theme', 'light');
        })();
    </script>
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
                    <div class="logo-mark">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#fff"
                            stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="1" y="3" width="15" height="13" />
                            <polygon points="16 8 20 8 23 11 23 16 16 16 16 8" />
                            <circle cx="5.5" cy="18.5" r="2.5" />
                            <circle cx="18.5" cy="18.5" r="2.5" />
                        </svg>
                    </div>
                    <div class="logo-text">
                        <div class="logo-name">{{ config('app.name') }}</div>
                        <div class="logo-tag" data-en="Management System">Management System</div>
                    </div>
                </div>
            </div>

            <nav class="sb-nav">

                {{-- Beranda --}}
                <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                    <span class="n-icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round">
                            <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                            <polyline points="9 22 9 12 15 12 15 22" />
                        </svg>
                    </span>
                    <span class="n-label">Beranda</span>
                </a>

                <div class="nav-sep"></div>


                {{-- Manajemen Pengguna --}}
                <div class="nav-group">
                    <div class="nav-link has-sub" onclick="toggleSub(this)">
                        <span class="n-icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round">
                                <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" />
                                <circle cx="9" cy="7" r="4" />
                                <path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75" />
                            </svg>
                        </span>
                        <span class="n-label">Pengguna</span>
                        <svg class="n-arrow" width="14" height="14" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                            <polyline points="9 18 15 12 9 6" />
                        </svg>
                    </div>
                    <div class="n-sub">
                        <a href="#" class="sub-link">Daftar Pengguna</a>
                        <a href="#" class="sub-link">Peran & Akses</a>
                    </div>
                </div>
                @php
                    $masterOpen = request()->routeIs(
                        'master-data.kendaraan.*',
                        'master-data.vehicle-types.*',
                        'master-data.vehicle-brands.*',
                        'master-data.vehicle-models.*',
                        'master-data.fuel-types.*',
                        'master-data.body-types.*',
                    );
                    $kendaraanOpen = request()->routeIs(
                        'master-data.kendaraan.*',
                        'master-data.vehicle-types.*',
                        'master-data.vehicle-brands.*',
                        'master-data.vehicle-models.*',
                        'master-data.fuel-types.*',
                        'master-data.body-types.*',
                    );
                @endphp

                <div class="nav-group">
                    <div class="nav-link has-sub {{ $masterOpen ? 'active open' : '' }}" onclick="toggleSub(this)">
                        <span class="n-icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round">
                                <ellipse cx="12" cy="5" rx="9" ry="3" />
                                <path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3" />
                                <path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5" />
                            </svg>
                        </span>
                        <span class="n-label">Data Master</span>
                        <svg class="n-arrow {{ $masterOpen ? 'open' : '' }}" width="14" height="14"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                            stroke-linecap="round">
                            <polyline points="9 18 15 12 9 6" />
                        </svg>
                    </div>

                    <div class="n-sub {{ $masterOpen ? 'open' : '' }}">

                        {{-- ── Kendaraan (sub-group) ── --}}
                        <div class="sub-group-hd {{ $kendaraanOpen ? 'open' : '' }}" onclick="toggleSubGroup(this)">
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
                            <span>Kendaraan</span>
                            <svg class="sub-arrow {{ $kendaraanOpen ? 'open' : '' }}" width="13" height="13"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                                stroke-linecap="round">
                                <polyline points="9 18 15 12 9 6" />
                            </svg>
                        </div>

                        <div class="sub-group-body {{ $kendaraanOpen ? 'open' : '' }}">
                            <a href="{{ route('master-data.kendaraan.index') }}"
                                class="sub-link2 {{ request()->routeIs('master-data.kendaraan.*') ? 'active' : '' }}">
                                Data Kendaraan
                            </a>
                            <a href="{{ route('master-data.vehicle-types.index') }}"
                                class="sub-link2 {{ request()->routeIs('master-data.vehicle-types.*') ? 'active' : '' }}">
                                Tipe Kendaraan
                            </a>
                            <a href="{{ route('master-data.vehicle-brands.index') }}"
                                class="sub-link2 {{ request()->routeIs('master-data.vehicle-brands.*') ? 'active' : '' }}">
                                Merek Kendaraan
                            </a>
                            <a href="{{ route('master-data.vehicle-models.index') }}"
                                class="sub-link2 {{ request()->routeIs('master-data.vehicle-models.*') ? 'active' : '' }}">
                                Model Kendaraan
                            </a>
                            <a href="{{ route('master-data.fuel-types.index') }}"
                                class="sub-link2 {{ request()->routeIs('master-data.fuel-types.*') ? 'active' : '' }}">
                                Bahan Bakar
                            </a>
                            <a href="{{ route('master-data.body-types.index') }}"
                                class="sub-link2 {{ request()->routeIs('master-data.body-types.*') ? 'active' : '' }}">
                                Jenis Karoseri
                            </a>
                        </div>

                    </div>
                </div>

                {{-- Laporan --}}
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
                    <span class="n-label">Laporan</span>
                </a>

                {{-- Armada --}}
                <a href="#" class="nav-link">
                    <span class="n-icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="1" y="3" width="15" height="13" />
                            <polygon points="16 8 20 8 23 11 23 16 16 16 16 8" />
                            <circle cx="5.5" cy="18.5" r="2.5" />
                            <circle cx="18.5" cy="18.5" r="2.5" />
                        </svg>
                    </span>
                    <span class="n-label">Armada</span>
                </a>

                <div class="nav-sep"></div>

                {{-- Pengaturan --}}
                <a href="#" class="nav-link">
                    <span class="n-icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round">
                            <circle cx="12" cy="12" r="3" />
                            <path
                                d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-2 2 2 2 0 01-2-2v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06-.06a2 2 0 01-2.83 0 2 2 0 010-2.83l.06-.06A1.65 1.65 0 004.68 15a1.65 1.65 0 00-1.51-1H3a2 2 0 01-2-2 2 2 0 012-2h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 010-2.83 2 2 0 012.83 0l.06.06A1.65 1.65 0 009 4.68a1.65 1.65 0 001-1.51V3a2 2 0 012-2 2 2 0 012 2v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 0 2 2 0 010 2.83l-.06.06A1.65 1.65 0 0019.4 9a1.65 1.65 0 001.51 1H21a2 2 0 012 2 2 2 0 01-2 2h-.09a1.65 1.65 0 00-1.51 1z" />
                        </svg>
                    </span>
                    <span class="n-label">Pengaturan</span>
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

                    <button class="ic-btn" title="Notifikasi">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round">
                            <path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9" />
                            <path d="M13.73 21a2 2 0 01-3.46 0" />
                        </svg>
                        <span class="notif-dot"></span>
                    </button>

                    <div class="lang-pill">
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
                            <div class="u-role">{{ Auth::user()->role->name ?? 'Pengguna' }}</div>
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
                                        d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-2 2 2 2 0 01-2-2v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06-.06a2 2 0 01-2.83 0 2 2 0 010-2.83l.06-.06A1.65 1.65 0 004.68 15a1.65 1.65 0 00-1.51-1H3a2 2 0 01-2-2 2 2 0 012-2h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 010-2.83 2 2 0 012.83 0l.06.06A1.65 1.65 0 009 4.68a1.65 1.65 0 001-1.51V3a2 2 0 012-2 2 2 2 0 012 2v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 0 2 2 0 010 2.83l-.06.06A1.65 1.65 0 0019.4 9a1.65 1.65 0 001.51 1H21a2 2 0 012 2 2 2 0 01-2 2h-.09a1.65 1.65 0 00-1.51 1z" />
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

    @stack('scripts')
</body>

</html>


#prod to dev