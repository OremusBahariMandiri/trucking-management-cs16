<?php

namespace App\Actions\DataManagement\FleetManagement;

use App\Models\DataManagement\FleetManagement\Fleet;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CreateFleet
{
    /**
     * Label yang ditampilkan di VehicleDocument untuk setiap tipe dokumen.
     */
    private const DOCUMENT_NUMBER_MAP = [
        'stnk'      => 'stnk_number',
        'kir'       => 'kir_number',
        'bpkb'      => 'bpkb_number',
        'asuransi'  => 'insurance_policy_number',
    ];

    private const DOCUMENT_EXPIRY_MAP = [
        'stnk'     => 'tax_expiry_date',
        'kir'      => 'kir_expiry_date',
        'asuransi' => 'insurance_expiry_date',
    ];

    public function handle(array $data, array $photos = [], array $documentFiles = []): Fleet
    {
        return DB::transaction(function () use ($data, $photos, $documentFiles) {

            $fleet = Fleet::create([
                ...$data,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ]);

            if (!empty($photos)) {
                $this->storePhotos($fleet, $photos);
            }

            if (!empty($documentFiles)) {
                $this->storeDocumentFiles($fleet, $documentFiles, $data);
            }

            return $fleet;
        });
    }

    // ── Photos ─────────────────────────────────────────────────────────────

    private function storePhotos(Fleet $fleet, array $photos): void
    {
        // Pastikan tepat satu foto yang is_primary = true
        $hasPrimary = collect($photos)->contains(fn ($p) => !empty($p['is_primary']));

        foreach ($photos as $index => $photo) {
            /** @var UploadedFile $file */
            $file = $photo['file'];

            $path = $file->store("vehicles/{$fleet->id}/photos", 'public');

            // Jika user tidak memilih utama → foto pertama otomatis jadi utama
            $isPrimary = $hasPrimary
                ? (bool) ($photo['is_primary'] ?? false)
                : ($index === 0);

            $fleet->photos()->create([
                'category'     => $photo['category'] ?? 'lainnya',
                'caption'      => $photo['caption'] ?? null,
                'file_path'    => $path,
                'file_name'    => $file->getClientOriginalName(),
                'file_size_kb' => (int) round($file->getSize() / 1024),
                'sort_order'   => $photo['sort_order'] ?? $index,
                'is_primary'   => $isPrimary,
                'uploaded_by'  => Auth::id(),
            ]);
        }
    }

    // ── Document files ─────────────────────────────────────────────────────

    /**
     * Simpan file lampiran untuk setiap jenis dokumen (STNK, KIR, BPKB, Asuransi).
     * Tiap jenis disimpan sebagai satu baris di tabel VehicleDocument.
     *
     * @param  array<string, UploadedFile>  $documentFiles  e.g. ['stnk' => UploadedFile, ...]
     * @param  array                         $data           data fleet (untuk mengambil nomor & tanggal)
     */
    private function storeDocumentFiles(Fleet $fleet, array $documentFiles, array $data): void
    {
        foreach ($documentFiles as $type => $file) {
            if (!($file instanceof UploadedFile) || !$file->isValid()) {
                continue;
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
}