<?php

namespace App\Http\Controllers\UserManagement;

use App\Actions\UserManagement\UserManagement\CreateUser;
use App\Actions\UserManagement\UserManagement\DeleteUser;
use App\Actions\UserManagement\UserManagement\UpdateUser;
use App\Actions\UserManagement\UserRoleManagement\AssignUserRoles;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserManagement\UserManagement\StoreUserRequest;
use App\Http\Requests\UserManagement\UserManagement\UpdateUserRequest;
use App\Models\User;
use App\Models\UserManagement\Permission;
use App\Models\UserManagement\Role;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(
        protected CreateUser      $createUser,
        protected UpdateUser      $updateUser,
        protected DeleteUser      $deleteUser,
        protected AssignUserRoles $assignUserRoles,
    ) {}

    public function index(Request $request)
    {
        $users = User::with('roles')
            ->when(
                $request->search,
                fn($q, $s) =>
                $q->where('full_name', 'like', "%{$s}%")
                    ->orWhere('employee_code', 'like', "%{$s}%")
                    ->orWhere('employee_id_number', 'like', "%{$s}%")
            )
            ->when(
                $request->role,
                fn($q, $r) =>
                $q->whereHas('roles', fn($rq) => $rq->where('slug', $r))
            )
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $roles = Role::orderBy('name')->get();

        $totalUsers    = User::count();
        $totalSuperadmin = User::whereHas('roles', fn($q) => $q->where('slug', 'superadmin'))->count();

        return view('user-management.users.index', compact(
            'users',
            'roles',
            'totalUsers',
            'totalSuperadmin'
        ));
    }

    public function create()
    {
        $roles = Role::orderBy('name')->get();
        return view('user-management.users.create', compact('roles'));
    }

    public function store(StoreUserRequest $request)
    {
        $user = $this->createUser->execute($request->validated());
        $this->assignUserRoles->execute($user, $request->role_ids ?? []);

        return redirect()
            ->route('user-management.users.index', $user)
            ->with('success', "User {$user->full_name} berhasil ditambahkan.");
    }

    public function show(User $user)
    {
        $user->load(['roles.permissions']);
        $allPermissions = Permission::all()->groupBy('group');

        return view('user-management.users.show', compact('user', 'allPermissions'));
    }

    public function edit(User $user)
    {
        $user->load('roles');
        $roles = Role::orderBy('name')->get();
        return view('user-management.users.edit', compact('user', 'roles'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $this->updateUser->execute($user, $request->validated());
        $this->assignUserRoles->execute($user, $request->role_ids ?? []);

        return redirect()
            ->route('user-management.users.index', $user)
            ->with('success', "User {$user->full_name} berhasil diperbarui.");
    }

    public function destroy(User $user)
    {
        $name = $user->full_name;
        $this->deleteUser->execute($user);

        return redirect()
            ->route('user-management.users.index')
            ->with('success', "User {$name} berhasil dihapus.");
    }
}
