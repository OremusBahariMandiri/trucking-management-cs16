<?php

namespace App\Http\Controllers\UserManagement;

use App\Actions\UserManagement\PermissionManagement\CreatePermission;
use App\Actions\UserManagement\PermissionManagement\DeletePermission;
use App\Actions\UserManagement\PermissionManagement\UpdatePermission;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserManagement\PermissionManagement\StorePermissionRequest;
use App\Http\Requests\UserManagement\PermissionManagement\UpdatePermissionRequest;
use App\Models\UserManagement\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function __construct(
        protected CreatePermission $createPermission,
        protected UpdatePermission $updatePermission,
        protected DeletePermission $deletePermission,
    ) {}

    public function index(Request $request)
    {
        $query = Permission::query()
            ->when($request->search, fn ($q, $s) =>
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('slug', 'like', "%{$s}%")
                  ->orWhere('group', 'like', "%{$s}%")
            )
            ->when($request->group, fn ($q, $g) =>
                $q->where('group', $g)
            )
            ->orderBy('group')
            ->orderBy('name');

        $permissions = $query->paginate(25)->withQueryString();
        $groups      = Permission::distinct()->orderBy('group')->pluck('group');

        return view('user-management.permissions.index', compact('permissions', 'groups'));
    }

    public function create()
    {
        $groups = Permission::distinct()->orderBy('group')->pluck('group');
        return view('user-management.permissions.create', compact('groups'));
    }

    public function store(StorePermissionRequest $request)
    {
        $permission = $this->createPermission->execute($request->validated());

        return redirect()
            ->route('user-management.permissions.index')
            ->with('success', "Permission {$permission->slug} berhasil ditambahkan.");
    }

    public function edit(Permission $permission)
    {
        $groups = Permission::distinct()->orderBy('group')->pluck('group');
        return view('user-management.permissions.edit', compact('permission', 'groups'));
    }

    public function update(UpdatePermissionRequest $request, Permission $permission)
    {
        $this->updatePermission->execute($permission, $request->validated());

        return redirect()
            ->route('user-management.permissions.index')
            ->with('success', "Permission {$permission->slug} berhasil diperbarui.");
    }

    public function destroy(Permission $permission)
    {
        $slug = $permission->slug;
        $this->deletePermission->execute($permission);

        return redirect()
            ->route('user-management.permissions.index')
            ->with('success', "Permission {$slug} berhasil dihapus.");
    }
}