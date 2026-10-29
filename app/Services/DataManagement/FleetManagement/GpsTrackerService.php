<?php

namespace App\Services\DataManagement\FleetManagement;

use App\Models\DataManagement\FleetManagement\Fleet;

class GpsTrackerService
{
    /**
     * Ambil WebSocket token.
     * GPS_API_KEY di .env sudah berupa Bearer token langsung dari provider.
     */
    public function getWsToken(): ?string
    {
        $token = config('services.gps.api_key');
        return $token ?: null;
    }

    public function refreshWsToken(): ?string
    {
        return $this->getWsToken();
    }

    public function getWsBaseUrl(): string
    {
        return rtrim(config('services.gps.ws_url', ''), '/');
    }

    public function getFleetWithGps(): \Illuminate\Support\Collection
    {
        return Fleet::query()
            ->whereNotNull('gps_tracker_id')
            ->where('gps_status', 'active')
            ->with(['brand', 'type', 'driver:id,name'])
            ->get()
            ->map(fn (Fleet $f) => [
                'id'               => $f->id,
                'gps_tracker_id'   => (int) $f->gps_tracker_id,
                'vehicle_code'     => $f->vehicle_code,
                'police_number'    => $f->police_number,
                'brand'            => $f->brand?->name,
                'type'             => $f->type?->name,
                'driver'           => $f->driver?->name,
                'gps_last_lat'     => $f->gps_last_lat ? (float) $f->gps_last_lat : null,
                'gps_last_lng'     => $f->gps_last_lng ? (float) $f->gps_last_lng : null,
                'gps_last_speed'   => $f->gps_last_speed,
                'gps_last_acc'     => $f->gps_last_acc,
                'gps_last_seen'    => $f->gps_last_seen?->toDateTimeString(),
                'gps_last_address' => $f->gps_last_address,
                'show_url'         => route('data-management.fleet-data.show', $f->id),
            ]);
    }

    public function updateLastPosition(int $gpsTrackerId, array $position): void
    {
        Fleet::where('gps_tracker_id', $gpsTrackerId)
            ->update([
                'gps_last_lat'     => $position['lat']     ?? null,
                'gps_last_lng'     => $position['lng']     ?? null,
                'gps_last_speed'   => isset($position['speed']) ? (int) $position['speed'] : null,
                'gps_last_acc'     => isset($position['acc'])   ? (int) $position['acc']   : null,
                'gps_last_seen'    => $position['tdate']   ?? now()->toDateTimeString(),
                'gps_last_address' => $position['address'] ?? null,
            ]);
    }
}
