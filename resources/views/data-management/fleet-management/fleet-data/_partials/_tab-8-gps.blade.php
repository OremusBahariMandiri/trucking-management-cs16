{{--
    Tab 8 — GPS Tracking
    Partial: resources/views/data-management/fleet-management/fleet-data/_partials/_tab-8-gps.blade.php
    Dipasang di: show.blade.php (tab ke-8)
--}}

@if (! $fleet->gps_tracker_id)
    <div class="alert alert-secondary d-flex align-items-center gap-3 mt-3">
        <i class="bi bi-geo-alt fs-3 text-muted"></i>
        <div>
            <strong data-en="GPS Not Installed">GPS Belum Terpasang</strong><br>
            <small class="text-muted">
                <span data-en="This vehicle does not have a GPS tracker. Enter the">Kendaraan ini belum memiliki GPS tracker. Masukkan</span>
                <code>GPS Tracker ID</code>
                <span data-en="in the Operational Tab to activate this feature.">di Tab Operasional untuk mengaktifkan fitur ini.</span>
            </small>
        </div>
    </div>
@else
    @push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" integrity="sha512-h9FcoyWjHcOcmEVkxOfTLnmZFWIH0iZhZT1H2TbOq55xssQGEJHEaIm+PgoUaZbRvQTNTLSeB68ARYJipY1SQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        #tab8-gps-map {
            height: 360px;
            border-radius: 8px;
            z-index: 0;
        }

        /* ── GPS KPI Cards — dark mode aware ─────────────────────── */
        .gps-kpi-card {
            background-color: var(--surface-2, #f8f9fa);
            border: 1px solid var(--border, #dee2e6);
            border-radius: 8px;
            text-align: center;
            padding: 1rem 0.5rem;
            transition: background-color .2s, border-color .2s;
        }
        .gps-kpi-value {
            font-weight: 700;
            color: var(--text, #212529);
        }
        .gps-kpi-label {
            font-size: .75rem;
            color: var(--text-muted, #6c757d);
            margin-top: .25rem;
        }

        /* ── Status badge dark mode ──────────────────────────────── */
        .gps-info-row code {
            background-color: var(--surface-2, #f8f9fa);
            color: var(--text, #212529);
            border: 1px solid var(--border, #dee2e6);
            padding: .1em .4em;
            border-radius: 4px;
            font-size: .85em;
        }
        .gps-info-row .text-muted {
            color: var(--text-muted, #6c757d) !important;
        }
    </style>
    @endpush

    {{-- Status GPS --}}
    <div class="row g-3 mt-1">
        <div class="col-12">
            <div class="d-flex align-items-center gap-2 mb-3 gps-info-row">
                @php
                    $gpsStatusMap = [
                        'active'        => ['Aktif',           'Active',        'success'],
                        'offline'       => ['Offline',         'Offline',       'secondary'],
                        'not_installed' => ['Tidak Terpasang', 'Not Installed', 'secondary'],
                    ];
                    $gpsStatusData = $gpsStatusMap[$fleet->gps_status] ?? ['—', '—', 'secondary'];
                @endphp
                <span class="badge bg-{{ $gpsStatusData[2] }}"
                      data-en="{{ $gpsStatusData[1] }}">{{ $gpsStatusData[0] }}</span>
                <span class="text-muted small">
                    <span data-en="Tracker ID:">Tracker ID:</span>
                    <code>{{ $fleet->gps_tracker_id }}</code>
                </span>
                @if ($fleet->gps_provider)
                    <span class="text-muted small">
                        <span data-en="Provider:">Provider:</span>
                        {{ $fleet->gps_provider }}
                    </span>
                @endif
            </div>
        </div>

        {{-- KPI Cards --}}
        <div class="col-6 col-md-3">
            <div class="gps-kpi-card">
                <div class="gps-kpi-value fs-3 text-primary" id="gps8-speed">
                    {{ $fleet->gps_last_speed !== null ? $fleet->gps_last_speed . ' km/h' : '—' }}
                </div>
                <div class="gps-kpi-label" data-en="Speed">Kecepatan</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="gps-kpi-card">
                <div class="gps-kpi-value fs-4" id="gps8-acc">
                    @if ($fleet->gps_last_acc === null)
                        <span class="text-muted">—</span>
                    @elseif ($fleet->gps_last_acc)
                        <span class="text-success">ON</span>
                    @else
                        <span class="text-danger">OFF</span>
                    @endif
                </div>
                <div class="gps-kpi-label" data-en="Engine (ACC)">Mesin (ACC)</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="gps-kpi-card">
                <div class="gps-kpi-value fs-6" id="gps8-seen">
                    {{ $fleet->gps_last_seen ? $fleet->gps_last_seen->diffForHumans() : '—' }}
                </div>
                <div class="gps-kpi-label" data-en="Last Update">Terakhir Update</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="gps-kpi-card">
                <div class="gps-kpi-value" style="font-size:11px!important" id="gps8-coord">
                    @if ($fleet->gps_last_lat && $fleet->gps_last_lng)
                        {{ number_format($fleet->gps_last_lat, 5) }},
                        {{ number_format($fleet->gps_last_lng, 5) }}
                    @else
                        —
                    @endif
                </div>
                <div class="gps-kpi-label" data-en="Coordinates">Koordinat</div>
            </div>
        </div>

        {{-- Mini Peta --}}
        <div class="col-12">
            <div id="tab8-gps-map"
                data-lat="{{ $fleet->gps_last_lat ?? '' }}"
                data-lng="{{ $fleet->gps_last_lng ?? '' }}"
                data-label="{{ $fleet->police_number ?? $fleet->vehicle_code }}"
                data-token-url="{{ route('data-management.gps-monitoring.api.ws-token') }}"
                data-refresh-url="{{ route('data-management.gps-monitoring.api.ws-token.refresh') }}"
                data-gps-id="{{ $fleet->gps_tracker_id }}">
            </div>
        </div>

        {{-- Alamat terakhir --}}
        @if ($fleet->gps_last_address)
        <div class="col-12">
            <small class="text-muted">
                <i class="bi bi-geo-alt"></i>
                {{ $fleet->gps_last_address }}
            </small>
        </div>
        @endif

        {{-- Link ke monitoring penuh --}}
        <div class="col-12 text-end">
            <a href="{{ route('data-management.gps-monitoring.index') }}" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-broadcast"></i>
                <span data-en="Open Full Monitoring">Buka Monitoring Penuh</span>
            </a>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js" integrity="sha512-puJW3E/qXDqYp9IfhAI54BJEaWIfloJ7JWoFqsFR3wbgemXa2ArikE2oFojtaWFMCCget6CjpJFBqo9mLbyCGQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
    (function() {
        const el = document.getElementById('tab8-gps-map');
        if (!el) return;

        const lat    = parseFloat(el.dataset.lat);
        const lng    = parseFloat(el.dataset.lng);
        const label  = el.dataset.label;
        const gpsId  = parseInt(el.dataset.gpsId);
        const tokenUrl   = el.dataset.tokenUrl;
        const refreshUrl = el.dataset.refreshUrl;

        /* ── Deteksi dark mode (sinkron dgn sistem tema app) ─────── */
        function isDark() {
            const t = document.documentElement.getAttribute('data-theme');
            if (t === 'dark')  return true;
            if (t === 'light') return false;
            // 'system' atau tidak ada atribut → ikuti prefers-color-scheme
            return window.matchMedia('(prefers-color-scheme: dark)').matches;
        }

        const TILE_LIGHT = 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
        const TILE_DARK  = 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png';
        const ATTR_LIGHT = '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors';
        const ATTR_DARK  = '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors © <a href="https://carto.com/">CARTO</a>';

        // Init map
        const defaultLat  = (!isNaN(lat) && lat) ? lat : -2.5;
        const defaultLng  = (!isNaN(lng) && lng) ? lng : 118.0;
        const defaultZoom = (!isNaN(lat) && lat) ? 14 : 5;

        const map = L.map('tab8-gps-map').setView([defaultLat, defaultLng], defaultZoom);

        let tileLayer = L.tileLayer(isDark() ? TILE_DARK : TILE_LIGHT, {
            attribution: isDark() ? ATTR_DARK : ATTR_LIGHT,
            maxZoom: 19,
        }).addTo(map);

        /* ── Ganti tile saat tema berubah ────────────────────────── */
        function refreshTile() {
            const dark = isDark();
            map.removeLayer(tileLayer);
            tileLayer = L.tileLayer(dark ? TILE_DARK : TILE_LIGHT, {
                attribution: dark ? ATTR_DARK : ATTR_LIGHT,
                maxZoom: 19,
            }).addTo(map);
        }

        // Observer pada atribut data-theme di <html>
        const themeObserver = new MutationObserver(refreshTile);
        themeObserver.observe(document.documentElement, { attributes: true, attributeFilter: ['data-theme'] });

        // Fallback: system preference berubah
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
            if (!document.documentElement.hasAttribute('data-theme')) refreshTile();
        });

        let marker = null;
        if (!isNaN(lat) && lat && !isNaN(lng) && lng) {
            marker = L.marker([lat, lng]).addTo(map).bindPopup(label).openPopup();
        }

        /* ── WebSocket real-time ─────────────────────────────────── */
        let ws = null;
        let pingInterval = null;

        async function connectWs() {
            try {
                const res  = await fetch(tokenUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                const data = await res.json();
                if (!data.success || !data.token) return;

                ws = new WebSocket(`${data.ws_url}/ws?token=${data.token}`);

                ws.onopen = () => {
                    pingInterval = setInterval(() => {
                        if (ws.readyState === WebSocket.OPEN) ws.send(JSON.stringify({type:'ping'}));
                    }, 30000);
                };

                ws.onmessage = (event) => {
                    let frame;
                    try { frame = JSON.parse(event.data); } catch { return; }
                    if (frame.type !== 'gps') return;
                    if ((frame.vh_id ?? frame.id) !== gpsId) return;

                    const nlat = parseFloat(frame.lat);
                    const nlng = parseFloat(frame.lng);
                    if (!nlat || !nlng) return;

                    if (marker) {
                        marker.setLatLng([nlat, nlng]);
                        map.setView([nlat, nlng]);
                    } else {
                        marker = L.marker([nlat, nlng]).addTo(map).bindPopup(label);
                        map.setView([nlat, nlng], 14);
                    }

                    // Update KPI
                    const speedEl = document.getElementById('gps8-speed');
                    const accEl   = document.getElementById('gps8-acc');
                    const seenEl  = document.getElementById('gps8-seen');
                    const coordEl = document.getElementById('gps8-coord');
                    if (speedEl) speedEl.textContent = (frame.speed ?? 0) + ' km/h';
                    if (accEl)   accEl.innerHTML = frame.acc
                        ? '<span class="text-success">ON</span>'
                        : '<span class="text-danger">OFF</span>';
                    if (seenEl)  seenEl.textContent = frame.tdate ?? (document.documentElement.lang === 'en' ? 'Just now' : 'Baru saja');
                    if (coordEl) coordEl.textContent = `${nlat.toFixed(5)}, ${nlng.toFixed(5)}`;
                };

                ws.onclose = () => { clearInterval(pingInterval); setTimeout(connectWs, 10000); };

            } catch(e) {
                console.warn('[GPS Tab8] Gagal connect WS:', e);
            }
        }

        connectWs();
    })();
    </script>
    @endpush
@endif
