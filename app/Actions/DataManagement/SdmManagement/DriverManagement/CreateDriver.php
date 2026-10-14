<?php

namespace App\Actions\DataManagement\SdmManagement\DriverManagement;

use App\Models\DataManagement\SdmManagement\DriverManagement\Driver;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class CreateDriver
{
    public function handle(array $data, array $documents = [], array $certificates = []): Driver
    {
        return DB::transaction(function () use ($data, $documents, $certificates) {

            // ── Foto profil ────────────────────────────────────
            if (isset($data['photo']) && $data['photo'] instanceof UploadedFile) {
                $data['photo_path'] = $data['photo']->store('drivers/photos', 'public');
            }
            unset($data['photo']);

            // ── File KTP ───────────────────────────────────────
            if (isset($data['ktp_file']) && $data['ktp_file'] instanceof UploadedFile) {
                $data['ktp_file_path'] = $data['ktp_file']->store('drivers/ktp', 'public');
            }
            unset($data['ktp_file']);

            // ── Domisili = KTP ─────────────────────────────────
            if (!empty($data['domicile_same_as_ktp'])) {
                $data['domicile_address']     = $data['ktp_address']     ?? null;
                $data['domicile_village']     = $data['ktp_village']     ?? null;
                $data['domicile_district']    = $data['ktp_district']    ?? null;
                $data['domicile_city']        = $data['ktp_city']        ?? null;
                $data['domicile_province']    = $data['ktp_province']    ?? null;
                $data['domicile_postal_code'] = $data['ktp_postal_code'] ?? null;
            }

            $driver = Driver::create($data);

            // ── Dokumen ────────────────────────────────────────
            foreach ($documents as $doc) {
                if (empty($doc['document_name'])) continue;

                $filePath = null;
                if (isset($doc['file']) && $doc['file'] instanceof UploadedFile) {
                    $filePath = $doc['file']->store('drivers/documents', 'public');
                }

                $driver->documents()->create([
                    'document_name'   => $doc['document_name'],
                    'document_number' => $doc['document_number'] ?? null,
                    'issued_date'     => $doc['issued_date']     ?? null,
                    'expiry_date'     => $doc['expiry_date']     ?? null,
                    'description'     => $doc['description']     ?? null,
                    'file_path'       => $filePath,
                ]);
            }

            // ── Sertifikat ─────────────────────────────────────
            foreach ($certificates as $cert) {
                if (empty($cert['certificate_name'])) continue;

                $filePath = null;
                if (isset($cert['file']) && $cert['file'] instanceof UploadedFile) {
                    $filePath = $cert['file']->store('drivers/certificates', 'public');
                }

                $driver->certificates()->create([
                    'certificate_name'   => $cert['certificate_name'],
                    'certificate_number' => $cert['certificate_number'] ?? null,
                    'issuer'             => $cert['issuer']             ?? null,
                    'issued_date'        => $cert['issued_date']        ?? null,
                    'expiry_date'        => $cert['expiry_date']        ?? null,
                    'description'        => $cert['description']        ?? null,
                    'file_path'          => $filePath,
                ]);
            }

            return $driver;
        });
    }
}