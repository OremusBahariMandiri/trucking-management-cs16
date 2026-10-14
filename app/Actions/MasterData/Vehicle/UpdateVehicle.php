<?php

namespace App\Actions\MasterData\Vehicle;

use App\Models\MasterData\Vehicle\Vehicle;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UpdateVehicle
{
    public function handle(Vehicle $vehicle, array $data, array $photos = []): Vehicle
    {
        return DB::transaction(function () use ($vehicle, $data, $photos) {

            $vehicle->update([
                ...$data,
                'updated_by' => Auth::id(),
            ]);

            // Tambah foto baru jika ada
            if (!empty($photos)) {
                $this->storePhotos($vehicle, $photos);
            }

            return $vehicle->fresh();
        });
    }

    private function storePhotos(Vehicle $vehicle, array $photos): void
    {
        $lastOrder = $vehicle->photos()->max('sort_order') ?? -1;

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
                'sort_order'   => $lastOrder + $index + 1,
                'is_primary'   => false,
                'uploaded_by'  => Auth::id(),
            ]);
        }
    }

    public function deletePhoto(Vehicle $vehicle, int $photoId): void
    {
        $photo = $vehicle->photos()->findOrFail($photoId);

        // Hapus file dari storage
        if ($photo->file_path) {
            Storage::disk('public')->delete($photo->file_path);
        }

        $wasPrimary = $photo->is_primary;
        $photo->delete();

        // Jika foto yang dihapus adalah foto utama, jadikan foto pertama tersisa sebagai utama
        if ($wasPrimary) {
            $vehicle->photos()->orderBy('sort_order')->first()?->update(['is_primary' => true]);
        }
    }

    public function setPrimaryPhoto(Vehicle $vehicle, int $photoId): void
    {
        $vehicle->photos()->update(['is_primary' => false]);
        $vehicle->photos()->where('id', $photoId)->update(['is_primary' => true]);
    }
}