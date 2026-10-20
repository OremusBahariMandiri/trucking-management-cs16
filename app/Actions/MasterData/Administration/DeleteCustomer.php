<?php

namespace App\Actions\MasterData\Administration;

use App\Models\MasterData\Administration\Customer;
use Illuminate\Support\Facades\Storage;

class DeleteCustomer
{
    public function handle(Customer $customer): void
    {
        // Hapus file NPWP & NIB dari storage
        if ($customer->npwp_file_path) {
            Storage::disk('public')->delete($customer->npwp_file_path);
        }
        if ($customer->nib_file_path) {
            Storage::disk('public')->delete($customer->nib_file_path);
        }

        $customer->delete();
    }
}
