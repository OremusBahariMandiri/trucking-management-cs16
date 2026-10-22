<?php

namespace App\Services\MasterData\Administration;

use App\Models\MasterData\Administration\Customer;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class CustomerService
{
    public function list(array $filters = []): Collection
    {
        return Customer::query()
            ->with(['customerType', 'businessType', 'primaryPic'])
            ->when($filters['q'] ?? null, fn($q, $s) =>
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('customer_code', 'like', "%{$s}%")
                  ->orWhere('phone', 'like', "%{$s}%")
            )
            ->when(isset($filters['status']) && $filters['status'] !== '', fn($q) =>
                $q->where('is_active', $filters['status'])
            )
            ->when($filters['customer_type_id'] ?? null, fn($q, $id) =>
                $q->where('customer_type_id', $id)
            )
            ->latest()
            ->get();
    }

    public function paginatedList(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return Customer::query()
            ->with(['customerType', 'businessType', 'primaryPic'])
            ->when($filters['q'] ?? null, fn($q, $s) =>
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('customer_code', 'like', "%{$s}%")
                  ->orWhere('phone', 'like', "%{$s}%")
            )
            ->when(isset($filters['status']) && $filters['status'] !== '', fn($q) =>
                $q->where('is_active', $filters['status'])
            )
            ->when($filters['customer_type_id'] ?? null, fn($q, $id) =>
                $q->where('customer_type_id', $id)
            )
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }

    public function statCounts(): array
    {
        return [
            'total'    => Customer::count(),
            'active'   => Customer::where('is_active', true)->count(),
            'inactive' => Customer::where('is_active', false)->count(),
        ];
    }
}
