<?php

namespace App\Http\Controllers;

use App\Concerns\RespondsWithInertiaOrJson;
use App\Enums\HomePage;
use App\Enums\Permission;
use App\Http\Requests\RolePermission\StoreRoleRequest;
use App\Http\Requests\RolePermission\UpdateRolePermissionsRequest;
use App\Http\Requests\RolePermission\UpdateRoleRequest;
use App\Models\Role;
use App\Support\RolePermissionCatalog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class RolePermissionController extends Controller
{
    use RespondsWithInertiaOrJson;

    public function edit(): Response
    {
        $this->authorize('viewAny', Role::class);

        $catalog = app(RolePermissionCatalog::class);
        $roles = Role::query()->ordered()->get();

        return Inertia::render('permissions/Edit', [
            'permissionOptions' => array_map(
                fn (Permission $permission): array => $permission->toOption(),
                Permission::cases(),
            ),
            'homePageOptions' => HomePage::options(),
            'roles' => $roles->map(fn (Role $role) => $role->toOption())->values()->all(),
            'rolePermissions' => $catalog->permissionValuesByRole(),
            'roleHomes' => $catalog->homePagesByRole(),
        ]);
    }

    public function update(UpdateRolePermissionsRequest $request, RolePermissionCatalog $catalog): JsonResponse|RedirectResponse
    {
        foreach ($request->roleNames() as $slug => $name) {
            $role = Role::findBySlug($slug);

            if ($role === null || $role->isLocked()) {
                continue;
            }

            $role->update(['name' => $name]);
        }

        $catalog->sync($request->rolePermissions(), $request->roleHomes());

        return $this->flashRedirect($request, __('flash.permissions.updated'), route('permissions.edit'));
    }

    public function store(StoreRoleRequest $request, RolePermissionCatalog $catalog): JsonResponse|RedirectResponse
    {
        Role::query()->create($request->roleAttributes());
        $catalog->forget();

        return $this->flashRedirect($request, __('flash.permissions.role_created'), route('permissions.edit'));
    }

    public function updateRole(UpdateRoleRequest $request, Role $role, RolePermissionCatalog $catalog): JsonResponse|RedirectResponse
    {
        $role->update([
            'name' => trim((string) $request->validated('name')),
        ]);
        $catalog->forget();

        return $this->flashRedirect($request, __('flash.permissions.role_updated'), route('permissions.edit'));
    }

    public function destroy(Request $request, Role $role, RolePermissionCatalog $catalog): JsonResponse|RedirectResponse
    {
        $this->authorize('delete', $role);

        $role->delete();
        $catalog->forget();

        return $this->flashRedirect($request, __('flash.permissions.role_deleted'), route('permissions.edit'));
    }
}
