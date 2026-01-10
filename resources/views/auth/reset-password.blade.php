<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Orien</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Poppins', sans-serif; min-height: 100vh; display: flex; }
        .login-container { display: flex; width: 100%; min-height: 100vh; }
        .hero-section { width: 75%; background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); position: relative; display: flex; align-items: center; justify-content: center; }
        .hero-overlay { position: absolute; inset: 0; background: rgba(0, 0, 0, 0.1); }
        .separator { width: 3px; background: linear-gradient(to bottom, transparent 0%, #e5e7eb 10%, #3b82f6 50%, #e5e7eb 90%, transparent 100%); position: relative; }
        .separator::before { content: ''; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 24px; height: 24px; background: #3b82f6; border-radius: 50%; box-shadow: 0 0 0 8px rgba(59, 130, 246, 0.3); animation: pulse 2s ease-in-out infinite; }
        @keyframes pulse { 0%, 100% { transform: translate(-50%, -50%) scale(1); box-shadow: 0 0 0 8px rgba(59, 130, 246, 0.3); } 50% { transform: translate(-50%, -50%) scale(1.2); box-shadow: 0 0 0 14px rgba(59, 130, 246, 0.15); } }
        .login-section { width: 25%; min-width: 380px; background: #ffffff; display: flex; flex-direction: column; justify-content: center; padding: 3rem; }
        .login-header { text-align: center; margin-bottom: 2rem; display: flex; flex-direction: column; justify-content: center; align-items: center; }
        .login-logo { width: 60px; height: 60px; background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-bottom: 1rem; }
        .login-logo span { color: white; font-size: 32px; }
        .login-title { font-size: 1.5rem; font-weight: 700; color: #111827; }
        .login-subtitle { font-size: 0.75rem; color: #6b7280; margin-top: 0.5rem; }
        .login-form { width: 100%; }
        .form-group { margin-bottom: 1.25rem; }
        .form-label { display: block; font-size: 0.6875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem; }
        .form-input { width: 100%; padding: 0.75rem 1rem; font-size: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem; transition: all 0.2s ease; outline: none; }
        .form-input:focus { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1); }
        .form-input::placeholder { color: #9ca3af; }
        .form-input.error { border-color: #ef4444; }
        .input-icon-wrapper { position: relative; }
        .input-icon { position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%); color: #9ca3af; font-size: 18px; }
        .input-icon-wrapper .form-input { padding-left: 2.5rem; }
        .login-btn { width: 100%; padding: 0.875rem; font-size: 0.75rem; font-weight: 600; color: white; background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); border: none; border-radius: 0.375rem; cursor: pointer; transition: all 0.2s ease; text-transform: uppercase; letter-spacing: 0.05em; }
        .login-btn:hover { background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%); box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4); transform: translateY(-1px); }
        .error-message { background: #fef2f2; border: 1px solid #fecaca; color: #dc2626; padding: 0.75rem 1rem; border-radius: 0.375rem; font-size: 0.6875rem; margin-bottom: 1.25rem; }
        .success-message { background: #f0fdf4; border: 1px solid #bbf7d0; color: #16a34a; padding: 0.75rem 1rem; border-radius: 0.375rem; font-size: 0.6875rem; margin-bottom: 1.25rem; }
        .register-link { text-align: center; margin-top: 1.5rem; font-size: 0.6875rem; color: #6b7280; }
        .register-link a { color: #3b82f6; text-decoration: none; font-weight: 500; }
        .register-link a:hover { color: #1d4ed8; }
        .login-footer { text-align: center; margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid #e5e7eb; }
        .login-footer p { font-size: 0.625rem; color: #9ca3af; }
        .legal-links { margin-top: 0.75rem; display: flex; justify-content: center; align-items: center; gap: 0.5rem; }
        .legal-links a { font-size: 0.625rem; color: #6b7280; text-decoration: none; transition: color 0.2s ease; }
        .legal-links a:hover { color: #3b82f6; }
        .legal-links span { font-size: 0.625rem; color: #d1d5db; }
        @media (max-width: 1024px) { .hero-section { width: 60%; } .login-section { width: 40%; min-width: 320px; } }
        @media (max-width: 768px) { .login-container { flex-direction: column; } .hero-section { width: 100%; min-height: 150px; } .separator { width: 100%; height: 1px; background: linear-gradient(to right, transparent 0%, #e5e7eb 10%, #3b82f6 50%, #e5e7eb 90%, transparent 100%); } .separator::before { display: none; } .login-section { width: 100%; min-width: unset; padding: 2rem; } }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="hero-section"><div class="hero-overlay"></div></div>
        <div class="separator"></div>
        <div class="login-section">
            <div class="login-header">
                <div class="login-logo"><span class="material-symbols-outlined">lock_reset</span></div>
                <h1 class="login-title">Reset Password</h1>
                <p class="login-subtitle">Enter your new password</p>
            </div>
            
            @if(session('status'))
            <div class="success-message">{{ session('status') }}</div>
            @endif
            
            @if($errors->any())
            <div class="error-message">{{ $errors->first() }}</div>
            @endif
            
            <form method="POST" action="{{ route('password.update') }}" class="login-form">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                
                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <div class="input-icon-wrapper">
                        <span class="material-symbols-outlined input-icon">mail</span>
                        <input type="email" name="email" value="{{ old('email', $email) }}" class="form-input {{ $errors->has('email') ? 'error' : '' }}" placeholder="Enter your email" required autofocus>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">New Password</label>
                    <div class="input-icon-wrapper">
                        <span class="material-symbols-outlined input-icon">lock</span>
                        <input type="password" name="password" class="form-input {{ $errors->has('password') ? 'error' : '' }}" placeholder="Enter new password" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Confirm Password</label>
                    <div class="input-icon-wrapper">
                        <span class="material-symbols-outlined input-icon">lock</span>
                        <input type="password" name="password_confirmation" class="form-input" placeholder="Confirm new password" required>
                    </div>
                </div>
                
                <button type="submit" class="login-btn">Reset Password</button>
            </form>
            
            <div class="register-link"><a href="{{ route('login') }}">← Back to login</a></div>
            
            <div class="login-footer">
                <p>© {{ date('Y') }} Orien. All rights reserved.</p>
                <div class="legal-links"><a href="#">Privacy</a><span>/</span><a href="#">Terms</a><span>/</span><a href="#">Disclaimer</a></div>
            </div>
        </div>
    </div>
</body>
</html>
