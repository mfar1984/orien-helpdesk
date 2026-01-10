<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display roles settings.
     */
    public function index(Request $request)
    {
        if (!auth()->user()->hasPermission('settings_roles.view')) {
            abort(403, 'You do not have permission to view roles.');
        }
        
        $query = Role::withCount('users');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $perPage = (int) setting('pagination_size', 15);
        $roles = $query->orderBy('created_at', 'desc')->paginate($perPage);

        $user = auth()->user();
        $canCreate = $user->hasPermission('settings_roles.create');
        $canEdit = $user->hasPermission('settings_roles.edit');
        $canDelete = $user->hasPermission('settings_roles.delete');

        return view('settings.roles.index', compact('roles', 'canCreate', 'canEdit', 'canDelete'));
    }

    /**
     * Show the form for creating a new role.
     */
    public function create()
    {
        if (!auth()->user()->hasPermission('settings_roles.create')) {
            abort(403, 'You do not have permission to create roles.');
        }
        
        return view('settings.roles.create');
    }

    /**
     * Store a newly created role.
     */
    public function store(Request $request)
    {
        if (!auth()->user()->hasPermission('settings_roles.create')) {
            abort(403, 'You do not have permission to create roles.');
        }
        
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'status' => 'required|in:active,inactive',
            'description' => 'nullable|string',
            'permissions' => 'nullable|array',
        ]);

        $role = Role::create([
            'name' => $request->name,
            'description' => $request->description,
            'permissions' => $request->permissions ?? [],
            'status' => $request->status,
        ]);

        // Log activity
        ActivityLogService::logCreate($role, 'roles');

        return redirect()->route('settings.roles')->with('success', 'Role created successfully.');
    }

    /**
     * Display the specified role.
     */
    public function show(Role $role)
    {
        if (!auth()->user()->hasPermission('settings_roles.view')) {
            abort(403, 'You do not have permission to view roles.');
        }
        
        $role->loadCount('users');
        $canEdit = auth()->user()->hasPermission('settings_roles.edit');
        
        return view('settings.roles.show', compact('role', 'canEdit'));
    }

    /**
     * Show the form for editing the specified role.
     */
    public function edit(Role $role)
    {
        if (!auth()->user()->hasPermission('settings_roles.edit')) {
            abort(403, 'You do not have permission to edit roles.');
        }
        
        return view('settings.roles.edit', compact('role'));
    }

    /**
     * Update the specified role.
     */
    public function update(Request $request, Role $role)
    {
        if (!auth()->user()->hasPermission('settings_roles.edit')) {
            abort(403, 'You do not have permission to edit roles.');
        }
        
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'status' => 'required|in:active,inactive',
            'description' => 'nullable|string',
            'permissions' => 'nullable|array',
        ]);

        $oldValues = $role->only(['name', 'status', 'permissions']);
        
        $role->update([
            'name' => $request->name,
            'description' => $request->description,
            'permissions' => $request->permissions ?? [],
            'status' => $request->status,
        ]);

        // Log activity
        ActivityLogService::logUpdate($role, 'roles', $oldValues);

        return redirect()->route('settings.roles')->with('success', 'Role updated successfully.');
    }

    /**
     * Remove the specified role.
     */
    public function destroy(Role $role)
    {
        if (!auth()->user()->hasPermission('settings_roles.delete')) {
            abort(403, 'You do not have permission to delete roles.');
        }
        
        if ($role->users()->count() > 0) {
            return redirect()->route('settings.roles')->with('error', 'Cannot delete role with assigned users.');
        }

        // Log activity before delete
        ActivityLogService::logDelete($role, 'roles');

        $role->delete();

        return redirect()->route('settings.roles')->with('success', 'Role deleted successfully.');
    }
}
