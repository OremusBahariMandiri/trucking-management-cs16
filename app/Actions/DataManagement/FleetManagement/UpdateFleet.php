<?php

namespace App\Actions\DataManagement\FleetManagement;

use App\Models\DataManagement\FleetManagement\Fleet;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UpdateFleet
{
    public function handle(Fleet $fleet, array $data, array $photos = []): Fleet
    {
        return DB::transaction(function () use ($fleet, $data, $photos) {

            $fleet->update([
                ...$data,
                'updated_by' => Auth::id(),
            ]);

            if (!empty($photos)) {
                $this->storePhotos($fleet, $photos);
            }

            return $fleet->fresh();
        });
    }

    private function storePhotos(Fleet $fleet, array $photos): void
    {
        $lastOrder = $fleet->photos()->max('sort_order') ?? -1;

        foreach ($photos as $index => $photo) {
            /** @var UploadedFile $file */
            $file = $photo['file'];

            $path = $file->store("vehicles/{$fleet->id}/photos", 'public');

            $fleet->photos()->create([
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

    public function deletePhoto(Fleet $fleet, int $photoId): void
    {
        $photo = $fleet->photos()->findOrFail($photoId);

        if ($photo->file_path) {
            Storage::disk('public')->delete($photo->file_path);
        }

        $wasPrimary = $photo->is_primary;
        $photo->delete();

        if ($wasPrimary) {
            $fleet->photos()->orderBy('sort_order')->first()?->update(['is_primary' => true]);
        }
    }

    public function setPrimaryPhoto(Fleet $fleet, int $photoId): void
    {
        $fleet->photos()->update(['is_primary' => false]);
        $fleet->photos()->where('id', $photoId)->update(['is_primary' => true]);
    }
}