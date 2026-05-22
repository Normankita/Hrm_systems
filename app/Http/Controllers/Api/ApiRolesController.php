<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;

class ApiRolesController extends Controller
{
    public function __construct()
    {
        // You can add middleware or auth checks here if needed
    }

    public function updateRolePermissions(Request $request, $id)
    {
        $request->validate([
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role = Role::findOrFail($id);
        $this->syncEntityPermissions($role, $request->permissions);

        return response()->json([
            'message' => 'Permissions updated successfully for role',
            'role' => $role,
        ]);
    }

    public function updateUserPermissions(Request $request, $id)
    {
        $request->validate([
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $user = User::findOrFail($id);
        $this->syncEntityPermissions($user, $request->permissions);

        return response()->json([
            'message' => 'Permissions updated successfully for user',
            'user' => $user,
        ]);
    }

    /**
     * Sync permissions to a role or user.
     *
     * @param \Spatie\Permission\Traits\HasPermissions $entity
     * @param array|null $permissions
     * @return void
     */
    private function syncEntityPermissions($entity, $permissions)
    {
        if (empty($permissions)) {
            $entity->syncPermissions([]);
            return;
        }

        $permissionNames = Permission::whereIn('id', $permissions)->pluck('name')->toArray();
        $entity->syncPermissions($permissionNames);
    }
}
