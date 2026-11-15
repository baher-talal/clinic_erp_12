<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::withCount('users')->with('permissions')->get();
        return view('admin.roles.index', compact('roles'));
    }

    public function edit(Role $role)
    {
        $permissions       = Permission::all()->groupBy('group');
        $rolePermissionIds = $role->permissions->pluck('id')->toArray();
        return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissionIds'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'label'          => 'required|string|max:100',
            'color'          => 'required|string|max:20',
            'permissions'    => 'nullable|array',
            'permissions.*'  => 'exists:permissions,id',
        ]);

        $role->update([
            'label' => $request->label,
            'color' => $request->color,
        ]);

        $role->permissions()->sync($request->permissions ?? []);

        return redirect()->route('admin.roles.index')
            ->with('success', 'تم تحديث صلاحيات الدور بنجاح');
    }
}
