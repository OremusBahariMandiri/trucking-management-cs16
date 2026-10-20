<?php

namespace App\Actions\MasterData\Administration;

use App\Models\MasterData\Administration\Customer;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CreateCustomer
{
    public function handle(array $data, ?UploadedFile $npwpFile = null, ?UploadedFile $nibFile = null): Customer
    {
        return DB::transaction(function () use ($data, $npwpFile, $nibFile) {

            $customer = Customer::create([
                ...$this->coreFields($data),
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ]);

            $this->handleFile($customer, $npwpFile, 'npwp');
            $this->handleFile($customer, $nibFile,  'nib');

            $this->syncPics($customer, $data['pics'] ?? []);

            return $customer;
        });
    }

    // ── Helpers ────────────────────────────────────────────────────────────

    private function coreFields(array $data): array
    {
        return [
            'registration_date' => $data['registration_date'],
            'customer_code'     => $data['customer_code'],
            'name'              => $data['name'],
            'customer_type_id'  => $data['customer_type_id']  ?? null,
            'business_type_id'  => $data['business_type_id']  ?? null,
            'npwp_number'       => $data['npwp_number']        ?? null,
            'nib_number'        => $data['nib_number']         ?? null,
            'address'           => $data['address']            ?? null,
            'rt_rw'             => $data['rt_rw']              ?? null,
            'kelurahan'         => $data['kelurahan']          ?? null,
            'kecamatan'         => $data['kecamatan']          ?? null,
            'city'              => $data['city']               ?? null,
            'province'          => $data['province']           ?? null,
            'postal_code'       => $data['postal_code']        ?? null,
            'phone'             => $data['phone']              ?? null,
            'email'             => $data['email']              ?? null,
            'is_active'         => $data['is_active']          ?? true,
        ];
    }

    private function handleFile(Customer $customer, ?UploadedFile $file, string $prefix): void
    {
        if (!$file || !$file->isValid()) return;

        $path = $file->store("customers/{$customer->id}/{$prefix}", 'public');
        $customer->update([
            "{$prefix}_file_path" => $path,
            "{$prefix}_file_name" => $file->getClientOriginalName(),
        ]);
    }

    private function syncPics(Customer $customer, array $pics): void
    {
        $hasPrimary = collect($pics)->contains(fn($p) => !empty($p['is_primary']));

        foreach ($pics as $index => $pic) {
            if (empty($pic['name'])) continue;

            $customer->pics()->create([
                'name'       => $pic['name'],
                'position'   => $pic['position']   ?? null,
                'department' => $pic['department']  ?? null,
                'phone'      => $pic['phone']       ?? null,
                'email'      => $pic['email']       ?? null,
                'is_primary' => $hasPrimary ? (bool)($pic['is_primary'] ?? false) : ($index === 0),
            ]);
        }
    }
}
