<?php

namespace App\Services\DataManagement\SdmManagement\EmployeeManagement;

use App\Models\DataManagement\SdmManagement\EmployeeManagement\Employee;
use Illuminate\Pagination\LengthAwarePaginator;

class EmployeeService
{
    public function list(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return Employee::query()
            ->when($filters['search'] ?? null, fn($q, $v) => $q->search($v))
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }

    public function find(int $id): Employee
    {
        return Employee::findOrFail($id);
    }

    public function generateNrk(): string
    {
        $year  = now()->year;
        $count = Employee::whereYear('created_at', $year)->count();
        $seq   = str_pad($count + 1, 4, '0', STR_PAD_LEFT);

        return "EMP-{$year}-{$seq}";
    }

    public function stats(): array
    {
        return [
            'total' => Employee::count(),
        ];
    }
}