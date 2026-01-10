@extends('layouts.app')

@section('title', 'Two-Factor Authentication')

@section('breadcrumb')
    <x-breadcrumb :items="[
        ['label' => 'Profile', 'url' => route('users.show', auth()->user())],
        ['label' => 'Two-Factor Authentication', 'active' => true]
    ]" />
@endsection

@section('content')
<div class="max-w-2xl mx-auto space-y-4">
    <!-- Success Message -->
    @if(session('success'))
    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
        <div class="flex items-center gap-3">
            <span class="material-symbols-outlined text-green-600">check_circle</span>
            <p class="text-sm text-green-800">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    <!-- Status Card -->
    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-green-50 to-emerald-50">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-green-600 flex items-center justify-center">
                        <span class="material-symbols-outlined text-white" style="font-size: 24px;">verified_user</span>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900" style="font-family: Poppins, sans-serif;">Two-Factor Authentication</h2>
                        <p class="text-xs text-green-600 font-medium">● Enabled and Active</p>
                    </div>
                </div>
                <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full">SECURED</span>
            </div>
        </div>

        <div class="px-6 py-6">
            <p class="text-sm text-gray-700 mb-6">
                Your account is protected with two-factor authentication. You'll need to enter a code from your authenticator app each time you log in.
            </p>

            <!-- Stats -->
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="p-4 bg-blue-50 rounded-lg border border-blue-200">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="material-symbols-outlined text-blue-600" style="font-size: 20px;">calendar_today</span>
                        <span class="text-xs text-blue-600 font-medium">Enabled On</span>
                    </div>
                    <p class="text-sm font-semibold text-gray-900">{{ format_date($user->two_factor_confirmed_at) }}</p>
                    <p class="text-xs text-gray-600">{{ $user->two_factor_confirmed_at->diffForHumans() }}</p>
                </div>
                <div class="p-4 bg-purple-50 rounded-lg border border-purple-200">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="material-symbols-outlined text-purple-600" style="font-size: 20px;">key</span>
                        <span class="text-xs text-purple-600 font-medium">Recovery Codes</span>
                    </div>
                    <p class="text-sm font-semibold text-gray-900">{{ count($recoveryCodes) }} codes</p>
                    <p class="text-xs text-gray-600">Available for backup</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recovery Codes -->
    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-amber-600" style="font-size: 20px;">key</span>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900" style="font-family: Poppins, sans-serif;">Recovery Codes</h3>
                        <p class="text-xs text-gray-600">Use these codes if you lose access to your authenticator app</p>
                    </div>
                </div>
                <button 
                    onclick="showRegenerateModal()" 
                    class="inline-flex items-center gap-2 px-3 py-2 text-xs font-medium text-blue-600 bg-blue-50 rounded hover:bg-blue-100 transition"
                >
                    <span class="material-symbols-outlined" style="font-size: 14px;">refresh</span>
                    Regenerate
                </button>
            </div>
        </div>

        <div class="px-6 py-6">
            <div class="p-4 bg-amber-50 border border-amber-200 rounded-lg mb-4">
                <div class="flex items-start gap-2">
                    <span class="material-symbols-outlined text-amber-600" style="font-size: 18px;">warning</span>
                    <p class="text-xs text-amber-800">
                        <strong>Important:</strong> Store these codes in a secure location. Each code can only be used once. If you lose access to your authenticator app, you can use these codes to sign in.
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                @foreach($recoveryCodes as $code)
                <div class="p-3 bg-gray-50 border border-gray-200 rounded font-mono text-sm text-center select-all">
                    {{ $code }}
                </div>
                @endforeach
            </div>

            <div class="mt-4 flex gap-2">
                <button 
                    onclick="downloadRecoveryCodes()" 
                    class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2 text-xs font-medium text-gray-700 bg-gray-100 rounded hover:bg-gray-200 transition"
                >
                    <span class="material-symbols-outlined" style="font-size: 16px;">download</span>
                    Download Codes
                </button>
                <button 
                    onclick="printRecoveryCodes()" 
                    class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2 text-xs font-medium text-gray-700 bg-gray-100 rounded hover:bg-gray-200 transition"
                >
                    <span class="material-symbols-outlined" style="font-size: 16px;">print</span>
                    Print Codes
                </button>
            </div>
        </div>
    </div>

    <!-- Disable 2FA -->
    <div class="bg-white border border-red-200 rounded-lg overflow-hidden">
        <div class="px-6 py-4 bg-red-50">
            <div class="flex items-center gap-3">
                <span class="material-symbols-outlined text-red-600" style="font-size: 20px;">block</span>
                <div>
                    <h3 class="text-sm font-semibold text-red-900" style="font-family: Poppins, sans-serif;">Disable Two-Factor Authentication</h3>
                    <p class="text-xs text-red-700">Remove the extra security layer from your account</p>
                </div>
            </div>
        </div>

        <div class="px-6 py-4">
            <p class="text-xs text-gray-600 mb-4">
                Disabling two-factor authentication will make your account less secure. You'll only need your password to log in.
            </p>
            <button 
                onclick="showDisableModal()" 
                class="inline-flex items-center gap-2 px-4 py-2 text-xs font-medium text-white bg-red-600 rounded hover:bg-red-700 transition"
            >
                <span class="material-symbols-outlined" style="font-size: 14px;">block</span>
                DISABLE 2FA
            </button>
        </div>
    </div>
