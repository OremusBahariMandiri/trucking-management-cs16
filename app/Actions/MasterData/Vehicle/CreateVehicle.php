<?php

namespace App\Actions\MasterData\Vehicle;

use App\Models\MasterData\Vehicle\Vehicle;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CreateVehicle
{
    public function handle(array $data, array $photos = []): Vehicle
    {
        return DB::transaction(function () use ($data, $photos) {

            $vehicle = Vehicle::create([
                ...$data,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ]);

            // Simpan foto jika ada
            if (!empty($photos)) {
                $this->storePhotos($vehicle, $photos);
            }

            return $vehicle;
        });
    }

    private function storePhotos(Vehicle $vehicle, array $photos): void
    {
        foreach ($photos as $index => $photo) {
            /** @var UploadedFile $file */
            $file = $photo['file'];

            $path = $file->store("vehicles/{$vehicle->id}/photos", 'public');

            $vehicle->photos()->create([
                'category'     => $photo['category'] ?? 'lainnya',
                'caption'      => $photo['caption'] ?? null,
                'file_path'    => $path,
                'file_name'    => $file->getClientOriginalName(),
                'file_size_kb' => (int) round($file->getSize() / 1024),
                'sort_order'   => $index,
                'is_primary'   => $index === 0, // foto pertama jadi foto utama
                'uploaded_by'  => Auth::id(),
            ]);
        }
    }
}