@php
    $settings = \App\Models\Setting::all()->pluck('value', 'key');
    $systemName = $settings['system_name'] ?? 'ORIEN';
    $companyShortName = $settings['company_short_name'] ?? 'ORIEN';
    $favicon = $settings['favicon'] ?? null;
    $logo = $settings['logo'] ?? null;
    $heroImage = $settings['hero_image'] ?? null;
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - {{ $systemName }}</title>
    @if($favicon)
        <link rel="icon" type="image/png" href="{{ asset('storage/' . $favicon) }}">
    @endif
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            @if($heroImage)
            background: url('{{ asset('storage/' . $heroImage) }}') center/cover no-repeat fixed;
            @else
            background: linear-gradient(135deg, #0a9343 0%, #27388f 100%);
            @endif
            position: relative;
            overflow: hidden;
        }

        /* Animated background overlay */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(10, 147, 67, 0.85) 0%, rgba(39, 56, 143, 0.85) 100%);
            z-index: 1;
        }

        /* Floating particles */
        .particles {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 2;
            overflow: hidden;
        }

        .particle {
            position: absolute;
            width: 10px;
            height: 10px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 15s infinite;
        }

        .particle:nth-child(1) { left: 10%; animation-delay: 0s; animation-duration: 20s; }
        .particle:nth-child(2) { left: 20%; animation-delay: 2s; animation-duration: 25s; }
        .particle:nth-child(3) { left: 30%; animation-delay: 4s; animation-duration: 18s; }
        .particle:nth-child(4) { left: 40%; animation-delay: 1s; animation-duration: 22s; }
        .particle:nth-child(5) { left: 50%; animation-delay: 3s; animation-duration: 19s; }
        .particle:nth-child(6) { left: 60%; animation-delay: 5s; animation-duration: 21s; }
        .particle:nth-child(7) { left: 70%; animation-delay: 2s; animation-duration: 24s; }
        .particle:nth-child(8) { left: 80%; animation-delay: 4s; animation-duration: 17s; }
        .particle:nth-child(9) { left: 90%; animation-delay: 1s; animation-duration: 23s; }

        @keyframes float {
            0%, 100% {
                transform: translateY(100vh) scale(0);
                opacity: 0;
            }
            10% {
                opacity: 1;
            }
            90% {
                opacity: 1;
            }
            100% {
                transform: translateY(-100vh) scale(1);
                opacity: 0;
            }
        }

        /* Login container */
        .login-container {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 360px;
            padding: 15px;
            animation: slideUp 0.8s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Glossy card */
        .login-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 30px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 
                0 25px 50px rgba(0, 0, 0, 0.3),
                inset 0 1px 0 rgba(255, 255, 255, 0.3),
                inset 0 -1px 0 rgba(255, 255, 255, 0.1);
            position: relative;
            overflow: hidden;
        }

        /* Glossy shine effect */
        .login-card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(
                45deg,
                transparent 40%,
                rgba(255, 255, 255, 0.1) 45%,
                rgba(255, 255, 255, 0.2) 50%,
                rgba(255, 255, 255, 0.1) 55%,
                transparent 60%
            );
            animation: shine 4s infinite;
            pointer-events: none;
        }

        @keyframes shine {
            0% {
                transform: translateX(-100%) translateY(-100%) rotate(45deg);
            }
            100% {
                transform: translateX(100%) translateY(100%) rotate(45deg);
            }
        }

        /* Logo */
        .logo-container {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo-container img {
            height: 40px;
            filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.3));
            animation: pulse 2s infinite;
        }

        .logo-fallback {
            width: 48px;
            height: 48px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            animation: pulse 2s infinite;
        }

        .logo-fallback span {
            color: #fff;
            font-size: 26px;
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
        }

        .welcome-text {
            text-align: center;
            margin-bottom: 20px;
        }

        .welcome-text h1 {
            color: #fff;
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 5px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .welcome-text p {
            color: rgba(255, 255, 255, 0.8);
            font-size: 12px;
        }

        /* Error message */
        .error-message {
            background: rgba(239, 68, 68, 0.2);
            border: 1px solid rgba(239, 68, 68, 0.4);
            color: #fecaca;
            padding: 10px 12px;
            border-radius: 10px;
            font-size: 11px;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .error-message .material-symbols-outlined {
            font-size: 16px;
            color: #f87171;
        }

        /* Form */
        .form-group {
            margin-bottom: 14px;
            position: relative;
        }

        .form-group label {
            display: block;
            color: rgba(255, 255, 255, 0.9);
            font-size: 10px;
            font-weight: 500;
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper .material-symbols-outlined {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.5);
            font-size: 16px;
            transition: color 0.3s;
        }

        .form-group input {
            width: 100%;
            padding: 10px 12px 10px 40px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            color: #fff;
            font-size: 12px;
            font-family: 'Poppins', sans-serif;
            transition: all 0.3s;
        }

        .form-group input::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }

        .form-group input:focus {
            outline: none;
            background: rgba(255, 255, 255, 0.15);
            border-color: rgba(255, 255, 255, 0.4);
            box-shadow: 0 0 20px rgba(255, 255, 255, 0.1);
        }

        .form-group input:focus + .input-wrapper .material-symbols-outlined,
        .form-group input:focus ~ .material-symbols-outlined {
            color: #fff;
        }

        .form-group input.error {
            border-color: rgba(239, 68, 68, 0.6);
            background: rgba(239, 68, 68, 0.1);
        }

        /* Remember & Forgot */
        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 18px;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 6px;
            color: rgba(255, 255, 255, 0.8);
            font-size: 11px;
            cursor: pointer;
        }

        .remember-me input {
            width: 14px;
            height: 14px;
            accent-color: #0a9343;
        }

        .forgot-link {
            color: rgba(255, 255, 255, 0.8);
            font-size: 11px;
            text-decoration: none;
            transition: color 0.3s;
        }

        .forgot-link:hover {
            color: #fff;
        }

        /* Submit button */
        .submit-btn {
            width: 100%;
            padding: 11px;
            background: linear-gradient(135deg, #0a9343 0%, #27388f 100%);
            border: none;
            border-radius: 10px;
            color: #fff;
            font-size: 12px;
            font-weight: 600;
            font-family: 'Poppins', sans-serif;
            cursor: pointer;
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .submit-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .submit-btn:hover::before {
            left: 100%;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(10, 147, 67, 0.4);
        }

        /* Register link */
        .register-link {
            text-align: center;
            margin-top: 18px;
            color: rgba(255, 255, 255, 0.7);
            font-size: 11px;
        }

        .register-link a {
            color: #fff;
            text-decoration: none;
            font-weight: 600;
            transition: opacity 0.3s;
        }

        .register-link a:hover {
            opacity: 0.8;
        }

        /* Footer */
        .footer {
            text-align: center;
            margin-top: 15px;
            color: rgba(255, 255, 255, 0.5);
            font-size: 9px;
        }

        .footer .legal-links {
            margin-top: 6px;
            display: flex;
            justify-content: center;
            gap: 12px;
        }

        .footer .legal-links a {
            color: rgba(255, 255, 255, 0.5);
            text-decoration: none;
            transition: color 0.3s;
        }

        .footer .legal-links a:hover {
            color: rgba(255, 255, 255, 0.8);
        }

        /* Responsive */
        @media (max-width: 480px) {
            .login-container {
                padding: 15px;
            }
            .login-card {
                padding: 30px 25px;
            }
            .welcome-text h1 {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <!-- Floating particles -->
    <div class="particles">
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
    </div>

    <div class="login-container">
        <div class="login-card">
            <div class="logo-container">
                @if($logo)
                    <img src="{{ asset('storage/' . $logo) }}" alt="{{ $companyShortName }}">
                @else
                    <div class="logo-fallback">
                        <span class="material-symbols-outlined">deployed_code</span>
                    </div>
                @endif
            </div>

            <div class="welcome-text">
                <h1>Welcome Back</h1>
                <p>Sign in to continue to {{ $companyShortName }}</p>
            </div>

            @if($errors->any())
            <div class="error-message">
                <span class="material-symbols-outlined">error</span>
                {{ $errors->first() }}
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                
                <div class="form-group">
                    <label>Email Address</label>
                    <div class="input-wrapper">
                        <span class="material-symbols-outlined">mail</span>
                        <input type="email" name="email" value="{{ old('email') }}" 
                               class="{{ $errors->has('email') ? 'error' : '' }}"
                               placeholder="Enter your email" required autofocus>
                    </div>
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <div class="input-wrapper">
                        <span class="material-symbols-outlined">lock</span>
                        <input type="password" name="password" 
                               class="{{ $errors->has('password') ? 'error' : '' }}"
                               placeholder="Enter your password" required>
                    </div>
                </div>

                <div class="form-options">
                    <label class="remember-me">
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        Remember me
                    </label>
                    <a href="{{ route('password.request') }}" class="forgot-link">Forgot Password?</a>
                </div>

                <button type="submit" class="submit-btn">Sign In</button>

                <div class="register-link">
                    Don't have an account? <a href="{{ route('register') }}">Register Now</a>
                </div>
            </form>
        </div>

        <div class="footer">
            <p>© {{ date('Y') }} {{ $companyShortName }}. All rights reserved.</p>
            <div class="legal-links">
                <a href="#">Privacy</a>
                <a href="#">Terms</a>
                <a href="#">Disclaimer</a>
            </div>
        </div>
    </div>
</body>
</html>
