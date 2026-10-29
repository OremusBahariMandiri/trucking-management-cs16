{{--
    Tab 8 — GPS Tracking
    Partial: resources/views/data-management/fleet-management/fleet-data/_partials/_tab-8-gps.blade.php
    Dipasang di: show.blade.php (tab ke-8)
--}}

@if (! $fleet->gps_tracker_id)
    <div class="alert alert-secondary d-flex align-items-center gap-3 mt-3">
        <i class="bi bi-geo-alt fs-3 text-muted"></i>
        <div>
            <strong>GPS Belum Terpasang</strong><br>
            <small class="text-muted">Kendaraan ini belum memiliki GPS tracker. Masukkan <code>GPS Tracker ID</code> di Tab Operasional untuk mengaktifkan fitur ini.</small>
        </div>
    </div>
@else
    @push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" integrity="sha512-h9FcoyWjHcOcmEVkxOfTLnmZFWIH0iZhZT1H2TbOq55xssQGEJHEaIm+PgoUaZbRvQTNTLSeB68ARYJipY1SQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        #tab8-gps-map { height: 360px; border-radius: 8px; z-index: 0; }
    </style>
    @endpush

    {{-- Status GPS --}}
    <div class="row g-3 mt-1">
        <div class="col-12">
            <div class="d-flex align-items-center gap-2 mb-3">
                @php
                    $gpsStatusLabel = [
                        'active'        => ['Aktif', 'success'],
                        'offline'       => ['Offline', 'secondary'],
                        'not_installed' => ['Tidak Terpasang', 'secondary'],
                    ][$fleet->gps_status] ?? ['—', 'secondary'];
                @endphp
                <span class="badge bg-{{ $gpsStatusLabel[1] }}">{{ $gpsStatusLabel[0] }}</span>
                <span class="text-muted small">Tracker ID: <code>{{ $fleet->gps_tracker_id }}</code></span>
                @if ($fleet->gps_provider)
                    <span class="text-muted small">Provider: {{ $fleet->gps_provider }}</span>
                @endif
            </div>
        </div>

        {{-- KPI singkat --}}
        <div class="col-6 col-md-3">
            <div class="card border-0 bg-light text-center py-3">
                <div class="fs-3 fw-bold text-primary" id="gps8-speed">
                    {{ $fleet->gps_last_speed !== null ? $fleet->gps_last_speed . ' km/h' : '—' }}
                </div>
                <div class="small text-muted">Kecepatan</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 bg-light text-center py-3">
                <div class="fs-4 fw-bold" id="gps8-acc">
                    @if ($fleet->gps_last_acc === null)
                        <span class="text-muted">—</span>
                    @elseif ($fleet->gps_last_acc)
                        <span class="text-success">ON</span>
                    @else
                        <span class="text-danger">OFF</span>
                    @endif
                </div>
                <div class="small text-muted">Mesin (ACC)</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 bg-light text-center py-3">
                <div class="fs-6 fw-semibold text-dark" id="gps8-seen">
                    {{ $fleet->gps_last_seen ? $fleet->gps_last_seen->diffForHumans() : '—' }}
                </div>
                <div class="small text-muted">Terakhir Update</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 bg-light text-center py-3">
                <div class="fs-6 fw-semibold text-dark" style="font-size:11px!important" id="gps8-coord">
                    @if ($fleet->gps_last_lat && $fleet->gps_last_lng)
                        {{ number_format($fleet->gps_last_lat, 5) }},
                        {{ number_format($fleet->gps_last_lng, 5) }}
                    @else
                        —
                    @endif
                </div>
                <div class="small text-muted">Koordinat</div>
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
            <small class="text-muted"><i class="bi bi-geo-alt"></i> {{ $fleet->gps_last_address }}</small>
        </div>
        @endif

        {{-- Link ke monitoring penuh --}}
        <div class="col-12 text-end">
            <a href="{{ route('data-management.gps-monitoring.index') }}" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-broadcast"></i> Buka Monitoring Penuh
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

        // Init map
        const defaultLat = (!isNaN(lat) && lat) ? lat : -2.5;
        const defaultLng = (!isNaN(lng) && lng) ? lng : 118.0;
        const defaultZoom = (!isNaN(lat) && lat) ? 14 : 5;

        const map = L.map('tab8-gps-map').setView([defaultLat, defaultLng], defaultZoom);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 19,
        }).addTo(map);

        let marker = null;
        if (!isNaN(lat) && lat && !isNaN(lng) && lng) {
            marker = L.marker([lat, lng]).addTo(map).bindPopup(label).openPopup();
        }

        // Sambungkan ke WebSocket untuk update real-time
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
                    if (seenEl)  seenEl.textContent = frame.tdate ?? 'Baru saja';
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
