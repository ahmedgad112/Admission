<?php

namespace App\Http\Controllers;

use App\Concerns\RespondsWithInertiaOrJson;
use App\Enums\Permission;
use App\Enums\UserRole;
use App\Http\Requests\RolePermission\UpdateRolePermissionsRequest;
use App\Models\RolePermissionSet;
use App\Support\RolePermissionCatalog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class RolePermissionController extends Controller
{
    use RespondsWithInertiaOrJson;

    public function edit(): Response
    {
        $this->authorize('viewAny', RolePermissionSet::class);

        return Inertia::render('permissions/Edit', [
            'permissionOptions' => array_map(fn (Permission $permission) => [
                'value' => $permission->value,
                'label' => $permission->label(),
                'description' => $permission->description(),
            ], Permission::cases()),
            'roles' => array_map(fn (UserRole $role) => [
                'value' => $role->value,
                'locked' => $role === UserRole::SuperAdmin,
            ], UserRole::cases()),
            'rolePermissions' => app(RolePermissionCatalog::class)->permissionValuesByRole(),
        ]);
    }

    public function update(UpdateRolePermissionsRequest $request, RolePermissionCatalog $catalog): JsonResponse|RedirectResponse
    {
        $catalog->sync($request->rolePermissions());

        return $this->flashRedirect($request, __('flash.permissions.updated'), route('permissions.edit'));
    }
}
