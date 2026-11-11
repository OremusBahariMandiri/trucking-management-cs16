<?php

namespace App\Http\Controllers\DataManagement\FleetManagement;

use App\Http\Controllers\Controller;
use App\Models\DataManagement\FleetManagement\VehicleEngineLog;
use App\Models\DataManagement\FleetManagement\Fleet;
use App\Services\DataManagement\FleetManagement\GpsTrackerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GpsMonitoringController extends Controller
{
    public function __construct(
        protected GpsTrackerService $gpsService,
    ) {}

    public function index(): View
    {
        $fleets    = $this->gpsService->getFleetWithGps();
        $wsBaseUrl = $this->gpsService->getWsBaseUrl();

        return view('data-management.fleet-management.monitoring.index', compact('fleets', 'wsBaseUrl'));
    }

    public function wsToken(): JsonResponse
    {
        $token = $this->gpsService->getWsToken();

        if (! $token) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mendapatkan token GPS. Cek konfigurasi GPS_API_KEY.',
            ], 503);
        }

        return response()->json([
            'success' => true,
            'token'   => $token,
            'ws_url'  => $this->gpsService->getWsBaseUrl(),
        ]);
    }

    public function refreshToken(): JsonResponse
    {
        $token = $this->gpsService->refreshWsToken();

        if (! $token) {
            return response()->json(['success' => false, 'message' => 'Gagal refresh token GPS.'], 503);
        }

        return response()->json([
            'success' => true,
            'token'   => $token,
            'ws_url'  => $this->gpsService->getWsBaseUrl(),
        ]);
    }

    public function positions(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => $this->gpsService->getFleetWithGps(),
        ]);
    }

    /**
     * POST /api/engine-event
     * Dipanggil JS saat deteksi perubahan acc (mesin on/off).
     */
    public function logEngineEvent(Request $request): JsonResponse
    {
        $data = $request->validate([
            'gps_tracker_id' => 'required',
            'event'          => 'required|in:on,off',
            'lat'            => 'nullable|numeric',
            'lng'            => 'nullable|numeric',
            'speed'          => 'nullable|integer',
            'occurred_at'    => 'nullable|date',
        ]);

        // Cari vehicle_id
        $fleet = Fleet::where('gps_tracker_id', $data['gps_tracker_id'])->first();

        VehicleEngineLog::create([
            'vehicle_id'     => $fleet?->id,
            'gps_tracker_id' => $data['gps_tracker_id'],
            'event'          => $data['event'],
            'lat'            => $data['lat'] ?? null,
            'lng'            => $data['lng'] ?? null,
            'speed'          => $data['speed'] ?? null,
            'occurred_at'    => $data['occurred_at'] ?? now(),
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * GET /api/engine-history/{gpsTrackerId}
     * Ambil 20 history terakhir mesin untuk kendaraan tertentu.
     */
    public function engineHistory(string $gpsTrackerId): JsonResponse
    {
        $logs = VehicleEngineLog::where('gps_tracker_id', $gpsTrackerId)
            ->orderByDesc('occurred_at')
            ->limit(20)
            ->get(['event', 'lat', 'lng', 'speed', 'occurred_at']);

        return response()->json(['success' => true, 'data' => $logs]);
    }
}
