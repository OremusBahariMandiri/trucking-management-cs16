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
        const color = acc ? '#16a34a' : '#dc2626';
        const svg = `<svg xmlns="http://www.w3.org/2000/svg" width="28" height="36" viewBox="0 0 28 36">
            <path d="M14 0C6.27 0 0 6.27 0 14c0 10.5 14 22 14 22S28 24.5 28 14C28 6.27 21.73 0 14 0z" fill="${color}"/>
            <circle cx="14" cy="14" r="6" fill="white"/>
        </svg>`;
        return L.divIcon({ html: svg, className: '', iconSize: [28,36], iconAnchor: [14,36], popupAnchor: [0,-36] });
    }

    function popupContent(fleet, frame) {
        const acc   = frame.acc ? '<span class="badge bg-success">ON</span>' : '<span class="badge bg-danger">OFF</span>';
        const speed = frame.speed !== undefined ? `${frame.speed} km/h` : '-';
        return `<div style="min-width:220px;font-size:13px">
            <strong>${fleet.police_number ?? fleet.vehicle_code}</strong><br>
            <small class="text-muted">${fleet.brand ?? ''} ${fleet.type ?? ''}</small><br>
            <hr class="my-1">
            <b>Mesin:</b> ${acc} &nbsp; <b>Kecepatan:</b> ${speed}<br>
            <b>Waktu:</b> ${frame.tdate ?? '-'}<br>
            <b>Lokasi:</b> <small>${frame.address ?? '-'}</small><br>
            <a href="${fleet.show_url}" class="btn btn-sm btn-outline-primary mt-1" target="_blank">Detail Kendaraan</a>
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
                    <small class="text-muted">${f.driver ?? 'Tanpa sopir'}</small>
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
        el.innerHTML = '<div class="text-muted">Memuat history...</div>';
        const url = HISTORY_URL.replace('__ID__', gpsTrackerId);
        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(r => r.json())
            .then(json => {
                if (!json.success || !json.data.length) {
                    el.innerHTML = '<div class="text-muted fst-italic">Belum ada history mesin.</div>';
                    return;
                }
                el.innerHTML = '<div class="fw-semibold mb-1 text-muted">History Mesin (20 terakhir):</div>' +
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
            .catch(() => { el.innerHTML = '<div class="text-danger">Gagal memuat history.</div>'; });
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
        const map = {
            connecting:   ['Menghubungkan...', 'text-warning'],
            connected:    ['Terhubung (Live)', 'text-success'],
            disconnected: ['Terputus — Reconnecting...', 'text-danger'],
            error:        ['Error konfigurasi GPS', 'text-danger'],
        };
        const [text, cls] = map[status] ?? ['—', 'text-muted'];
        el.textContent = text;
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
