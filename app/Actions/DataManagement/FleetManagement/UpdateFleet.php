<?php

namespace App\Actions\DataManagement\FleetManagement;

use App\Models\DataManagement\FleetManagement\Fleet;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UpdateFleet
{
    /**
     * Map tipe dokumen → field nomor di tabel aa06_vehicles.
     */
    private const DOCUMENT_NUMBER_MAP = [
        'stnk'     => 'stnk_number',
        'kir'      => 'kir_number',
        'bpkb'     => 'bpkb_number',
        'asuransi' => 'insurance_policy_number',
    ];

    private const DOCUMENT_EXPIRY_MAP = [
        'stnk'     => 'tax_expiry_date',
        'kir'      => 'kir_expiry_date',
        'asuransi' => 'insurance_expiry_date',
    ];

    public function handle(Fleet $fleet, array $data, array $photos = [], array $documentFiles = []): Fleet
    {
        return DB::transaction(function () use ($fleet, $data, $photos, $documentFiles) {

            $fleet->update([
                ...$data,
                'updated_by' => Auth::id(),
            ]);

            if (!empty($photos)) {
                $this->storePhotos($fleet, $photos);
            }

            if (!empty($documentFiles)) {
                $this->storeDocumentFiles($fleet, $documentFiles, $data);
            }

            return $fleet->fresh();
        });
    }

    // ── Photos ─────────────────────────────────────────────────────────────

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

    // ── Document files ─────────────────────────────────────────────────────

    /**
     * Simpan / timpa file lampiran dokumen.
     * Jika dokumen dengan tipe yang sama sudah ada, file lama dihapus dari storage
     * lalu diganti dengan yang baru (updateOrCreate).
     *
     * @param  array<string, UploadedFile>  $documentFiles
     */
    private function storeDocumentFiles(Fleet $fleet, array $documentFiles, array $data): void
    {
        foreach ($documentFiles as $type => $file) {
            if (!($file instanceof UploadedFile) || !$file->isValid()) {
                continue;
            }

            // Hapus file lama jika sudah ada
            $existing = $fleet->documents()->where('document_type', $type)->first();
            if ($existing?->file_path) {
                Storage::disk('public')->delete($existing->file_path);
            }

            $path = $file->store("vehicles/{$fleet->id}/documents/{$type}", 'public');

            $numberField = self::DOCUMENT_NUMBER_MAP[$type] ?? null;
            $expiryField = self::DOCUMENT_EXPIRY_MAP[$type] ?? null;

            $fleet->documents()->updateOrCreate(
                ['document_type' => $type],
                [
                    'document_number' => $numberField ? ($data[$numberField] ?? null) : null,
                    'expiry_date'     => $expiryField ? ($data[$expiryField] ?? null) : null,
                    'file_path'       => $path,
                    'file_name'       => $file->getClientOriginalName(),
                    'file_size_kb'    => (int) round($file->getSize() / 1024),
                    'uploaded_by'     => Auth::id(),
                ]
            );
        }
    }

    // ── Photo helpers ───────────────────────────────────────────────────────

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

    public function updatePhotoMeta(Fleet $fleet, int $photoId, string $category, ?string $caption): void
    {
        $fleet->photos()
              ->findOrFail($photoId)
              ->update([
                  'category' => $category,
                  'caption'  => $caption,
              ]);
    }

    // ── Document file helper ────────────────────────────────────────────────

    /**
     * Hapus file lampiran satu dokumen berdasarkan tipe-nya (stnk|kir|bpkb|insurance).
     */
    public function deleteDocumentFile(Fleet $fleet, string $documentType): void
    {
        $document = $fleet->documents()->where('document_type', $documentType)->firstOrFail();

        if ($document->file_path) {
            Storage::disk('public')->delete($document->file_path);
        }

        $document->update([
            'file_path'    => null,
            'file_name'    => null,
            'file_size_kb' => null,
        ]);
    }
}