</div>

<!-- Disable Modal -->
<div id="disable-modal" class="modal-hidden" style="position: fixed; inset: 0; background: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; z-index: 9999;">
    <div class="bg-white rounded-lg max-w-md w-full mx-4" style="max-height: 90vh; overflow-y: auto;">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900" style="font-family: Poppins, sans-serif;">Disable Two-Factor Authentication</h3>
                <button onclick="closeDisableModal()" class="text-gray-400 hover:text-gray-600">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
        </div>

        <form method="POST" action="{{ route('two-factor.disable') }}">
            @csrf
            @method('DELETE')
            
            <div class="px-6 py-4">
                <p class="text-sm text-gray-700 mb-4">
                    Enter your password to confirm that you want to disable two-factor authentication.
                </p>

                <label for="password-disable" class="block text-xs font-medium text-gray-700 mb-2">Password</label>
                <input 
                    type="password" 
                    id="password-disable" 
                    name="password" 
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500"
                    style="font-family: Poppins, sans-serif;"
                >
                @error('password')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex gap-3">
                <button 
                    type="submit" 
                    class="flex-1 px-4 py-2 bg-red-600 text-white text-sm font-semibold rounded-lg hover:bg-red-700 transition"
                    style="font-family: Poppins, sans-serif;"
                >
                    DISABLE
                </button>
                <button 
                    type="button" 
                    onclick="closeDisableModal()" 
                    class="px-4 py-2 bg-gray-200 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-300 transition"
                    style="font-family: Poppins, sans-serif;"
                >
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Regenerate Modal -->
<div id="regenerate-modal" class="modal-hidden" style="position: fixed; inset: 0; background: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; z-index: 9999;">
    <div class="bg-white rounded-lg max-w-md w-full mx-4">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900" style="font-family: Poppins, sans-serif;">Regenerate Recovery Codes</h3>
                <button onclick="closeRegenerateModal()" class="text-gray-400 hover:text-gray-600">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
        </div>

        <form method="POST" action="{{ route('two-factor.regenerate-codes') }}">
            @csrf
            
            <div class="px-6 py-4">
                <p class="text-sm text-gray-700 mb-4">
                    This will generate new recovery codes and invalidate all existing codes. Enter your password to confirm.
                </p>

                <label for="password-regenerate" class="block text-xs font-medium text-gray-700 mb-2">Password</label>
                <input 
                    type="password" 
                    id="password-regenerate" 
                    name="password" 
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    style="font-family: Poppins, sans-serif;"
                >
            </div>

            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex gap-3">
                <button 
                    type="submit" 
                    class="flex-1 px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition"
                    style="font-family: Poppins, sans-serif;"
                >
                    REGENERATE
                </button>
                <button 
                    type="button" 
                    onclick="closeRegenerateModal()" 
                    class="px-4 py-2 bg-gray-200 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-300 transition"
                    style="font-family: Poppins, sans-serif;"
                >
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<style>
.modal-hidden {
    display: none !important;
}
.modal-visible {
    display: flex !important;
}
</style>

<script>
function showDisableModal() {
    document.getElementById('disable-modal').classList.remove('modal-hidden');
    document.getElementById('disable-modal').classList.add('modal-visible');
}

function closeDisableModal() {
    document.getElementById('disable-modal').classList.add('modal-hidden');
    document.getElementById('disable-modal').classList.remove('modal-visible');
}

function showRegenerateModal() {
    document.getElementById('regenerate-modal').classList.remove('modal-hidden');
    document.getElementById('regenerate-modal').classList.add('modal-visible');
}

function closeRegenerateModal() {
    document.getElementById('regenerate-modal').classList.add('modal-hidden');
    document.getElementById('regenerate-modal').classList.remove('modal-visible');
}

function downloadRecoveryCodes() {
    const codes = @json($recoveryCodes);
    const content = `{{ company_name() }} - Two-Factor Authentication Recovery Codes\n\n` +
                   `Generated: {{ format_datetime(now()) }}\n` +
                   `Account: {{ auth()->user()->email }}\n\n` +
                   `Recovery Codes:\n` +
                   codes.map((code, i) => `${i + 1}. ${code}`).join('\n') +
                   `\n\nKeep these codes in a secure location. Each code can only be used once.`;
    
    const blob = new Blob([content], { type: 'text/plain' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = '2fa-recovery-codes.txt';
    a.click();
    window.URL.revokeObjectURL(url);
}

function printRecoveryCodes() {
    window.print();
}
</script>

<style media="print">
@media print {
    body * {
        visibility: hidden;
    }
    .bg-white.border.border-gray-200:nth-child(2),
    .bg-white.border.border-gray-200:nth-child(2) * {
        visibility: visible;
    }
    .bg-white.border.border-gray-200:nth-child(2) {
        position: absolute;
        left: 0;
        top: 0;
    }
}
</style>
@endsection
