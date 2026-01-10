@extends('layouts.app')

@section('title', 'General Config')

@section('breadcrumb')
    <x-breadcrumb :items="[
        ['label' => 'Platform Settings'],
        ['label' => 'General Config', 'active' => true]
    ]" />
@endsection

@php
    $canEdit = auth()->user()->hasPermission('settings_general.edit');
@endphp

@section('content')
<div class="bg-white border border-gray-200">
    <!-- Header -->
    <div class="px-6 py-4 flex items-center justify-between border-b border-gray-100">
        <div>
            <h2 class="text-base font-semibold text-gray-900" style="font-family: Poppins, sans-serif;">General Configuration</h2>
            <p class="text-xs text-gray-500 mt-0.5">Configure system-wide settings</p>
        </div>
        @if($canEdit)
        <button type="submit" form="config-form" class="inline-flex items-center px-3 bg-blue-600 text-white text-xs font-medium rounded hover:bg-blue-700 transition" style="min-height: 32px; font-family: Poppins, sans-serif; font-size: 11px;">
            <span class="material-symbols-outlined mr-1" style="font-size: 14px;">save</span>
            SAVE CHANGES
        </button>
        @else
        <span class="inline-flex items-center px-3 py-1 bg-gray-100 text-gray-500 text-xs font-medium rounded" style="font-family: Poppins, sans-serif; font-size: 11px;">
            <span class="material-symbols-outlined mr-1" style="font-size: 14px;">visibility</span>
            VIEW ONLY
        </span>
        @endif
    </div>

    @if(session('success'))
    <div class="mx-6 mt-4 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded text-xs">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="mx-6 mt-4 px-4 py-3 bg-red-50 border border-red-200 text-red-700 rounded text-xs">
        {{ session('error') }}
    </div>
    @endif

    <!-- Settings Form -->
    <form id="config-form" action="{{ route('settings.general.save') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="p-6 space-y-6">
            
            <!-- Branding & Images -->
            <div class="border border-gray-200 rounded-lg overflow-hidden">
                <div class="px-4 py-3 bg-gray-50 border-b border-gray-100 flex items-center gap-2">
                    <span class="material-symbols-outlined text-gray-500" style="font-size: 18px;">image</span>
                    <h3 class="text-xs font-semibold text-gray-900" style="font-family: Poppins, sans-serif;">Branding & Images</h3>
                </div>
                <div class="p-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Favicon/Icon -->
                        <div>
                            <label class="block text-gray-700 mb-2" style="font-size: 11px; font-family: Poppins, sans-serif;">
                                Favicon (Browser Icon) 
                                <span class="text-gray-400 font-normal">- 32x32px, .ico/.png</span>
                            </label>
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center transition {{ $canEdit ? 'hover:border-blue-400 cursor-pointer' : 'bg-gray-50 cursor-not-allowed opacity-75' }}" style="height: 130px;" @if($canEdit) onclick="document.getElementById('favicon_upload').click()" @endif>
                                <input type="file" id="favicon_upload" name="favicon" accept=".ico,.png" class="hidden" onchange="previewImage(this, 'favicon_preview')" {{ $canEdit ? '' : 'disabled' }}>
                                <div id="favicon_preview" class="flex items-center justify-center" style="height: 48px;">
                                    @if($settings['favicon'] ?? false)
                                        <img src="{{ asset('storage/' . $settings['favicon']) }}" alt="Favicon" style="max-height: 48px; max-width: 48px; object-fit: contain;">
                                    @else
                                        <div class="w-12 h-12 bg-gray-100 rounded flex items-center justify-center">
                                            <span class="material-symbols-outlined text-gray-400" style="font-size: 24px;">deployed_code</span>
                                        </div>
                                    @endif
                                </div>
                                <p class="text-xs text-gray-500 mt-3" style="font-family: Poppins, sans-serif;">{{ $canEdit ? 'Click to upload favicon' : 'Favicon preview' }}</p>
                                <p class="text-xs text-gray-400 mt-1" style="font-family: Poppins, sans-serif;">Shows in browser tab</p>
                            </div>
                            <div class="h-6 mt-2">
                                @if(($settings['favicon'] ?? false) && $canEdit)
                                    <label class="flex items-center gap-2 text-xs text-gray-500 cursor-pointer" style="font-family: Poppins, sans-serif;">
                                        <input type="checkbox" name="remove_favicon" value="1" class="w-3 h-3">
                                        Remove current favicon
                                    </label>
                                @endif
                            </div>
                        </div>

                        <!-- Logo -->
                        <div>
                            <label class="block text-gray-700 mb-2" style="font-size: 11px; font-family: Poppins, sans-serif;">
                                Logo 
                                <span class="text-gray-400 font-normal">- 200x60px, .png/.svg</span>
                            </label>
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center transition {{ $canEdit ? 'hover:border-blue-400 cursor-pointer' : 'bg-gray-50 cursor-not-allowed opacity-75' }}" style="height: 130px;" @if($canEdit) onclick="document.getElementById('logo_upload').click()" @endif>
                                <input type="file" id="logo_upload" name="logo" accept=".png,.svg,.jpg,.jpeg" class="hidden" onchange="previewImage(this, 'logo_preview')" {{ $canEdit ? '' : 'disabled' }}>
                                <div id="logo_preview" class="flex items-center justify-center" style="height: 48px;">
                                    @if($settings['logo'] ?? false)
                                        <img src="{{ asset('storage/' . $settings['logo']) }}" alt="Logo" style="max-height: 48px; max-width: 150px; object-fit: contain;">
                                    @else
                                        <div class="h-12 bg-gray-100 rounded flex items-center justify-center px-4">
                                            <span class="material-symbols-outlined text-gray-400 mr-1" style="font-size: 24px;">deployed_code</span>
                                            <span class="text-gray-400 font-semibold">ORIEN</span>
                                        </div>
                                    @endif
                                </div>
                                <p class="text-xs text-gray-500 mt-3" style="font-family: Poppins, sans-serif;">{{ $canEdit ? 'Click to upload logo' : 'Logo preview' }}</p>
                                <p class="text-xs text-gray-400 mt-1" style="font-family: Poppins, sans-serif;">Shows in sidebar & login page</p>
                            </div>
                            <div class="h-6 mt-2">
                                @if(($settings['logo'] ?? false) && $canEdit)
                                    <label class="flex items-center gap-2 text-xs text-gray-500 cursor-pointer" style="font-family: Poppins, sans-serif;">
                                        <input type="checkbox" name="remove_logo" value="1" class="w-3 h-3">
                                        Remove current logo
                                    </label>
                                @endif
                            </div>
                        </div>

                        <!-- Hero Image -->
                        <div>
                            <label class="block text-gray-700 mb-2" style="font-size: 11px; font-family: Poppins, sans-serif;">
                                Login Hero Image 
                                <span class="text-gray-400 font-normal">- 1920x1080px, .jpg/.png</span>
                            </label>
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center transition {{ $canEdit ? 'hover:border-blue-400 cursor-pointer' : 'bg-gray-50 cursor-not-allowed opacity-75' }}" style="height: 130px;" @if($canEdit) onclick="document.getElementById('hero_upload').click()" @endif>
                                <input type="file" id="hero_upload" name="hero_image" accept=".jpg,.jpeg,.png,.webp" class="hidden" onchange="previewImage(this, 'hero_preview')" {{ $canEdit ? '' : 'disabled' }}>
                                <div id="hero_preview" class="flex items-center justify-center" style="height: 48px;">
                                    @if($settings['hero_image'] ?? false)
                                        <img src="{{ asset('storage/' . $settings['hero_image']) }}" alt="Hero" style="max-height: 48px; max-width: 85px; object-fit: cover; border-radius: 4px;">
                                    @else
                                        <div class="bg-gradient-to-br from-blue-500 to-blue-700 rounded flex items-center justify-center" style="width: 85px; height: 48px;">
                                            <span class="material-symbols-outlined text-white/50" style="font-size: 20px;">landscape</span>
                                        </div>
                                    @endif
                                </div>
                                <p class="text-xs text-gray-500 mt-3" style="font-family: Poppins, sans-serif;">{{ $canEdit ? 'Click to upload hero image' : 'Hero image preview' }}</p>
                                <p class="text-xs text-gray-400 mt-1" style="font-family: Poppins, sans-serif;">Shows on login page (75% left side)</p>
                            </div>
                            <div class="h-6 mt-2">
                                @if(($settings['hero_image'] ?? false) && $canEdit)
                                    <label class="flex items-center gap-2 text-xs text-gray-500 cursor-pointer" style="font-family: Poppins, sans-serif;">
                                        <input type="checkbox" name="remove_hero_image" value="1" class="w-3 h-3">
                                        Remove current hero image
                                    </label>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Company Information -->
            <div class="border border-gray-200 rounded-lg overflow-hidden">
                <div class="px-4 py-3 bg-gray-50 border-b border-gray-100 flex items-center gap-2">
                    <span class="material-symbols-outlined text-gray-500" style="font-size: 18px;">business</span>
                    <h3 class="text-xs font-semibold text-gray-900" style="font-family: Poppins, sans-serif;">Company Information</h3>
                </div>
                <div class="p-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div class="md:col-span-2 lg:col-span-3">
                            <label class="block text-gray-700 mb-1" style="font-size: 11px; font-family: Poppins, sans-serif;">System Name <span class="text-red-500">*</span></label>
                            <input type="text" name="system_name" value="{{ $settings['system_name'] ?? 'ORIEN Helpdesk' }}" 
                                   class="w-full px-3 border border-gray-300 rounded text-xs focus:outline-none focus:border-blue-500 {{ !$canEdit ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                                   style="min-height: 32px; font-size: 11px; font-family: Poppins, sans-serif;" {{ $canEdit ? 'required' : 'disabled' }} placeholder="e.g. ORIEN Helpdesk">
                            <p class="text-xs text-gray-400 mt-1" style="font-family: Poppins, sans-serif;">Displayed in the system topbar</p>
                        </div>
                        <div>
                            <label class="block text-gray-700 mb-1" style="font-size: 11px; font-family: Poppins, sans-serif;">Company Name <span class="text-red-500">*</span></label>
                            <input type="text" name="company_name" value="{{ $settings['company_name'] ?? 'ORIEN Sdn Bhd' }}" 
                                   class="w-full px-3 border border-gray-300 rounded text-xs focus:outline-none focus:border-blue-500 {{ !$canEdit ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                                   style="min-height: 32px; font-size: 11px; font-family: Poppins, sans-serif;" {{ $canEdit ? 'required' : 'disabled' }}>
                        </div>
                        <div>
                            <label class="block text-gray-700 mb-1" style="font-size: 11px; font-family: Poppins, sans-serif;">Short Name <span class="text-red-500">*</span></label>
                            <input type="text" name="company_short_name" value="{{ $settings['company_short_name'] ?? 'ORIEN' }}" 
                                   class="w-full px-3 border border-gray-300 rounded text-xs focus:outline-none focus:border-blue-500 {{ !$canEdit ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                                   style="min-height: 32px; font-size: 11px; font-family: Poppins, sans-serif;" {{ $canEdit ? 'required' : 'disabled' }}>
                        </div>
                        <div>
                            <label class="block text-gray-700 mb-1" style="font-size: 11px; font-family: Poppins, sans-serif;">SSM Number</label>
                            <input type="text" name="company_ssm_number" value="{{ $settings['company_ssm_number'] ?? '' }}" 
                                   class="w-full px-3 border border-gray-300 rounded text-xs focus:outline-none focus:border-blue-500 {{ !$canEdit ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                                   style="min-height: 32px; font-size: 11px; font-family: Poppins, sans-serif;" {{ !$canEdit ? 'disabled' : '' }} placeholder="e.g. 123456-X">
                        </div>
                        <div>
                            <label class="block text-gray-700 mb-1" style="font-size: 11px; font-family: Poppins, sans-serif;">Email</label>
                            <input type="email" name="company_email" value="{{ $settings['company_email'] ?? '' }}" 
                                   class="w-full px-3 border border-gray-300 rounded text-xs focus:outline-none focus:border-blue-500 {{ !$canEdit ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                                   style="min-height: 32px; font-size: 11px; font-family: Poppins, sans-serif;" {{ !$canEdit ? 'disabled' : '' }} placeholder="info@company.com">
                        </div>
                        <div>
                            <label class="block text-gray-700 mb-1" style="font-size: 11px; font-family: Poppins, sans-serif;">Phone</label>
                            <input type="text" name="company_phone" value="{{ $settings['company_phone'] ?? '' }}" 
                                   class="w-full px-3 border border-gray-300 rounded text-xs focus:outline-none focus:border-blue-500 {{ !$canEdit ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                                   style="min-height: 32px; font-size: 11px; font-family: Poppins, sans-serif;" {{ !$canEdit ? 'disabled' : '' }} placeholder="+60 3-1234 5678">
                        </div>
                        <div>
                            <label class="block text-gray-700 mb-1" style="font-size: 11px; font-family: Poppins, sans-serif;">Website</label>
                            <input type="url" name="company_website" value="{{ $settings['company_website'] ?? '' }}" 
                                   class="w-full px-3 border border-gray-300 rounded text-xs focus:outline-none focus:border-blue-500 {{ !$canEdit ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                                   style="min-height: 32px; font-size: 11px; font-family: Poppins, sans-serif;" {{ !$canEdit ? 'disabled' : '' }} placeholder="https://www.company.com">
                        </div>
                        <div class="md:col-span-2 lg:col-span-3">
                            <label class="block text-gray-700 mb-1" style="font-size: 11px; font-family: Poppins, sans-serif;">Address</label>
                            <textarea name="company_address" rows="2"
                                      class="w-full px-3 py-2 border border-gray-300 rounded text-xs focus:outline-none focus:border-blue-500 {{ !$canEdit ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                                      style="font-size: 11px; font-family: Poppins, sans-serif;" {{ !$canEdit ? 'disabled' : '' }} placeholder="Enter company address">{{ $settings['company_address'] ?? '' }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Regional Settings -->
            <div class="border border-gray-200 rounded-lg overflow-hidden">
                <div class="px-4 py-3 bg-gray-50 border-b border-gray-100 flex items-center gap-2">
                    <span class="material-symbols-outlined text-gray-500" style="font-size: 18px;">schedule</span>
                    <h3 class="text-xs font-semibold text-gray-900" style="font-family: Poppins, sans-serif;">Date & Time Settings</h3>
                </div>
                <div class="p-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-gray-700 mb-1" style="font-size: 11px; font-family: Poppins, sans-serif;">Timezone <span class="text-red-500">*</span></label>
                            <select name="timezone" class="w-full px-3 border border-gray-300 rounded text-xs focus:outline-none focus:border-blue-500 {{ !$canEdit ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                                    style="min-height: 32px; font-size: 11px; font-family: Poppins, sans-serif;" {{ $canEdit ? 'required' : 'disabled' }}>
                                @php
                                    $timezones = [
                                        'Asia/Kuala_Lumpur' => 'Asia/Kuala_Lumpur (GMT+8)',
                                        'Asia/Singapore' => 'Asia/Singapore (GMT+8)',
                                        'Asia/Jakarta' => 'Asia/Jakarta (GMT+7)',
                                        'Asia/Bangkok' => 'Asia/Bangkok (GMT+7)',
                                        'Asia/Hong_Kong' => 'Asia/Hong_Kong (GMT+8)',
                                        'Asia/Tokyo' => 'Asia/Tokyo (GMT+9)',
                                        'Asia/Dubai' => 'Asia/Dubai (GMT+4)',
                                        'Europe/London' => 'Europe/London (GMT+0)',
                                        'Europe/Paris' => 'Europe/Paris (GMT+1)',
                                        'America/New_York' => 'America/New_York (GMT-5)',
                                        'America/Los_Angeles' => 'America/Los_Angeles (GMT-8)',
                                        'Australia/Sydney' => 'Australia/Sydney (GMT+11)',
                                        'UTC' => 'UTC (GMT+0)',
                                    ];
                                @endphp
                                @foreach($timezones as $value => $label)
                                    <option value="{{ $value }}" {{ ($settings['timezone'] ?? 'Asia/Kuala_Lumpur') == $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-gray-700 mb-1" style="font-size: 11px; font-family: Poppins, sans-serif;">Date Format <span class="text-red-500">*</span></label>
                            <select name="date_format" class="w-full px-3 border border-gray-300 rounded text-xs focus:outline-none focus:border-blue-500 {{ !$canEdit ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                                    style="min-height: 32px; font-size: 11px; font-family: Poppins, sans-serif;" {{ $canEdit ? 'required' : 'disabled' }}>
                                @php
                                    $dateFormats = [
                                        'd/m/Y' => 'DD/MM/YYYY (31/12/2024)',
                                        'm/d/Y' => 'MM/DD/YYYY (12/31/2024)',
                                        'Y-m-d' => 'YYYY-MM-DD (2024-12-31)',
                                        'd-m-Y' => 'DD-MM-YYYY (31-12-2024)',
                                        'd M Y' => 'DD Mon YYYY (31 Dec 2024)',
                                        'd F Y' => 'DD Month YYYY (31 December 2024)',
                                        'M d, Y' => 'Mon DD, YYYY (Dec 31, 2024)',
                                        'F d, Y' => 'Month DD, YYYY (December 31, 2024)',
                                    ];
                                @endphp
                                @foreach($dateFormats as $value => $label)
                                    <option value="{{ $value }}" {{ ($settings['date_format'] ?? 'd/m/Y') == $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-gray-700 mb-1" style="font-size: 11px; font-family: Poppins, sans-serif;">Time Format <span class="text-red-500">*</span></label>
                            <select name="time_format" class="w-full px-3 border border-gray-300 rounded text-xs focus:outline-none focus:border-blue-500 {{ !$canEdit ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                                    style="min-height: 32px; font-size: 11px; font-family: Poppins, sans-serif;" {{ $canEdit ? 'required' : 'disabled' }}>
                                @php
                                    $timeFormats = [
                                        'H:i' => '24-hour (14:30)',
                                        'H:i:s' => '24-hour with seconds (14:30:00)',
                                        'h:i A' => '12-hour (2:30 PM)',
                                        'h:i:s A' => '12-hour with seconds (2:30:00 PM)',
                                        'g:i a' => '12-hour lowercase (2:30 pm)',
                                        'g:i:s a' => '12-hour lowercase with seconds (2:30:00 pm)',
                                    ];
                                @endphp
                                @foreach($timeFormats as $value => $label)
                                    <option value="{{ $value }}" {{ ($settings['time_format'] ?? 'H:i') == $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Security Settings -->
            <div class="border border-gray-200 rounded-lg overflow-hidden">
                <div class="px-4 py-3 bg-gray-50 border-b border-gray-100 flex items-center gap-2">
                    <span class="material-symbols-outlined text-gray-500" style="font-size: 18px;">security</span>
                    <h3 class="text-xs font-semibold text-gray-900" style="font-family: Poppins, sans-serif;">Security Settings</h3>
                </div>
                <div class="p-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-gray-700 mb-1" style="font-size: 11px; font-family: Poppins, sans-serif;">Session Timeout (minutes) <span class="text-red-500">*</span></label>
                            <input type="number" name="session_timeout" value="{{ $settings['session_timeout'] ?? 120 }}" 
                                   class="w-full px-3 border border-gray-300 rounded text-xs focus:outline-none focus:border-blue-500 {{ !$canEdit ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                                   style="min-height: 32px; font-size: 11px; font-family: Poppins, sans-serif;" min="15" max="480" {{ $canEdit ? 'required' : 'disabled' }}>
                            <p class="text-xs text-gray-400 mt-1" style="font-family: Poppins, sans-serif;">15 - 480 minutes</p>
                        </div>
                        <div>
                            <label class="block text-gray-700 mb-1" style="font-size: 11px; font-family: Poppins, sans-serif;">Min Password Length <span class="text-red-500">*</span></label>
                            <input type="number" name="password_min_length" value="{{ $settings['password_min_length'] ?? 8 }}" 
                                   class="w-full px-3 border border-gray-300 rounded text-xs focus:outline-none focus:border-blue-500 {{ !$canEdit ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                                   style="min-height: 32px; font-size: 11px; font-family: Poppins, sans-serif;" min="6" max="32" {{ $canEdit ? 'required' : 'disabled' }}>
                            <p class="text-xs text-gray-400 mt-1" style="font-family: Poppins, sans-serif;">6 - 32 characters</p>
                        </div>
                        <div>
                            <label class="block text-gray-700 mb-1" style="font-size: 11px; font-family: Poppins, sans-serif;">Max Login Attempts <span class="text-red-500">*</span></label>
                            <input type="number" name="max_login_attempts" value="{{ $settings['max_login_attempts'] ?? 5 }}" 
                                   class="w-full px-3 border border-gray-300 rounded text-xs focus:outline-none focus:border-blue-500 {{ !$canEdit ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                                   style="min-height: 32px; font-size: 11px; font-family: Poppins, sans-serif;" min="3" max="10" {{ $canEdit ? 'required' : 'disabled' }}>
                            <p class="text-xs text-gray-400 mt-1" style="font-family: Poppins, sans-serif;">Before lockout</p>
                        </div>
                        <div>
                            <label class="block text-gray-700 mb-1" style="font-size: 11px; font-family: Poppins, sans-serif;">Lockout Duration (min) <span class="text-red-500">*</span></label>
                            <input type="number" name="lockout_duration" value="{{ $settings['lockout_duration'] ?? 15 }}" 
                                   class="w-full px-3 border border-gray-300 rounded text-xs focus:outline-none focus:border-blue-500 {{ !$canEdit ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                                   style="min-height: 32px; font-size: 11px; font-family: Poppins, sans-serif;" min="5" max="60" {{ $canEdit ? 'required' : 'disabled' }}>
                            <p class="text-xs text-gray-400 mt-1" style="font-family: Poppins, sans-serif;">5 - 60 minutes</p>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <label class="flex items-center gap-3 p-3 border border-gray-200 rounded {{ $canEdit ? 'cursor-pointer hover:bg-gray-50' : 'cursor-not-allowed bg-gray-50' }}">
                            <input type="checkbox" name="require_2fa" value="1" 
                                   class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" {{ ($settings['require_2fa'] ?? false) ? 'checked' : '' }} {{ !$canEdit ? 'disabled' : '' }}>
                            <div>
                                <span class="text-gray-700" style="font-size: 11px; font-family: Poppins, sans-serif;">Require Two-Factor Authentication</span>
                                <p class="text-xs text-gray-400" style="font-family: Poppins, sans-serif;">All users must enable 2FA to access the system</p>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Email Notification Settings -->
            <div class="border border-gray-200 rounded-lg overflow-hidden">
                <div class="px-4 py-3 bg-gray-50 border-b border-gray-100 flex items-center gap-2">
                    <span class="material-symbols-outlined text-gray-500" style="font-size: 18px;">notifications</span>
                    <h3 class="text-xs font-semibold text-gray-900" style="font-family: Poppins, sans-serif;">Email Notification Settings</h3>
                </div>
                <div class="p-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                        <label class="flex items-center gap-3 p-3 border border-gray-200 rounded {{ $canEdit ? 'cursor-pointer hover:bg-gray-50' : 'cursor-not-allowed bg-gray-50' }}">
                            <input type="checkbox" name="email_ticket_created" value="1" {{ ($settings['email_ticket_created'] ?? true) ? 'checked' : '' }}
                                   class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" {{ !$canEdit ? 'disabled' : '' }}>
                            <div>
                                <span class="text-gray-700" style="font-size: 11px; font-family: Poppins, sans-serif;">Ticket Created</span>
                                <p class="text-xs text-gray-400" style="font-family: Poppins, sans-serif;">When new ticket is created</p>
                            </div>
                        </label>
                        <label class="flex items-center gap-3 p-3 border border-gray-200 rounded {{ $canEdit ? 'cursor-pointer hover:bg-gray-50' : 'cursor-not-allowed bg-gray-50' }}">
                            <input type="checkbox" name="email_ticket_replied" value="1" {{ ($settings['email_ticket_replied'] ?? true) ? 'checked' : '' }}
                                   class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" {{ !$canEdit ? 'disabled' : '' }}>
                            <div>
                                <span class="text-gray-700" style="font-size: 11px; font-family: Poppins, sans-serif;">Ticket Replied</span>
                                <p class="text-xs text-gray-400" style="font-family: Poppins, sans-serif;">When ticket receives reply</p>
                            </div>
                        </label>
                        <label class="flex items-center gap-3 p-3 border border-gray-200 rounded {{ $canEdit ? 'cursor-pointer hover:bg-gray-50' : 'cursor-not-allowed bg-gray-50' }}">
                            <input type="checkbox" name="email_ticket_status_changed" value="1" {{ ($settings['email_ticket_status_changed'] ?? true) ? 'checked' : '' }}
                                   class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" {{ !$canEdit ? 'disabled' : '' }}>
                            <div>
                                <span class="text-gray-700" style="font-size: 11px; font-family: Poppins, sans-serif;">Status Changed</span>
                                <p class="text-xs text-gray-400" style="font-family: Poppins, sans-serif;">When ticket status changes</p>
                            </div>
                        </label>
                        <label class="flex items-center gap-3 p-3 border border-gray-200 rounded {{ $canEdit ? 'cursor-pointer hover:bg-gray-50' : 'cursor-not-allowed bg-gray-50' }}">
                            <input type="checkbox" name="email_ticket_assigned" value="1" {{ ($settings['email_ticket_assigned'] ?? true) ? 'checked' : '' }}
                                   class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" {{ !$canEdit ? 'disabled' : '' }}>
                            <div>
                                <span class="text-gray-700" style="font-size: 11px; font-family: Poppins, sans-serif;">Ticket Assigned</span>
                                <p class="text-xs text-gray-400" style="font-family: Poppins, sans-serif;">When ticket is assigned</p>
                            </div>
                        </label>
                        <label class="flex items-center gap-3 p-3 border border-gray-200 rounded {{ $canEdit ? 'cursor-pointer hover:bg-gray-50' : 'cursor-not-allowed bg-gray-50' }}">
                            <input type="checkbox" name="email_user_created" value="1" {{ ($settings['email_user_created'] ?? true) ? 'checked' : '' }}
                                   class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" {{ !$canEdit ? 'disabled' : '' }}>
                            <div>
                                <span class="text-gray-700" style="font-size: 11px; font-family: Poppins, sans-serif;">User Created</span>
                                <p class="text-xs text-gray-400" style="font-family: Poppins, sans-serif;">When new user is created</p>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- System Defaults -->
            <div class="border border-gray-200 rounded-lg overflow-hidden">
                <div class="px-4 py-3 bg-gray-50 border-b border-gray-100 flex items-center gap-2">
                    <span class="material-symbols-outlined text-gray-500" style="font-size: 18px;">tune</span>
                    <h3 class="text-xs font-semibold text-gray-900" style="font-family: Poppins, sans-serif;">System Defaults</h3>
                </div>
                <div class="p-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-gray-700 mb-1" style="font-size: 11px; font-family: Poppins, sans-serif;">Pagination Size <span class="text-red-500">*</span></label>
                            <select name="pagination_size" class="w-full px-3 border border-gray-300 rounded text-xs focus:outline-none focus:border-blue-500 {{ !$canEdit ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                                    style="min-height: 32px; font-size: 11px; font-family: Poppins, sans-serif;" {{ $canEdit ? 'required' : 'disabled' }}>
                                @php $paginationSize = $settings['pagination_size'] ?? 15; @endphp
                                <option value="10" {{ $paginationSize == 10 ? 'selected' : '' }}>10 items per page</option>
                                <option value="15" {{ $paginationSize == 15 ? 'selected' : '' }}>15 items per page</option>
                                <option value="25" {{ $paginationSize == 25 ? 'selected' : '' }}>25 items per page</option>
                                <option value="50" {{ $paginationSize == 50 ? 'selected' : '' }}>50 items per page</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-gray-700 mb-1" style="font-size: 11px; font-family: Poppins, sans-serif;">Auto-Close Tickets (days) <span class="text-red-500">*</span></label>
                            <input type="number" name="ticket_auto_close_days" value="{{ $settings['ticket_auto_close_days'] ?? 7 }}" 
                                   class="w-full px-3 border border-gray-300 rounded text-xs focus:outline-none focus:border-blue-500 {{ !$canEdit ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                                   style="min-height: 32px; font-size: 11px; font-family: Poppins, sans-serif;" min="1" max="30" {{ $canEdit ? 'required' : 'disabled' }}>
                            <p class="text-xs text-gray-400 mt-1" style="font-family: Poppins, sans-serif;">After resolved status</p>
                        </div>
                        <div>
                            <label class="block text-gray-700 mb-1" style="font-size: 11px; font-family: Poppins, sans-serif;">Max Attachment Size (MB) <span class="text-red-500">*</span></label>
                            <input type="number" name="attachment_max_size" value="{{ $settings['attachment_max_size'] ?? 10 }}" 
                                   class="w-full px-3 border border-gray-300 rounded text-xs focus:outline-none focus:border-blue-500 {{ !$canEdit ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                                   style="min-height: 32px; font-size: 11px; font-family: Poppins, sans-serif;" min="1" max="50" {{ $canEdit ? 'required' : 'disabled' }}>
                            <p class="text-xs text-gray-400 mt-1" style="font-family: Poppins, sans-serif;">Per file upload</p>
                        </div>
                        <div>
                            <label class="block text-gray-700 mb-1" style="font-size: 11px; font-family: Poppins, sans-serif;">Allowed File Types <span class="text-red-500">*</span></label>
                            <input type="text" name="allowed_file_types" value="{{ $settings['allowed_file_types'] ?? 'pdf,doc,docx,xls,xlsx,jpg,jpeg,png,gif,zip' }}" 
                                   class="w-full px-3 border border-gray-300 rounded text-xs focus:outline-none focus:border-blue-500 {{ !$canEdit ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                                   style="min-height: 32px; font-size: 11px; font-family: Poppins, sans-serif;" {{ $canEdit ? 'required' : 'disabled' }}>
                            <p class="text-xs text-gray-400 mt-1" style="font-family: Poppins, sans-serif;">Comma separated</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>

<script>
function previewImage(input, previewId) {
    const preview = document.getElementById(previewId);
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            let html = '';
            if (previewId === 'favicon_preview') {
                html = `<img src="${e.target.result}" alt="Preview" style="max-height: 48px; max-width: 48px; object-fit: contain;">`;
            } else if (previewId === 'logo_preview') {
                html = `<img src="${e.target.result}" alt="Preview" style="max-height: 48px; max-width: 150px; object-fit: contain;">`;
            } else {
                html = `<img src="${e.target.result}" alt="Preview" style="max-height: 48px; max-width: 85px; object-fit: cover; border-radius: 4px;">`;
            }
            preview.innerHTML = html;
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
