<?php

namespace App\Http\Middleware;

use App\Models\UserManagement\RbacService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    public function __construct(protected RbacService $rbacService) {}

    /**
     * Penggunaan di route:
     *   ->middleware('permission')                        // auto-derive dari route name
     *   ->middleware('permission:master-data.vehicle-types.monitor')  // explicit
     */
    public function handle(Request $request, Closure $next, ?string $permission = null): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Superadmin bebas semua akses
        if ($this->rbacService->isSuperAdmin($user)) {
            return $next($request);
        }

        // Tentukan slug permission yang akan dicek
        $slug = $permission ?? $this->deriveFromRoute($request);

        // Jika tidak bisa derive (misal route home/dashboard), lewati saja
        if ($slug === null) {
            return $next($request);
        }

        if (!$this->rbacService->userHasPermission($user, $slug)) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Forbidden. Anda tidak memiliki akses.'], 403);
            }

            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }

    private function deriveFromRoute(Request $request): ?string
    {
        $routeName = $request->route()?->getName();
        if (!$routeName) {
            return null;
        }

        return $this->rbacService->derivePermissionFromRouteName($routeName);
    }
}