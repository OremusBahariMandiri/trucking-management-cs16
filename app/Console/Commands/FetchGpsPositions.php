<?php

namespace App\Console\Commands;

use App\Services\DataManagement\FleetManagement\GpsTrackerService;
use Illuminate\Console\Command;

class FetchGpsPositions extends Command
{
    protected $signature   = 'gps:fetch-positions {--verbose : Tampilkan detail tiap kendaraan}';
    protected $description = 'Ambil posisi GPS via REST API dan simpan ke database (backup polling).';

    public function __construct(
        protected GpsTrackerService $gpsService,
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $this->info('[GPS] Mengambil posisi kendaraan via REST API...');

        $positions = $this->gpsService->fetchPositionsFromApi();

        if (empty($positions)) {
            $this->warn('[GPS] Tidak ada data posisi dari API.');
            return self::FAILURE;
        }

        $updated = 0;

        foreach ($positions as $pos) {
            // vh_id / id = gps_tracker_id di model Fleet kita
            $gpsTrackerId = $pos['vh_id'] ?? $pos['id'] ?? null;

            if (! $gpsTrackerId) {
                continue;
            }

            $this->gpsService->updateLastPosition((int) $gpsTrackerId, $pos);
            $updated++;

            if ($this->option('verbose')) {
                $this->line(sprintf(
                    '  → #%s %s | lat: %s, lng: %s | speed: %s km/h | acc: %s',
                    $gpsTrackerId,
                    $pos['nopol'] ?? '-',
                    $pos['lat']   ?? '-',
                    $pos['lng']   ?? '-',
                    $pos['speed'] ?? '-',
                    $pos['acc'] ? 'ON' : 'OFF',
                ));
            }
        }

        $this->info("[GPS] Selesai. {$updated} kendaraan diperbarui.");

        return self::SUCCESS;
    }
}
