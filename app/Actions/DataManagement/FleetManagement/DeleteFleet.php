<?php

namespace App\Actions\DataManagement\FleetManagement;

use App\Models\DataManagement\FleetManagement\Fleet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DeleteFLeet
{
    public function handle(Fleet $fleet): void
    {
        DB::transaction(function () use ($fleet) {
            $fleet->update(['updated_by' => Auth::id()]);
            $fleet->delete();
        });
    }

    public function forceDelete(Fleet $fleet): void
    {
        DB::transaction(function () use ($fleet) {
            foreach ($fleet->photos as $photo) {
                if ($photo->file_path) {
                    Storage::disk('public')->delete($photo->file_path);
                }
            }

            foreach ($fleet->documents as $document) {
                if ($document->file_path) {
                    Storage::disk('public')->delete($document->file_path);
                }
            }

            $fleet->forceDelete();
        });
    }

    public function restore(int $fleetId): Fleet
    {
        $fleet = Fleet::withTrashed()->findOrFail($fleetId);
        $fleet->restore();
        $fleet->update(['updated_by' => Auth::id()]);

        return $fleet;
    }
}