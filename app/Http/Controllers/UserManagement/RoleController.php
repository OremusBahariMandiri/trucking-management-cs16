<?php

namespace App\Http\Controllers\UserManagement;

use App\Actions\UserManagement\RoleManagement\CreateRole;
use App\Actions\UserManagement\RoleManagement\DeleteRole;
use App\Actions\UserManagement\RoleManagement\SyncRolePermissions;
use App\Actions\UserManagement\RoleManagement\UpdateRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserManagement\RoleManagement\StoreRoleRequest;
use App\Http\Requests\UserManagement\RoleManagement\UpdateRoleRequest;
use App\Models\UserManagement\Permission;
use App\Models\UserManagement\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function __construct(
        protected CreateRole          $createRole,
        protected UpdateRole          $updateRole,
        protected DeleteRole          $deleteRole,
        protected SyncRolePermissions $syncRolePermissions,
    ) {}

    public function index(Request $request)
    {
        $roles = Role::withCount(['permissions', 'users'])
            ->when($request->search, fn ($q, $s) =>
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('slug', 'like', "%{$s}%")
            )
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('user-management.roles.index', compact('roles'));
    }

    public function create()
    {
        return view('user-management.roles.create');
    }

    public function store(StoreRoleRequest $request)
    {
        $role = $this->createRole->execute($request->validated());

        return redirect()
            ->route('user-management.roles.index', $role)
            ->with('success', "Role {$role->name} berhasil ditambahkan.");
    }

    public function show(Role $role)
    {
        $role->load('users', 'permissions');

        // Semua permission dikelompokkan per group
        $allPermissions = Permission::orderBy('group')->orderBy('name')->get()
            ->groupBy('group');

        $assignedIds = $role->permissions->pluck('id')->toArray();

        return view('user-management.roles.show', compact('role', 'allPermissions', 'assignedIds'));
    }

    public function edit(Role $role)
    {
        return view('user-management.roles.edit', compact('role'));
    }

    public function update(UpdateRoleRequest $request, Role $role)
    {
        $this->updateRole->execute($role, $request->validated());

        return redirect()
            ->route('user-management.roles.index', $role)
            ->with('success', "Role {$role->name} berhasil diperbarui.");
    }

    public function destroy(Role $role)
    {
        $name = $role->name;
        $this->deleteRole->execute($role);

        return redirect()
            ->route('user-management.roles.index')
            ->with('success', "Role {$name} berhasil dihapus.");
    }


    /**
     * Dedicated page for managing role permissions.
     */
    public function managePermissions(Role $role)
    {
        $role->load('permissions');

        $allPermissions = Permission::orderBy('group')->orderBy('slug')->get();
        $assignedIds    = $role->permissions->pluck('id')->toArray();

        // Build hierarchical tree: [L1][L2][modulePrefix] => [Permission, ...]
        $tree = [];
        foreach ($allPermissions as $perm) {
            $groupParts = explode(' > ', $perm->group, 2);
            $l1 = trim($groupParts[0] ?? 'Lainnya');
            $l2 = trim($groupParts[1] ?? '');

            $slugParts = explode('.', $perm->slug);
            array_pop($slugParts); // strip action
            $modulePrefix = implode('.', $slugParts);

            $tree[$l1][$l2][$modulePrefix][] = $perm;
        }

        return view('user-management.roles.permissions', compact('role', 'tree', 'assignedIds'));
    }

    /**
     * Sync permissions untuk role — dipanggil dari form di halaman permissions.
     */
    public function syncPermissions(Request $request, Role $role)
    {
        $request->validate([
            'permission_ids'   => 'nullable|array',
            'permission_ids.*' => 'exists:permissions,id',
        ]);

        $this->syncRolePermissions->execute($role, $request->permission_ids ?? []);

        return redirect()
            ->route('user-management.roles.manage-permissions', $role)
            ->with('success', "Permission untuk role {$role->name} berhasil diperbarui.");
    }
}