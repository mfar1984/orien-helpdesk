<?php

namespace App\Http\Controllers;

use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use PragmaRX\Google2FA\Google2FA;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class TwoFactorController extends Controller
{
    protected $google2fa;

    public function __construct()
    {
        $this->google2fa = new Google2FA();
    }

    /**
     * Show the 2FA setup page.
     */
    public function show()
    {
        $user = auth()->user();
        
        // If already enabled, show status page
        if ($user->twoFactorEnabled()) {
            return view('auth.two-factor.show', [
                'user' => $user,
                'recoveryCodes' => $user->recoveryCodes(),
            ]);
        }

        // Generate new secret
        $secret = $this->google2fa->generateSecretKey();
        session(['2fa_secret' => $secret]);

        // Generate QR code data
        $qrCodeUri = $this->google2fa->getQRCodeUrl(
            company_name() ?: config('app.name'),
            $user->email,
            $secret
        );

        // Generate SVG QR code
        $renderer = new ImageRenderer(
            new RendererStyle(200),
            new SvgImageBackEnd()
        );
        $writer = new Writer($renderer);
        $qrCodeSvg = $writer->writeString($qrCodeUri);

        return view('auth.two-factor.setup', [
            'qrCodeSvg' => $qrCodeSvg,
            'secret' => $secret,
        ]);
    }

    /**
     * Enable 2FA for the user.
     */
    public function enable(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        $user = auth()->user();
        $secret = session('2fa_secret');

        if (!$secret) {
            return back()->withErrors(['code' => 'Session expired. Please try again.']);
        }

        // Verify the code
        $valid = $this->google2fa->verifyKey($secret, $request->code);

        if (!$valid) {
            return back()->withErrors(['code' => 'Invalid verification code.']);
        }

        // Generate recovery codes
        $recoveryCodes = $this->generateRecoveryCodes();

        // Save 2FA settings
        $user->forceFill([
            'two_factor_secret' => encrypt($secret),
            'two_factor_enabled' => true,
            'two_factor_confirmed_at' => now(),
        ])->save();

        $user->replaceRecoveryCodes($recoveryCodes->toArray());

        session()->forget('2fa_secret');
        
        ActivityLogService::log('2fa_enable', 'auth', 'Two-factor authentication enabled');

        return redirect()->route('two-factor.show')->with('success', 'Two-factor authentication has been enabled!');
    }

    /**
     * Disable 2FA for the user.
     */
    public function disable(Request $request)
    {
        $request->validate([
            'password' => 'required|string',
        ]);

        $user = auth()->user();

        // Verify password
        if (!\Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Invalid password.']);
        }

        // Disable 2FA
        $user->forceFill([
            'two_factor_enabled' => false,
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
        ])->save();
        
        ActivityLogService::log('2fa_disable', 'auth', 'Two-factor authentication disabled');

        return redirect()->route('two-factor.show')->with('success', 'Two-factor authentication has been disabled.');
    }

    /**
     * Regenerate recovery codes.
     */
    public function regenerateCodes(Request $request)
    {
        $request->validate([
            'password' => 'required|string',
        ]);

        $user = auth()->user();

        if (!\Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Invalid password.']);
        }

        $recoveryCodes = $this->generateRecoveryCodes();
        $user->replaceRecoveryCodes($recoveryCodes->toArray());
        
        ActivityLogService::log('2fa_regenerate_codes', 'auth', 'Two-factor recovery codes regenerated');

        return back()->with('success', 'Recovery codes have been regenerated.');
    }

    /**
     * Show 2FA verification page.
     */
    public function verify()
    {
        if (!session('2fa:user:id')) {
            return redirect()->route('login');
        }

        return view('auth.two-factor.verify');
    }

    /**
     * Verify 2FA code during login.
     */
    public function verifyCode(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $userId = session('2fa:user:id');
        if (!$userId) {
            return redirect()->route('login')->withErrors(['code' => 'Session expired.']);
        }

        $user = \App\Models\User::findOrFail($userId);

        // Try regular code first
        $secret = decrypt($user->two_factor_secret);
        $valid = $this->google2fa->verifyKey($secret, $request->code);

        // If not valid, try recovery codes
        if (!$valid) {
            $recoveryCodes = $user->recoveryCodes();
            $valid = in_array($request->code, $recoveryCodes);

            if ($valid) {
                // Remove used recovery code
                $remainingCodes = array_diff($recoveryCodes, [$request->code]);
                $user->replaceRecoveryCodes(array_values($remainingCodes));
            }
        }

        if (!$valid) {
            return back()->withErrors(['code' => 'Invalid verification code.']);
        }

        // Login the user
        auth()->login($user);
        $request->session()->regenerate();

        // Clear 2FA session
        session()->forget('2fa:user:id');

        return redirect()->intended(route('dashboard'));
    }

    /**
     * Generate recovery codes.
     */
    protected function generateRecoveryCodes(): Collection
    {
        return Collection::times(8, function () {
            return Str::random(10) . '-' . Str::random(10);
        });
    }
}
