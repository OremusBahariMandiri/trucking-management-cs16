<?php

// app/Models/DataManagement/SdmManagement/EmployeeManagement/Employee.php

namespace App\Models\DataManagement\SdmManagement\EmployeeManagement;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $table = 'bc01_employees';

    protected $fillable = [
        'nrk',
        'full_name',
        'department',
        'position',
        'wilker',
        'notes',
        'created_by',
        'updated_by',
    ];

    // ── Scopes ─────────────────────────────────────────────────────────────

    public function scopeSearch($query, string $keyword)
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('full_name', 'like', "%{$keyword}%")
              ->orWhere('nrk', 'like', "%{$keyword}%")
              ->orWhere('department', 'like', "%{$keyword}%")
              ->orWhere('position', 'like', "%{$keyword}%")
              ->orWhere('wilker', 'like', "%{$keyword}%");
        });
    }
}