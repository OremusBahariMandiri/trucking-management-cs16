<?php

namespace App\Actions\DataManagement\SdmManagement\DriverManagement;

use App\Models\DataManagement\SdmManagement\DriverManagement\Driver;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UpdateDriver
{
    public function handle(Driver $driver, array $data, array $documents = [], array $certificates = [], array $emergencyContacts = [], array $existingEmergencyContacts = []): Driver
    {
        return DB::transaction(function () use ($driver, $data, $documents, $certificates, $emergencyContacts, $existingEmergencyContacts) {

            // ── Foto profil ────────────────────────────────────
            if (!empty($data['remove_photo'])) {
                if ($driver->photo_path) {
                    Storage::disk('public')->delete($driver->photo_path);
                }
                $data['photo_path'] = null;

            } elseif (isset($data['photo']) && $data['photo'] instanceof UploadedFile) {
                if ($driver->photo_path) {
                    Storage::disk('public')->delete($driver->photo_path);
                }
                $data['photo_path'] = $data['photo']->store('drivers/photos', 'public');
            }
            unset($data['photo'], $data['remove_photo']);

            // ── File KTP ───────────────────────────────────────
            if (isset($data['ktp_file']) && $data['ktp_file'] instanceof UploadedFile) {
                if ($driver->ktp_file_path) {
                    Storage::disk('public')->delete($driver->ktp_file_path);
                }
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

            $driver->update($data);

            // ── Dokumen baru ───────────────────────────────────
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

            // ── Sertifikat baru ────────────────────────────────
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

            // ── Kontak Darurat yang ada (update) ──────────────
            foreach ($existingEmergencyContacts as $contactId => $contact) {
                $existing = $driver->emergencyContacts()->find((int) $contactId);
                if (! $existing) continue;

                $existing->update([
                    'contact_type'      => $contact['contact_type']      ?? $existing->contact_type,
                    'contact_status'    => $contact['contact_status']    ?? $existing->contact_status,
                    'full_name'         => $contact['full_name']         ?? $existing->full_name,
                    'nik'               => $contact['nik']               ?? null,
                    'birth_place'       => $contact['birth_place']       ?? null,
                    'birth_date'        => $contact['birth_date']        ?? null,
                    'gender'            => $contact['gender']            ?? null,
                    'religion'          => $contact['religion']          ?? null,
                    'marital_status'    => $contact['marital_status']    ?? null,
                    'phone_primary'     => $contact['phone_primary']     ?? $existing->phone_primary,
                    'phone_alternative' => $contact['phone_alternative'] ?? null,
                    'province'          => $contact['province']          ?? null,
                    'city'              => $contact['city']              ?? null,
                    'district'          => $contact['district']          ?? null,
                    'village'           => $contact['village']           ?? null,
                    'rt_rw'             => $contact['rt_rw']             ?? null,
                    'postal_code'       => $contact['postal_code']       ?? null,
                    'full_address'      => $contact['full_address']      ?? null,
                ]);
            }

            // ── Kontak Darurat baru ────────────────────────────
            foreach ($emergencyContacts as $contact) {
                if (empty($contact['full_name']) || empty($contact['phone_primary'])) continue;

                $driver->emergencyContacts()->create([
                    'contact_type'      => $contact['contact_type']      ?? 'orang_tua',
                    'contact_status'    => $contact['contact_status']    ?? 'aktif',
                    'full_name'         => $contact['full_name'],
                    'nik'               => $contact['nik']               ?? null,
                    'birth_place'       => $contact['birth_place']       ?? null,
                    'birth_date'        => $contact['birth_date']        ?? null,
                    'gender'            => $contact['gender']            ?? 'male',
                    'religion'          => $contact['religion']          ?? null,
                    'marital_status'    => $contact['marital_status']    ?? null,
                    'phone_primary'     => $contact['phone_primary'],
                    'phone_alternative' => $contact['phone_alternative'] ?? null,
                    'province'          => $contact['province']          ?? null,
                    'city'              => $contact['city']              ?? null,
                    'district'          => $contact['district']          ?? null,
                    'village'           => $contact['village']           ?? null,
                    'rt_rw'             => $contact['rt_rw']             ?? null,
                    'postal_code'       => $contact['postal_code']       ?? null,
                    'full_address'      => $contact['full_address']      ?? null,
                ]);
            }

            return $driver->fresh();
        });
    }

    public function deleteDocument(Driver $driver, int $documentId): void
    {
        $doc = $driver->documents()->findOrFail($documentId);

        if ($doc->file_path) {
            Storage::disk('public')->delete($doc->file_path);
        }

        $doc->delete();
    }

    public function deleteCertificate(Driver $driver, int $certificateId): void
    {
        $cert = $driver->certificates()->findOrFail($certificateId);

        if ($cert->file_path) {
            Storage::disk('public')->delete($cert->file_path);
        }

        $cert->delete();
    }

    public function deleteEmergencyContact(Driver $driver, int $contactId): void
    {
        $contact = $driver->emergencyContacts()->findOrFail($contactId);
        $contact->delete();
    }
}
