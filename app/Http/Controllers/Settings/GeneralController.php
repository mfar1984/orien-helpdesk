<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GeneralController extends Controller
{
    /**
     * Display general settings.
     */
    public function index()
    {
        $user = auth()->user();
        
        // Check permission
        if (!$user->hasPermission('settings_general.view')) {
            abort(403, 'You do not have permission to access General Settings.');
        }
        
        $settings = $this->getSettings();
        return view('settings.general', ['settings' => $settings]);
    }
    
    /**
     * Save general settings.
     */
    public function save(Request $request)
    {
        $user = auth()->user();
        
        // Check permission
        if (!$user->hasPermission('settings_general.edit')) {
            abort(403, 'You do not have permission to edit General Settings.');
        }
        $validated = $request->validate([
            // Branding & Images
            'favicon' => 'nullable|file|mimes:ico,png|max:512',
            'logo' => 'nullable|file|mimes:png,svg,jpg,jpeg|max:2048',
            'hero_image' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:5120',
            
            // Company Information
            'system_name' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'company_short_name' => 'required|string|max:50',
            'company_ssm_number' => 'nullable|string|max:50',
            'company_email' => 'nullable|email|max:255',
            'company_phone' => 'nullable|string|max:50',
            'company_website' => 'nullable|url|max:255',
            'company_address' => 'nullable|string',
            
            // Date & Time Settings
            'timezone' => 'required|string',
            'date_format' => 'required|string',
            'time_format' => 'required|string',
            
            // Security Settings
            'session_timeout' => 'required|integer|min:15|max:480',
            'password_min_length' => 'required|integer|min:6|max:32',
            'max_login_attempts' => 'required|integer|min:3|max:10',
            'lockout_duration' => 'required|integer|min:5|max:60',
            'require_2fa' => 'nullable|boolean',
            
            // Email Notifications
            'email_ticket_created' => 'nullable|boolean',
            'email_ticket_replied' => 'nullable|boolean',
            'email_ticket_status_changed' => 'nullable|boolean',
            'email_ticket_assigned' => 'nullable|boolean',
            'email_user_created' => 'nullable|boolean',
            
            // System Defaults
            'pagination_size' => 'required|integer|in:10,15,25,50',
            'ticket_auto_close_days' => 'required|integer|min:1|max:30',
            'attachment_max_size' => 'required|integer|min:1|max:50',
            'allowed_file_types' => 'required|string',
        ]);
        
        // Handle file uploads
        $this->handleImageUploads($request);
        
        // Remove file fields from validated data
        unset($validated['favicon'], $validated['logo'], $validated['hero_image']);
        
        // Convert checkboxes to boolean
        $validated['require_2fa'] = $request->boolean('require_2fa');
        $validated['email_ticket_created'] = $request->boolean('email_ticket_created');
        $validated['email_ticket_replied'] = $request->boolean('email_ticket_replied');
        $validated['email_ticket_status_changed'] = $request->boolean('email_ticket_status_changed');
        $validated['email_ticket_assigned'] = $request->boolean('email_ticket_assigned');
        $validated['email_user_created'] = $request->boolean('email_user_created');
        
        // Save each setting (no json_encode needed - model casts handle it)
        foreach ($validated as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value, 'group' => $this->getSettingGroup($key)]
            );
        }
        
        // Log activity
        ActivityLogService::log('update', 'settings', 'General settings updated');
        
        return redirect()->route('settings.general')
            ->with('success', 'Settings saved successfully.');
    }
    
    /**
     * Handle image uploads for branding.
     */
    private function handleImageUploads(Request $request)
    {
        $currentSettings = $this->getSettings();
        
        // Handle Favicon
        if ($request->boolean('remove_favicon') && isset($currentSettings['favicon'])) {
            Storage::disk('public')->delete($currentSettings['favicon']);
            Setting::where('key', 'favicon')->delete();
        } elseif ($request->hasFile('favicon')) {
            // Delete old favicon
            if (isset($currentSettings['favicon'])) {
                Storage::disk('public')->delete($currentSettings['favicon']);
            }
            $path = $request->file('favicon')->store('branding', 'public');
            Setting::updateOrCreate(
                ['key' => 'favicon'],
                ['value' => $path, 'group' => 'branding']
            );
        }
        
        // Handle Logo
        if ($request->boolean('remove_logo') && isset($currentSettings['logo'])) {
            Storage::disk('public')->delete($currentSettings['logo']);
            Setting::where('key', 'logo')->delete();
        } elseif ($request->hasFile('logo')) {
            // Delete old logo
            if (isset($currentSettings['logo'])) {
                Storage::disk('public')->delete($currentSettings['logo']);
            }
            $path = $request->file('logo')->store('branding', 'public');
            Setting::updateOrCreate(
                ['key' => 'logo'],
                ['value' => $path, 'group' => 'branding']
            );
        }
        
        // Handle Hero Image
        if ($request->boolean('remove_hero_image') && isset($currentSettings['hero_image'])) {
            Storage::disk('public')->delete($currentSettings['hero_image']);
            Setting::where('key', 'hero_image')->delete();
        } elseif ($request->hasFile('hero_image')) {
            // Delete old hero image
            if (isset($currentSettings['hero_image'])) {
                Storage::disk('public')->delete($currentSettings['hero_image']);
            }
            $path = $request->file('hero_image')->store('branding', 'public');
            Setting::updateOrCreate(
                ['key' => 'hero_image'],
                ['value' => $path, 'group' => 'branding']
            );
        }
    }
    
    /**
     * Get all settings as key-value array.
     */
    private function getSettings()
    {
        $settings = Setting::all()->pluck('value', 'key');
        
        $defaults = [
            // Branding
            'favicon' => null,
            'logo' => null,
            'hero_image' => null,
            // Company
            'system_name' => 'ORIEN Helpdesk',
            'company_name' => 'ORIEN Sdn Bhd',
            'company_short_name' => 'ORIEN',
            'company_ssm_number' => '',
            'company_email' => '',
            'company_phone' => '',
            'company_website' => '',
            'company_address' => '',
            'timezone' => 'Asia/Kuala_Lumpur',
            'date_format' => 'd/m/Y',
            'time_format' => 'H:i',
            'session_timeout' => 120,
            'password_min_length' => 8,
            'max_login_attempts' => 5,
            'lockout_duration' => 15,
            'require_2fa' => false,
            'email_ticket_created' => true,
            'email_ticket_replied' => true,
            'email_ticket_status_changed' => true,
            'email_ticket_assigned' => true,
            'email_user_created' => true,
            'pagination_size' => 15,
            'ticket_auto_close_days' => 7,
            'attachment_max_size' => 10,
            'allowed_file_types' => 'pdf,doc,docx,xls,xlsx,jpg,jpeg,png,gif,zip',
        ];
        
        // Merge with defaults (no json_decode needed - model casts handle it)
        $result = [];
        foreach ($defaults as $key => $default) {
            $result[$key] = $settings->has($key) ? $settings[$key] : $default;
        }
        
        return $result;
    }
    
    /**
     * Determine setting group based on key.
     */
    private function getSettingGroup(string $key): string
    {
        if (str_starts_with($key, 'company_') || $key === 'system_name') {
            return 'company';
        } elseif (in_array($key, ['timezone', 'date_format', 'time_format'])) {
            return 'datetime';
        } elseif (in_array($key, ['session_timeout', 'password_min_length', 'max_login_attempts', 'lockout_duration', 'require_2fa'])) {
            return 'security';
        } elseif (str_starts_with($key, 'email_')) {
            return 'notifications';
        } else {
            return 'system';
        }
    }
}
