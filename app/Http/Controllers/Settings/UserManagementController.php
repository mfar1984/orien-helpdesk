<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Services\ActivityLogService;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserManagementController extends Controller
{
    /**
     * Map tab to permission key.
     */
    private function getTabPermission($tab)
    {
        return match ($tab) {
            'administrator' => 'settings_users_admin',
            'agents' => 'settings_users_agents',
            'customers' => 'settings_users_customers',
            default => 'settings_users_admin',
        };
    }

    /**
     * Display users management with tabs.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $tab = $request->get('tab', 'administrator');
        
        $tabs = [
            'administrator' => 'Administrator',
            'agents' => 'Agents',
            'customers' => 'Customers',
        ];
        
        // Check permission for the current tab
        $permissionKey = $this->getTabPermission($tab);
        if (!$user->hasPermission($permissionKey . '.view')) {
            // Try to find another tab user has access to
            foreach (array_keys($tabs) as $tabKey) {
                $perm = $this->getTabPermission($tabKey);
                if ($user->hasPermission($perm . '.view')) {
                    return redirect()->route('settings.users', ['tab' => $tabKey]);
                }
            }
            // No access to any user tab
            abort(403, 'You do not have permission to access User Management.');
        }

        // Map tab to role slug
        $roleSlugMap = [
            'administrator' => 'administrator',
            'agents' => 'agent',
            'customers' => 'customer',
        ];

        $query = User::with('role');

        // Filter by role based on tab
        if (isset($roleSlugMap[$tab])) {
            $query->whereHas('role', function ($q) use ($roleSlugMap, $tab) {
                $q->where('slug', $roleSlugMap[$tab]);
            });
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $perPage = (int) setting('pagination_size', 15);
        $users = $query->orderBy('created_at', 'desc')->paginate($perPage);

        // Filter tabs based on permissions
        $filteredTabs = [];
        foreach ($tabs as $tabKey => $tabLabel) {
            $perm = $this->getTabPermission($tabKey);
            if ($user->hasPermission($perm . '.view')) {
                $filteredTabs[$tabKey] = $tabLabel;
            }
        }

        // Get permissions for current tab
        $canCreate = $user->hasPermission($permissionKey . '.create');
        $canEdit = $user->hasPermission($permissionKey . '.edit');
        $canDelete = $user->hasPermission($permissionKey . '.delete');
        $canManage = $user->hasPermission($permissionKey . '.manage');

        return view('settings.users', [
            'currentTab' => $tab,
            'tabs' => $filteredTabs,
            'users' => $users,
            'canCreate' => $canCreate,
            'canEdit' => $canEdit,
            'canDelete' => $canDelete,
            'canManage' => $canManage,
        ]);
    }

    /**
     * Show the form for creating a new user.
     */
    public function create(Request $request)
    {
        $tab = $request->get('tab', 'administrator');
        $permissionKey = $this->getTabPermission($tab);
        
        if (!auth()->user()->hasPermission($permissionKey . '.create')) {
            abort(403, 'You do not have permission to create users.');
        }
        
        $roles = Role::where('status', 'active')->get();
        return view('settings.users.create', compact('roles'));
    }

    /**
     * Store a newly created user.
     */
    public function store(Request $request)
    {
        // Determine which user type is being created based on role
        $roleId = $request->input('role_id');
        $role = Role::find($roleId);
        $permissionKey = match ($role?->slug ?? 'customer') {
            'administrator' => 'settings_users_admin',
            'agent' => 'settings_users_agents',
            default => 'settings_users_customers',
        };
        
        if (!auth()->user()->hasPermission($permissionKey . '.create')) {
            abort(403, 'You do not have permission to create this type of user.');
        }
        $minPasswordLength = setting('password_min_length', 8);
        
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'username' => 'required|string|max:255|unique:users,username',
            'password' => 'required|string|min:' . $minPasswordLength . '|confirmed',
            'role_id' => 'required|exists:roles,id',
            'status' => 'required|in:active,inactive',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'mobile' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'postcode' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:2',
            'company_name' => 'nullable|string|max:255',
            'company_registration' => 'nullable|string|max:100',
            'company_phone' => 'nullable|string|max:20',
            'company_email' => 'nullable|email|max:255',
            'company_address' => 'nullable|string',
            'company_website' => 'nullable|url|max:255',
            'industry' => 'nullable|string|max:100',
        ]);

        // Determine user_type based on role
        $role = Role::find($request->role_id);
        $userType = match ($role?->slug ?? 'customer') {
            'administrator' => 'administrator',
            'agent' => 'agent',
            default => 'customer',
        };
        
        $user = User::create([
            'name' => $request->first_name . ' ' . $request->last_name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
            'user_type' => $userType,
            'status' => $request->status,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'mobile' => $request->mobile,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'postcode' => $request->postcode,
            'country' => $request->country,
            'company_name' => $request->company_name,
            'company_registration' => $request->company_registration,
            'company_phone' => $request->company_phone,
            'company_email' => $request->company_email,
            'company_address' => $request->company_address,
            'company_website' => $request->company_website,
            'industry' => $request->industry,
        ]);

        // Log activity
        ActivityLogService::logCreate($user, 'users');

        // Send welcome email notification (if enabled in settings)
        NotificationService::sendWelcomeEmail($user);

        return redirect()->route('settings.users')->with('success', 'User created successfully.');
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        $permissionKey = match ($user->role?->slug ?? 'customer') {
            'administrator' => 'settings_users_admin',
            'agent' => 'settings_users_agents',
            default => 'settings_users_customers',
        };
        
        if (!auth()->user()->hasPermission($permissionKey . '.edit')) {
            abort(403, 'You do not have permission to edit this user.');
        }
        
        $roles = Role::where('status', 'active')->get();
        return view('settings.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified user.
     */
    public function update(Request $request, User $user)
    {
        $permissionKey = match ($user->role?->slug ?? 'customer') {
            'administrator' => 'settings_users_admin',
            'agent' => 'settings_users_agents',
            default => 'settings_users_customers',
        };
        
        if (!auth()->user()->hasPermission($permissionKey . '.edit')) {
            abort(403, 'You do not have permission to edit this user.');
        }
        
        $minPasswordLength = setting('password_min_length', 8);
        
        $request->validate([
            'email' => 'required|email|unique:users,email,' . $user->id,
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'password' => 'nullable|string|min:' . $minPasswordLength . '|confirmed',
            'role_id' => 'required|exists:roles,id',
            'status' => 'required|in:active,inactive',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
        ]);

        $data = [
            'name' => $request->first_name . ' ' . $request->last_name,
            'username' => $request->username,
            'email' => $request->email,
            'role_id' => $request->role_id,
            'status' => $request->status,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'mobile' => $request->mobile,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'postcode' => $request->postcode,
            'country' => $request->country,
            'company_name' => $request->company_name,
            'company_registration' => $request->company_registration,
            'company_phone' => $request->company_phone,
            'company_email' => $request->company_email,
            'company_address' => $request->company_address,
            'company_website' => $request->company_website,
            'industry' => $request->industry,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $oldValues = $user->only(['name', 'email', 'status', 'role_id']);
        $user->update($data);

        // Log activity
        ActivityLogService::logUpdate($user, 'users', $oldValues);

        return redirect()->route('settings.users')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user.
     */
    public function destroy(User $user)
    {
        $permissionKey = match ($user->role?->slug ?? 'customer') {
            'administrator' => 'settings_users_admin',
            'agent' => 'settings_users_agents',
            default => 'settings_users_customers',
        };
        
        if (!auth()->user()->hasPermission($permissionKey . '.delete')) {
            abort(403, 'You do not have permission to delete this user.');
        }
        
        // Log activity before delete
        ActivityLogService::logDelete($user, 'users');
        
        $user->delete();

        return redirect()->route('settings.users')->with('success', 'User deleted successfully.');
    }

    /**
     * Lock the specified user account.
     */
    public function lock(User $user)
    {
        $permissionKey = match ($user->role?->slug ?? 'customer') {
            'administrator' => 'settings_users_admin',
            'agent' => 'settings_users_agents',
            default => 'settings_users_customers',
        };
        
        if (!auth()->user()->hasPermission($permissionKey . '.manage')) {
            abort(403, 'You do not have permission to lock this user.');
        }
        
        $user->lock();
        
        // Log the action
        ActivityLogService::logLock($user);

        return redirect()->back()->with('success', 'User account has been locked.');
    }

    /**
     * Unlock the specified user account.
     */
    public function unlock(User $user)
    {
        $permissionKey = match ($user->role?->slug ?? 'customer') {
            'administrator' => 'settings_users_admin',
            'agent' => 'settings_users_agents',
            default => 'settings_users_customers',
        };
        
        if (!auth()->user()->hasPermission($permissionKey . '.manage')) {
            abort(403, 'You do not have permission to unlock this user.');
        }
        
        $user->unlock();
        
        // Log the action
        ActivityLogService::logUnlock($user);

        return redirect()->back()->with('success', 'User account has been unlocked.');
    }

    /**
     * Suspend the specified user account.
     */
    public function suspend(Request $request, User $user)
    {
        $permissionKey = match ($user->role?->slug ?? 'customer') {
            'administrator' => 'settings_users_admin',
            'agent' => 'settings_users_agents',
            default => 'settings_users_customers',
        };
        
        if (!auth()->user()->hasPermission($permissionKey . '.manage')) {
            abort(403, 'You do not have permission to suspend this user.');
        }
        
        $reason = $request->input('reason');
        $user->suspend($reason);
        
        // Log the action
        ActivityLogService::logSuspend($user, $reason);

        return redirect()->back()->with('success', 'User account has been suspended.');
    }

    /**
     * Unsuspend the specified user account.
     */
    public function unsuspend(User $user)
    {
        $permissionKey = match ($user->role?->slug ?? 'customer') {
            'administrator' => 'settings_users_admin',
            'agent' => 'settings_users_agents',
            default => 'settings_users_customers',
        };
        
        if (!auth()->user()->hasPermission($permissionKey . '.manage')) {
            abort(403, 'You do not have permission to unsuspend this user.');
        }
        
        $user->unsuspend();
        
        // Log the action
        ActivityLogService::logUnsuspend($user);

        return redirect()->back()->with('success', 'User account has been unsuspended.');
    }
}
