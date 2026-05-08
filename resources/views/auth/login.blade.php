<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | Tailor</title>
    <link rel="icon" type="image/png" href="{{ asset('images/tailor-logo.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --gold: #c89b2c;
            --gold-deep: #a57d1e;
            --ink: #111111;
            --panel: rgba(28, 24, 20, 0.72);
            --panel-soft: rgba(200, 155, 44, 0.12);
            --text: #f5ead7;
            --muted: #d6c2a3;
        }

        * {
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            color: var(--text);
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background:
                radial-gradient(circle at top, rgba(200, 155, 44, 0.14), transparent 30%),
                linear-gradient(135deg, #2a241d 0%, #1b1815 45%, #120f0d 100%);
            position: relative;
        }

        body::before {
            content: "";
            position: absolute;
            inset: 0;
            background:
                linear-gradient(rgba(22, 16, 10, 0.34), rgba(22, 16, 10, 0.34)),
                url("{{ asset('images/login-tailor-bg-v2.png') }}") center center / cover no-repeat;
            opacity: 0.5;
            filter: saturate(0.88) brightness(0.96);
            pointer-events: none;
        }

        body::after {
            display: none;
        }

        h1, h2, h3 {
            font-family: Georgia, "Times New Roman", serif;
        }

        .login-shell {
            width: min(100%, 1240px);
            padding: 24px;
            position: relative;
            z-index: 1;
        }

        .login-shell::before {
            content: "";
            position: absolute;
            inset: 40px 90px;
            background:
                radial-gradient(circle at center, rgba(255, 255, 255, 0.12), transparent 62%);
            opacity: 1;
            pointer-events: none;
            z-index: 0;
        }

        .login-stage {
            position: relative;
            padding: 0;
            isolation: isolate;
            z-index: 1;
        }

        .login-stage::before {
            content: "";
            position: absolute;
            inset: -32px;
            background:
                radial-gradient(circle at center, rgba(255, 255, 255, 0.16), transparent 60%);
            opacity: 1;
            pointer-events: none;
            z-index: -1;
        }

        .login-card {
            position: relative;
            max-width: 520px;
            margin: 0 auto;
            padding: 26px 28px 22px;
            border-radius: 32px;
            background: linear-gradient(180deg, rgba(33, 28, 24, 0.78), rgba(18, 15, 13, 0.74));
            border: 1px solid rgba(222, 188, 121, 0.24);
            box-shadow:
                inset 0 1px 0 rgba(255, 244, 220, 0.12),
                0 28px 70px rgba(0, 0, 0, 0.36);
            backdrop-filter: blur(16px);
        }

        .brand-medallion {
            width: 112px;
            height: 112px;
            margin: -72px auto 14px;
            border-radius: 50%;
            padding: 11px;
            background:
                radial-gradient(circle at 50% 45%, #f6e4a4 0%, #d8b04d 42%, #9c7424 85%);
            box-shadow:
                0 0 0 6px rgba(215, 167, 44, 0.12),
                0 18px 40px rgba(0, 0, 0, 0.34),
                0 0 30px rgba(215, 167, 44, 0.24);
            display: block;
        }

        .brand-medallion-inner {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background:
                radial-gradient(circle at 50% 45%, #fff1c6 0%, #e1be65 48%, #ab7f2d 100%);
            border: 1px solid rgba(86, 58, 12, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .brand-medallion img {
            width: 84%;
            filter: sepia(1) saturate(0.2) brightness(0.45);
        }

        .brand-copy {
            text-align: center;
            margin-bottom: 14px;
        }

        .brand-copy h1 {
            font-size: clamp(1.8rem, 3vw, 2.35rem);
            line-height: 1;
            margin-bottom: 0.35rem;
            color: #fff5e8;
        }

        .brand-copy p {
            margin: 0;
            color: var(--muted);
            font-size: 0.9rem;
        }

        .login-form {
            display: grid;
            gap: 12px;
        }

        .field-wrap {
            padding: 12px 14px;
            border-radius: 20px;
            background: linear-gradient(180deg, rgba(255, 248, 238, 0.06), rgba(255, 248, 238, 0.03));
            border: 1px solid rgba(224, 191, 126, 0.18);
        }

        .form-label {
            color: #f0dec2;
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            margin-bottom: 0.55rem;
        }

        .form-control {
            background: rgba(248, 239, 225, 0.92);
            border: 1px solid rgba(193, 167, 119, 0.16);
            color: #1d1813;
            min-height: 50px;
            border-radius: 16px;
            padding: 0.8rem 0.95rem;
        }

        .form-control::placeholder {
            color: #8b7658;
        }

        .form-control:focus {
            background: rgba(255, 248, 238, 0.98);
            color: #1d1813;
            border-color: rgba(200, 155, 44, 0.38);
            box-shadow: 0 0 0 0.2rem rgba(200, 155, 44, 0.12);
        }

        .password-wrap {
            position: relative;
        }

        .password-wrap .form-control {
            padding-right: 3.25rem;
        }

        .password-toggle {
            position: absolute;
            top: 50%;
            right: 0.75rem;
            transform: translateY(-50%);
            width: 36px;
            height: 36px;
            border-radius: 999px;
            border: 1px solid rgba(200, 155, 44, 0.22);
            background: rgba(200, 155, 44, 0.1);
            color: #7a6444;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0;
        }

        .password-toggle:hover {
            background: rgba(197, 150, 47, 0.14);
            color: #3e3324;
        }

        .form-check-label {
            color: var(--muted);
        }

        .form-check-input {
            background-color: rgba(255, 247, 234, 0.92);
            border-color: rgba(197, 150, 47, 0.22);
        }

        .form-check-input:checked {
            background-color: var(--gold);
            border-color: var(--gold);
        }

        .btn-tailor {
            min-height: 52px;
            border-radius: 16px;
            background: linear-gradient(135deg, #d3a73a, #b68423);
            color: #ffffff;
            border: 1px solid rgba(255, 226, 163, 0.24);
            font-weight: 800;
            letter-spacing: 0.03em;
            box-shadow: 0 14px 28px rgba(0, 0, 0, 0.22);
        }

        .btn-tailor:hover {
            color: #ffffff;
            background: linear-gradient(135deg, #dbaf44, #bf8d2a);
            border-color: rgba(255, 226, 163, 0.28);
            opacity: 0.97;
            transform: translateY(-1px);
        }

        .invalid-feedback {
            display: block;
            color: #c43b4b;
            margin-top: 0.5rem;
        }

        @media (max-width: 576px) {
            .login-shell {
                padding: 14px;
            }

            .login-shell::before {
                inset: 28px 18px 110px;
                background: radial-gradient(circle at center, rgba(255, 255, 255, 0.06), transparent 58%);
            }

            body::after {
                display: none;
            }

            .login-stage {
                padding: 0;
            }

            .login-stage::before {
                inset: -20px;
                background: radial-gradient(circle at center, rgba(255, 255, 255, 0.08), transparent 56%);
            }

            .login-card {
                padding: 20px 20px 18px;
                border-radius: 26px;
            }

            .brand-medallion {
                width: 100px;
                height: 100px;
                margin-top: -50px;
            }
        }
    </style>
</head>
<body>
    <div class="login-shell">
        <div class="login-stage">
            <div class="login-card">
                <a href="{{ route('login') }}" class="brand-medallion" aria-label="Go to login">
                    <div class="brand-medallion-inner">
                        <img src="{{ asset('images/tailor-logo.png') }}" alt="Al Handaam Gents Tailoring">
                    </div>
                </a>

                <div class="brand-copy">
                    <h1>Login</h1>
                    <p>Secure staff access for Al Handaam Gents Tailoring</p>
                </div>

                <form action="{{ route('login.store') }}" method="POST" class="login-form">
                    @csrf

                    <div class="field-wrap">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" placeholder="Enter your email" required autofocus>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="field-wrap">
                        <label for="password" class="form-label">Password</label>
                        <div class="password-wrap">
                            <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Enter your password" required>
                            <button type="button" class="password-toggle" id="toggle-password" aria-label="Show password">
                                <svg id="eye-open" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                                <svg id="eye-closed" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true" style="display:none;">
                                    <path d="m3 3 18 18"/>
                                    <path d="M10.6 10.7a2 2 0 0 0 2.7 2.7"/>
                                    <path d="M9.4 5.5A10.7 10.7 0 0 1 12 5c6.5 0 10 7 10 7a17.7 17.7 0 0 1-4 4.9"/>
                                    <path d="M6.6 6.7C4.1 8.2 2 12 2 12a17.3 17.3 0 0 0 10 7 9.8 9.8 0 0 0 4.3-1"/>
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-check ms-1">
                        <input class="form-check-input" type="checkbox" value="1" id="remember" name="remember">
                        <label class="form-check-label" for="remember">Remember me</label>
                    </div>

                    <button type="submit" class="btn btn-tailor btn-lg">Login</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        (() => {
            const passwordInput = document.getElementById('password');
            const togglePassword = document.getElementById('toggle-password');
            const eyeOpen = document.getElementById('eye-open');
            const eyeClosed = document.getElementById('eye-closed');

            if (!passwordInput || !togglePassword || !eyeOpen || !eyeClosed) {
                return;
            }

            togglePassword.addEventListener('click', () => {
                const isPassword = passwordInput.type === 'password';

                passwordInput.type = isPassword ? 'text' : 'password';
                eyeOpen.style.display = isPassword ? 'none' : 'block';
                eyeClosed.style.display = isPassword ? 'block' : 'none';
                togglePassword.setAttribute('aria-label', isPassword ? 'Hide password' : 'Show password');
            });
        })();
    </script>
</body>
</html>
