<?php

namespace App\Models;

use App\Models\UserManagement\Role;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'employee_code',
        'employee_id_number',
        'full_name',
        'department',
        'position',
        'work_location',
        'password',
        'role_id',
        'created_by',
        'updated_by',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($user) {
            if (empty($user->employee_code)) {
                $user->employee_code = 'EMP-' . strtoupper(Str::random(8));
            }
        });
    }

    // ── Relasi ──────────────────────────────────────────

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    // ── Permission Check ─────────────────────────────────

    public function hasPermission(string $slug): bool
    {
        return $this->role?->hasPermission($slug) ?? false;
    }

    public function hasAccess(string $menu, string $action): bool
    {
        $actionMap = [
            'monitoring' => 'monitor',
            'index'      => 'monitor',
            'detail'     => 'view',
            'tambah'     => 'create',
            'ubah'       => 'update',
            'hapus'      => 'delete',
            'download'   => 'download',
        ];

        $mapped = $actionMap[$action] ?? $action;
        $slug   = "{$menu}.{$mapped}"; // contoh: "users.create"

        return $this->hasPermission($slug);
    }

    public function hasAccessToMenu(string $menu): bool
    {
        return $this->role?->permissions()
            ->where('group', $menu)
            ->exists() ?? false;
    }
}