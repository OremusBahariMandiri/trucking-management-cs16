<?php

namespace App\Actions\MasterData\Vehicle;

use App\Models\MasterData\Vehicle\Vehicle;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DeleteVehicle
{
    /**
     * Soft delete — data tetap ada di DB, bisa di-restore.
     */
    public function handle(Vehicle $vehicle): void
    {
        DB::transaction(function () use ($vehicle) {
            $vehicle->update(['updated_by' => Auth::id()]);
            $vehicle->delete();
        });
    }

    /**
     * Hard delete — hapus permanen beserta semua file foto.
     * Gunakan hanya dari fitur admin khusus.
     */
    public function forceDelete(Vehicle $vehicle): void
    {
        DB::transaction(function () use ($vehicle) {
            // Hapus semua file foto dari storage
            foreach ($vehicle->photos as $photo) {
                if ($photo->file_path) {
                    Storage::disk('public')->delete($photo->file_path);
                }
            }

            // Hapus semua file dokumen dari storage
            foreach ($vehicle->documents as $document) {
                if ($document->file_path) {
                    Storage::disk('public')->delete($document->file_path);
                }
            }

            $vehicle->forceDelete();
        });
    }

    /**
     * Restore data yang sudah di-soft delete.
     */
    public function restore(int $vehicleId): Vehicle
    {
        $vehicle = Vehicle::withTrashed()->findOrFail($vehicleId);
        $vehicle->restore();
        $vehicle->update(['updated_by' => Auth::id()]);

        return $vehicle;
    }
}