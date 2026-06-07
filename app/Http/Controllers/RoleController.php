<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::withCount(['users', 'permissions'])->latest()->get();
        return view('roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::grouped();
        return view('roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles',
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        $role = Role::create([
            'name' => $validated['name'],
            'display_name' => $validated['display_name'],
            'description' => $validated['description'] ?? null,
            'is_active' => $validated['is_active'] ?? true,
        ]);

        $role->permissions()->attach($validated['permissions'] ?? []);

        return redirect()->route('roles.index')->with('success', 'تم إضافة الدور بنجاح');
    }

    public function show(Role $role)
    {
        $role->load(['permissions', 'users']);
        return view('roles.show', compact('role'));
    }

    public function edit(Role $role)
    {
        $permissions = Permission::grouped();
        $rolePermissions = $role->permissions->pluck('id')->toArray();
        return view('roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        $role->update([
            'name' => $validated['name'],
            'display_name' => $validated['display_name'],
            'description' => $validated['description'] ?? null,
            'is_active' => $validated['is_active'] ?? true,
        ]);

        $role->permissions()->sync($validated['permissions'] ?? []);

        return redirect()->route('roles.index')->with('success', 'تم تحديث الدور بنجاح');
    }

    public function destroy(Role $role)
    {
        if ($role->users()->count() > 0) {
            return back()->with('error', 'لا يمكن حذف دور مرتبط بمستخدمين');
        }

        $role->delete();
        return redirect()->route('roles.index')->with('success', 'تم حذف الدور بنجاح');
    }
}