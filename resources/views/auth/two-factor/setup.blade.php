@extends('layouts.app')

@section('title', 'Enable Two-Factor Authentication')

@section('breadcrumb')
    <x-breadcrumb :items="[
        ['label' => 'Profile', 'url' => route('users.show', auth()->user())],
        ['label' => 'Two-Factor Authentication', 'active' => true]
    ]" />
@endsection

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center">
                    <span class="material-symbols-outlined text-white" style="font-size: 24px;">shield</span>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-gray-900" style="font-family: Poppins, sans-serif;">Enable Two-Factor Authentication</h2>
                    <p class="text-xs text-gray-600">Add an extra layer of security to your account</p>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="px-6 py-6">
            <!-- Step 1: Install Authenticator -->
            <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <div class="flex items-start gap-3">
                    <span class="material-symbols-outlined text-blue-600" style="font-size: 24px;">info</span>
                    <div>
                        <h3 class="text-sm font-semibold text-blue-900 mb-2" style="font-family: Poppins, sans-serif;">Step 1: Install an Authenticator App</h3>
                        <p class="text-xs text-blue-800 mb-2">Download and install one of these authenticator apps:</p>
                        <ul class="text-xs text-blue-800 space-y-1 ml-4">
                            <li>• Google Authenticator (Android, iOS)</li>
                            <li>• Microsoft Authenticator (Android, iOS)</li>
                            <li>• Authy (Android, iOS, Desktop)</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Step 2: Scan QR Code -->
            <div class="mb-6">
                <h3 class="text-sm font-semibold text-gray-900 mb-3" style="font-family: Poppins, sans-serif;">Step 2: Scan QR Code</h3>
                <p class="text-xs text-gray-600 mb-4">Open your authenticator app and scan this QR code:</p>
                
                <div class="flex justify-center p-6 bg-white border-2 border-dashed border-gray-300 rounded-lg">
                    <div class="w-48 h-48">
                        {!! $qrCodeSvg !!}
                    </div>
                </div>

                <div class="mt-4 p-3 bg-gray-50 rounded border border-gray-200">
                    <p class="text-xs text-gray-600 mb-2 font-medium">Can't scan? Enter this code manually:</p>
                    <code class="block text-center text-sm font-mono bg-white px-3 py-2 rounded border border-gray-300 select-all">{{ $secret }}</code>
                </div>
            </div>

            <!-- Step 3: Verify -->
            <div class="mb-4">
                <h3 class="text-sm font-semibold text-gray-900 mb-3" style="font-family: Poppins, sans-serif;">Step 3: Verify Code</h3>
                <p class="text-xs text-gray-600 mb-4">Enter the 6-digit code from your authenticator app to complete setup:</p>

                <form method="POST" action="{{ route('two-factor.enable') }}">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="code" class="block text-xs font-medium text-gray-700 mb-2">Verification Code</label>
                        <input 
                            type="text" 
                            id="code" 
                            name="code" 
                            required 
                            maxlength="6"
                            pattern="[0-9]{6}"
                            placeholder="000000"
                            inputmode="numeric"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg text-center text-lg font-semibold tracking-widest focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            style="font-family: 'Courier New', monospace; letter-spacing: 8px;"
                        >
                        @error('code')
                            <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex gap-3">
                        <button 
                            type="submit" 
                            class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-3 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition"
                            style="font-family: Poppins, sans-serif;"
                        >
                            <span class="material-symbols-outlined" style="font-size: 18px;">check_circle</span>
                            ENABLE 2FA
                        </button>
                        <a 
                            href="{{ route('users.show', auth()->user()) }}" 
                            class="px-4 py-3 bg-gray-100 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-200 transition text-center"
                            style="font-family: Poppins, sans-serif;"
                        >
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
