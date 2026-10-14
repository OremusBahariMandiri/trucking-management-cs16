<?php

namespace App\Actions\DataManagement\FleetManagement;

use App\Models\DataManagement\FleetManagement\Fleet;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CreateFleet
{
    public function handle(array $data, array $photos = []): Fleet
    {
        return DB::transaction(function () use ($data, $photos) {

            $fleet = Fleet::create([
                ...$data,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ]);

            if (!empty($photos)) {
                $this->storePhotos($fleet, $photos);
            }

            return $fleet;
        });
    }

    private function storePhotos(Fleet $fleet, array $photos): void
    {
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
                'sort_order'   => $index,
                'is_primary'   => $index === 0,
                'uploaded_by'  => Auth::id(),
            ]);
        }
    }
}