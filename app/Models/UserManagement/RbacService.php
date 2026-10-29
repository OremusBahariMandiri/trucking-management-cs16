<?php

namespace App\Models\UserManagement;

use App\Models\User;
use Illuminate\Support\Collection;

class RbacService
{
    // ── Superadmin ───────────────────────────────────────────────

    public function isSuperAdmin(User $user): bool
    {
        return $user->isSuperAdmin();
    }

    // ── Permission ───────────────────────────────────────────────

    public function userHasPermission(User $user, string $slug): bool
    {
        return $user->hasPermission($slug);
    }

    public function userHasAccess(User $user, string $menu, string $action): bool
    {
        return $user->hasAccess($menu, $action);
    }

    /**
     * Ambil semua slug permission milik user (dari semua role-nya).
     */
    public function getUserPermissions(User $user): Collection
    {
        if ($user->isSuperAdmin()) {
            return Permission::pluck('slug');
        }

        return Permission::whereHas('roles', function ($q) use ($user) {
            $q->whereIn('roles.id', $user->roles()->pluck('roles.id'));
        })->pluck('slug');
    }

    // ── Role Assignment ──────────────────────────────────────────

    public function assignRoles(User $user, array $roleIds): void
    {
        $user->roles()->sync($roleIds);
    }

    public function addRole(User $user, int $roleId): void
    {
        $user->roles()->syncWithoutDetaching([$roleId]);
    }

    public function removeRole(User $user, int $roleId): void
    {
        $user->roles()->detach($roleId);
    }

    // ── Role Permission Sync ─────────────────────────────────────

    public function syncRolePermissions(Role $role, array $permissionIds): void
    {
        $role->permissions()->sync($permissionIds);
    }

    public function grantPermission(Role $role, int $permissionId): void
    {
        $role->permissions()->syncWithoutDetaching([$permissionId]);
    }

    public function revokePermission(Role $role, int $permissionId): void
    {
        $role->permissions()->detach($permissionId);
    }

    // ── Permission Derivation dari Route Name ────────────────────

    /**
     * Konversi route name ke permission slug.
     *
     * Contoh:
     *   "master-data.vehicle-types.index"   => "master-data.vehicle-types.index"
     *   "data-management.fleet-data.store"      => "data-management.fleet-data.create"
     *   "master-data.vehicle-types.api.list" => "master-data.vehicle-types.index"
     *   "data-management.fleet-data.foto.destroy" => "data-management.fleet-data.delete"
     */
    public function derivePermissionFromRouteName(string $routeName): ?string
    {
        $parts = explode('.', $routeName);

        $actionRaw = array_pop($parts);

        // Hapus segmen-segmen sub-resource (api, foto, documents, certificates, dll.)
        $subResources = ['api', 'foto', 'documents', 'certificates'];
        $parts = array_values(array_filter($parts, fn ($p) => !in_array($p, $subResources)));

        $menu = implode('.', $parts);

        $actionMap = [
            'index'           => 'index',
            'list'            => 'index',
            'search'          => 'index',
            'show'            => 'show',
            'create'          => 'create',
            'store'           => 'create',
            'edit'            => 'edit',
            'update'          => 'edit',
            'destroy'         => 'delete',
            'pdf'             => 'export-pdf',
            'update-meta'     => 'edit',
            'toggle'          => 'edit',
            'set-primary'     => 'edit',
            'generate-code'   => 'index',
            'generate-nrk'    => 'index',
        ];

        $mapped = $actionMap[$actionRaw] ?? null;

        if (!$mapped || !$menu) {
            return null;
        }

        return "{$menu}.{$mapped}";
    }
}