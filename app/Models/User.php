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
        'created_by',
        'updated_by',
        // 'role_id' ← dihapus, diganti many-to-many
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

    // ── Relasi ────────────────────────────────────────────────────

    /**
     * Many-to-many: satu user bisa punya banyak role.
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles')->withTimestamps();
    }

    // ── RBAC Helpers ──────────────────────────────────────────────

    public function isSuperAdmin(): bool
    {
        return $this->roles()->where('slug', 'superadmin')->exists();
    }

    public function hasRole(string $slug): bool
    {
        return $this->roles()->where('slug', $slug)->exists();
    }

    public function hasPermission(string $slug): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        return $this->roles()
                    ->whereHas('permissions', fn ($q) => $q->where('slug', $slug))
                    ->exists();
    }

    public function hasAccess(string $menu, string $action): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        $actionMap = [
            'index'   => 'monitor',
            'list'    => 'monitor',
            'search'  => 'monitor',
            'show'    => 'view',
            'create'  => 'create',
            'store'   => 'create',
            'edit'    => 'update',
            'update'  => 'update',
            'destroy' => 'delete',
            'delete'  => 'delete',
            'toggle'  => 'toggle',
        ];

        $permAction = $actionMap[$action] ?? $action;

        return $this->hasPermission("{$menu}.{$permAction}");
    }

    public function hasAccessToMenu(string $menu): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        return $this->roles()
                    ->whereHas('permissions', fn ($q) => $q->where('slug', 'like', "{$menu}.%"))
                    ->exists();
    }
}