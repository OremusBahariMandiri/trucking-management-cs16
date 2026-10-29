<?php

namespace App\Http\Controllers\DataManagement\FleetManagement;

use App\Http\Controllers\Controller;
use App\Services\DataManagement\FleetManagement\GpsTrackerService;
use Illuminate\Http\JsonResponse;
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
                '_debug'  => [
                    'api_key_set' => ! empty(config('services.gps.api_key')),
                    'ws_url_set'  => ! empty(config('services.gps.ws_url')),
                ],
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
            return response()->json([
                'success' => false,
                'message' => 'Gagal refresh token GPS.',
            ], 503);
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
}
