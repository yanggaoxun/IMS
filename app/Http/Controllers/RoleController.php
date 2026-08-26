<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleController extends Controller
{
    /**
     * 角色列表（含每个角色的权限与全部权限字典）
     */
    public function index()
    {
        return Inertia::render('Roles/Index', [
            'roles' => Role::with('permissions:id,name')->orderBy('id')->get(['id', 'name']),
            'permissions' => Permission::orderBy('name')->get(['id', 'name']),
        ]);
    }

    /**
     * 新建角色
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:roles,name'],
            'permissions' => ['array'],
            'permissions.*' => ['string', Rule::exists('permissions', 'name')],
        ]);

        $role = Role::create(['name' => $validated['name']]);
        $role->syncPermissions($validated['permissions'] ?? []);

        $this->flushCache();

        return back()->with('success', true);
    }

    /**
     * 更新角色（admin 角色不可改名）
     */
    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('roles', 'name')->ignore($role->id)],
            'permissions' => ['array'],
            'permissions.*' => ['string', Rule::exists('permissions', 'name')],
        ]);

        if ($role->name === 'admin' && $validated['name'] !== 'admin') {
            return back()->withErrors(['name' => 'admin_role_locked']);
        }

        $role->update(['name' => $validated['name']]);
        $role->syncPermissions($validated['permissions'] ?? []);

        $this->flushCache();

        return back()->with('success', true);
    }

    /**
     * 删除角色（禁止删除 admin）
     */
    public function destroy(Role $role)
    {
        if ($role->name === 'admin') {
            return back()->withErrors(['role' => 'admin_role_locked']);
        }

        $role->delete();

        $this->flushCache();

        return back()->with('success', true);
    }

    private function flushCache(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
