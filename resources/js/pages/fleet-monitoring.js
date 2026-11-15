/**
 * Fleet GPS Monitoring — fleet-monitoring.js
 */

(function () {
    'use strict';

    const mapEl = document.getElementById('gps-map');
    if (!mapEl) return;

    const TOKEN_URL      = mapEl.dataset.tokenUrl;
    const REFRESH_URL    = mapEl.dataset.refreshUrl;
    const ENGINE_URL     = mapEl.dataset.engineUrl;
    const HISTORY_URL    = mapEl.dataset.historyUrl;
    const FLEET_DATA     = JSON.parse(document.getElementById('gps-fleet-data').textContent || '[]');

    let ws             = null;
    let map            = null;
    let pingInterval   = null;
    let reconnectTimer = null;
    let reconnectDelay = 3000;
    let isDestroyed    = false;

    const markers      = {};   // gps_tracker_id → L.marker
    const fleetById    = {};   // gps_tracker_id → fleet object
    const lastAcc      = {};   // gps_tracker_id → last acc value (untuk deteksi perubahan)

    FLEET_DATA.forEach(f => { fleetById[f.gps_tracker_id] = f; });

    // ── Bahasa ───────────────────────────────────────────────────────────────
    function isEn() {
        try { return localStorage.getItem('lang') === 'en'; } catch { return false; }
    }

    const T = {
        engine:         () => isEn() ? 'Engine'              : 'Mesin',
        speed:          () => isEn() ? 'Speed'               : 'Kecepatan',
        time:           () => isEn() ? 'Time'                : 'Waktu',
        location:       () => isEn() ? 'Location'            : 'Lokasi',
        detail:         () => isEn() ? 'Vehicle Detail'      : 'Detail Kendaraan',
        noDriver:       () => isEn() ? 'No driver'           : 'Tanpa sopir',
        loadingHistory: () => isEn() ? 'Loading history...'  : 'Memuat history...',
        noHistory:      () => isEn() ? 'No engine history yet.' : 'Belum ada history mesin.',
        historyTitle:   () => isEn() ? 'Engine History (last 20):' : 'History Mesin (20 terakhir):',
        historyFail:    () => isEn() ? 'Failed to load history.'  : 'Gagal memuat history.',
        connecting:     () => isEn() ? 'Connecting...'                  : 'Menghubungkan...',
        connected:      () => isEn() ? 'Connected (Live)'               : 'Terhubung (Live)',
        disconnected:   () => isEn() ? 'Disconnected — Reconnecting...' : 'Terputus — Reconnecting...',
        gpsError:       () => isEn() ? 'GPS configuration error'        : 'Error konfigurasi GPS',
    };

    // ── Leaflet Map ─────────────────────────────────────────────────────────
    function initMap() {
        map = L.map('gps-map', { zoomControl: true }).setView([-2.5, 118.0], 5);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
            maxZoom: 19,
        }).addTo(map);

        FLEET_DATA.forEach(f => {
            if (f.gps_last_lat && f.gps_last_lng) {
                placeMarker(f.gps_tracker_id, f.gps_last_lat, f.gps_last_lng, f, {
                    speed: f.gps_last_speed, acc: f.gps_last_acc,
                    tdate: f.gps_last_seen,  address: f.gps_last_address,
                });
            }
        });

        const pts = Object.values(markers).map(m => m.getLatLng());
        if (pts.length) map.fitBounds(L.latLngBounds(pts), { padding: [40, 40] });
    }

    // ── Marker ──────────────────────────────────────────────────────────────
    function makeIcon(acc) {
        // Warna solid vivid — kontras di peta terang maupun gelap
        // Background putih + drop shadow supaya icon tidak menyatu dengan peta
        const bak     = acc ? '#22c55e' : '#ef4444';   // green-500 / red-500
        const kabin   = acc ? '#16a34a' : '#dc2626';   // green-600 / red-600
        const outline = acc ? '#14532d' : '#7f1d1d';   // outline gelap = kontras maksimal
        const label   = acc ? '#bbf7d0' : '#fee2e2';   // teks label badge
        const svg = `<svg xmlns="http://www.w3.org/2000/svg" width="64" height="42" viewBox="0 0 64 42">
            <defs>
                <filter id="sh${acc?'1':'0'}" x="-15%" y="-15%" width="130%" height="150%">
                    <feDropShadow dx="0" dy="2" stdDeviation="2" flood-color="rgba(0,0,0,0.5)"/>
                </filter>
            </defs>
            <!-- Background putih — supaya tidak menyatu dgn peta -->
            <rect x="1" y="1" width="58" height="28" rx="4"
                  fill="white" stroke="${outline}" stroke-width="1" opacity="0.92"/>
            <g filter="url(#sh${acc?'1':'0'})">
                <!-- Bak dump truck -->
                <rect x="3" y="4" width="29" height="16" rx="2"
                      fill="${bak}" stroke="${outline}" stroke-width="1.5"/>
                <!-- Hidrolik bak -->
                <line x1="9" y1="20" x2="15" y2="11"
                      stroke="${outline}" stroke-width="2" stroke-linecap="round"/>
                <!-- Kabin -->
                <rect x="32" y="8" width="22" height="12" rx="2"
                      fill="${kabin}" stroke="${outline}" stroke-width="1.5"/>
                <!-- Kaca kabin -->
                <rect x="41" y="10" width="10" height="6" rx="1.5"
                      fill="#bae6fd" stroke="${outline}" stroke-width="0.8"/>
                <!-- Bumper -->
                <rect x="53" y="13" width="4" height="7" rx="1"
                      fill="${kabin}" stroke="${outline}" stroke-width="1"/>
                <!-- Chassis -->
                <rect x="3" y="20" width="52" height="4" rx="1" fill="#334155"/>
                <!-- Lampu depan -->
                <circle cx="56" cy="11" r="2" fill="#fde68a" stroke="${outline}" stroke-width="0.5"/>
            </g>
            <!-- Roda depan -->
            <circle cx="46" cy="30" r="5.5" fill="#1e293b" stroke="#94a3b8" stroke-width="1.5"/>
            <circle cx="46" cy="30" r="2.5"  fill="#64748b"/>
            <!-- Roda belakang 1 -->
            <circle cx="32" cy="30" r="5.5" fill="#1e293b" stroke="#94a3b8" stroke-width="1.5"/>
            <circle cx="32" cy="30" r="2.5"  fill="#64748b"/>
            <!-- Roda belakang 2 -->
            <circle cx="18" cy="30" r="5.5" fill="#1e293b" stroke="#94a3b8" stroke-width="1.5"/>
            <circle cx="18" cy="30" r="2.5"  fill="#64748b"/>
        </svg>`;
        return L.divIcon({ html: svg, className: '', iconSize: [64, 42], iconAnchor: [32, 38], popupAnchor: [0, -40] });
    }

    function popupContent(fleet, frame) {
        const acc   = frame.acc
            ? '<span class="badge bg-success">ON</span>'
            : '<span class="badge bg-danger">OFF</span>';
        const speed = frame.speed !== undefined ? `${frame.speed} km/h` : '-';
        return `<div style="min-width:220px;font-size:13px">
            <strong>${fleet.police_number ?? fleet.vehicle_code}</strong><br>
            <small class="text-muted">${fleet.brand ?? ''} ${fleet.type ?? ''}</small><br>
            <hr class="my-1">
            <b>${T.engine()}:</b> ${acc} &nbsp; <b>${T.speed()}:</b> ${speed}<br>
            <b>${T.time()}:</b> ${frame.tdate ?? '-'}<br>
            <b>${T.location()}:</b> <small>${frame.address ?? '-'}</small><br>
            <a href="${fleet.show_url}" class="btn btn-sm btn-outline-primary mt-1" target="_blank">${T.detail()}</a>
        </div>`;
    }

    function placeMarker(gpsTrackerId, lat, lng, fleet, frame) {
        if (markers[gpsTrackerId]) {
            markers[gpsTrackerId].setLatLng([lat, lng]).setIcon(makeIcon(frame.acc))
                .setPopupContent(popupContent(fleet, frame));
        } else {
            markers[gpsTrackerId] = L.marker([lat, lng], { icon: makeIcon(frame.acc) })
                .bindPopup(popupContent(fleet, frame)).addTo(map);
        }
    }

    // ── Sidebar ──────────────────────────────────────────────────────────────
    function buildSidebarList() {
        const ul = document.getElementById('fleet-list');
        if (!ul) return;

        FLEET_DATA.forEach(f => {
            const li = document.createElement('li');
            li.className = 'list-group-item list-group-item-action px-3 py-2';
            li.dataset.gpsId = f.gps_tracker_id;
            li.innerHTML = `
                <div class="d-flex justify-content-between align-items-start">
                  <div>
                    <strong class="d-block" style="font-size:13px">${f.police_number ?? f.vehicle_code}</strong>
                    <small class="text-muted">${f.driver ?? T.noDriver()}</small>
                  </div>
                  <span class="badge rounded-pill bg-secondary fleet-status" id="status-${f.gps_tracker_id}">—</span>
                </div>
                <small class="text-muted d-block mt-1 fleet-speed" id="speed-${f.gps_tracker_id}"></small>
                <div id="history-${f.gps_tracker_id}" class="mt-1" style="display:none;font-size:11px;max-height:120px;overflow-y:auto"></div>`;

            li.style.cursor = 'pointer';
            li.addEventListener('click', () => {
                const m = markers[f.gps_tracker_id];
                if (m) { map.setView(m.getLatLng(), 15); m.openPopup(); }
                toggleHistory(f.gps_tracker_id);
            });
            ul.appendChild(li);
        });
    }

    function updateSidebarItem(gpsTrackerId, frame) {
        const statusEl = document.getElementById(`status-${gpsTrackerId}`);
        const speedEl  = document.getElementById(`speed-${gpsTrackerId}`);
        if (statusEl) {
            statusEl.textContent = frame.acc ? 'ON' : 'OFF';
            statusEl.className   = `badge rounded-pill ${frame.acc ? 'bg-success' : 'bg-danger'} fleet-status`;
        }
        if (speedEl) {
            speedEl.textContent = `${frame.speed ?? 0} km/h  ·  ${frame.tdate ?? ''}`;
        }
    }

    // ── History panel ────────────────────────────────────────────────────────
    function toggleHistory(gpsTrackerId) {
        const el = document.getElementById(`history-${gpsTrackerId}`);
        if (!el) return;

        if (el.style.display === 'none') {
            el.style.display = 'block';
            loadHistory(gpsTrackerId, el);
        } else {
            el.style.display = 'none';
        }
    }

    function loadHistory(gpsTrackerId, el) {
        el.innerHTML = `<div class="text-muted">${T.loadingHistory()}</div>`;
        const url = HISTORY_URL.replace('__ID__', gpsTrackerId);
        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(r => r.json())
            .then(json => {
                if (!json.success || !json.data.length) {
                    el.innerHTML = `<div class="text-muted fst-italic">${T.noHistory()}</div>`;
                    return;
                }
                el.innerHTML = '<div class="fw-semibold mb-1 text-muted">' + T.historyTitle() + '</div>' +
                    json.data.map(row => {
                        const icon  = row.event === 'on' ? '🟢' : '🔴';
                        const label = row.event === 'on' ? 'ON' : 'OFF';
                        const time  = row.occurred_at ? row.occurred_at.replace('T', ' ').substring(0,16) : '-';
                        return `<div class="d-flex gap-1 py-1 border-bottom">
                            <span>${icon}</span>
                            <span class="fw-semibold ${row.event === 'on' ? 'text-success' : 'text-danger'}">${label}</span>
                            <span class="text-muted">${time}</span>
                            ${row.speed ? `<span class="ms-auto text-muted">${row.speed}km/h</span>` : ''}
                        </div>`;
                    }).join('');
            })
            .catch(() => { el.innerHTML = `<div class="text-danger">${T.historyFail()}</div>`; });
    }

    // ── Engine event logger ──────────────────────────────────────────────────
    function logEngineEvent(gpsTrackerId, event, frame) {
        fetch(ENGINE_URL, {
            method: 'POST',
            headers: {
                'Content-Type':     'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN':     document.querySelector('meta[name="csrf-token"]')?.content ?? '',
            },
            body: JSON.stringify({
                gps_tracker_id: gpsTrackerId,
                event:          event,
                lat:            frame.lat  ?? null,
                lng:            frame.lng  ?? null,
                speed:          frame.speed ?? null,
                occurred_at:    frame.tdate ?? null,
            }),
        }).catch(e => console.warn('[GPS] Gagal log engine event:', e));
    }

    // ── GPS frame handler ────────────────────────────────────────────────────
    function handleGpsFrame(frame) {
        const gpsTrackerId = frame.vh_id ?? frame.id;
        const fleet        = fleetById[gpsTrackerId];
        if (!fleet) return;

        const lat = parseFloat(frame.lat);
        const lng = parseFloat(frame.lng);
        if (!lat || !lng) return;

        // Deteksi perubahan mesin
        const prevAcc = lastAcc[gpsTrackerId];
        const currAcc = frame.acc;
        if (prevAcc !== undefined && prevAcc !== currAcc) {
            const event = currAcc ? 'on' : 'off';
            console.info(`[GPS] Mesin ${event.toUpperCase()} — kendaraan ${gpsTrackerId}`);
            logEngineEvent(gpsTrackerId, event, frame);

            // Refresh history panel kalau sedang terbuka
            const histEl = document.getElementById(`history-${gpsTrackerId}`);
            if (histEl && histEl.style.display !== 'none') {
                setTimeout(() => loadHistory(gpsTrackerId, histEl), 500);
            }
        }
        lastAcc[gpsTrackerId] = currAcc;

        placeMarker(gpsTrackerId, lat, lng, fleet, frame);
        updateSidebarItem(gpsTrackerId, frame);
    }

    // ── WebSocket ────────────────────────────────────────────────────────────
    async function connect() {
        if (isDestroyed) return;
        setConnectionStatus('connecting');

        let tokenData;
        try {
            const res = await fetch(TOKEN_URL, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            tokenData = await res.json();
        } catch (e) {
            console.warn('[GPS] Gagal fetch token:', e);
            scheduleReconnect();
            return;
        }

        if (!tokenData.success || !tokenData.token) {
            console.warn('[GPS] Token tidak tersedia:', tokenData.message);
            setConnectionStatus('error');
            return;
        }

        const wsUrl = `${tokenData.ws_url}/ws/monitoring?token=${tokenData.token}`;
        console.info('[GPS] Connecting to', wsUrl);
        ws = new WebSocket(wsUrl);

        ws.addEventListener('open', () => {
            console.info('[GPS] WebSocket connected');
            setConnectionStatus('connected');
            reconnectDelay = 3000;
            pingInterval = setInterval(() => {
                if (ws?.readyState === WebSocket.OPEN) ws.send(JSON.stringify({ type: 'ping' }));
            }, 30_000);
        });

        ws.addEventListener('message', (event) => {
            let frame;
            try { frame = JSON.parse(event.data); } catch { return; }
            switch (frame.type) {
                case 'gps':       handleGpsFrame(frame); break;
                case 'pong':      break;
                case 'remainder': console.info('[GPS] Remainder:', frame); break;
                default:          console.debug('[GPS] Frame:', frame.type);
            }
        });

        ws.addEventListener('close', (event) => {
            console.warn('[GPS] WebSocket closed', event.code);
            clearInterval(pingInterval);
            setConnectionStatus('disconnected');
            if (event.code === 1008 || event.code === 4001) {
                fetch(REFRESH_URL, {
                    method: 'POST',
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? '' },
                }).finally(() => scheduleReconnect());
            } else {
                scheduleReconnect();
            }
        });

        ws.addEventListener('error', (e) => console.error('[GPS] WS error', e));
    }

    function scheduleReconnect() {
        if (isDestroyed) return;
        reconnectTimer = setTimeout(() => { reconnectDelay = Math.min(reconnectDelay * 2, 60_000); connect(); }, reconnectDelay);
    }

    function setConnectionStatus(status) {
        const el = document.getElementById('gps-connection-status');
        if (!el) return;
        const statusMap = {
            connecting:   [T.connecting,   'text-warning'],
            connected:    [T.connected,    'text-success'],
            disconnected: [T.disconnected, 'text-danger'],
            error:        [T.gpsError,     'text-danger'],
        };
        const [textFn, cls] = statusMap[status] ?? [() => '—', 'text-muted'];
        el.textContent = textFn();
        el.className   = `fw-semibold ${cls}`;
    }

    // ── Boot ─────────────────────────────────────────────────────────────────
    initMap();
    buildSidebarList();
    connect();

    window.addEventListener('beforeunload', () => {
        isDestroyed = true;
        clearInterval(pingInterval);
        clearTimeout(reconnectTimer);
        ws?.close();
    });

})();
