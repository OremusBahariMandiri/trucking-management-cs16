<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — CS16 Trucking</title>
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background: #f0f4ff;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        /* ── Wrapper ── */
        .login-wrap {
            display: flex;
            width: 100%;
            max-width: 960px;
            min-height: 580px;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 24px 64px rgba(58, 90, 230, .18);
        }

        .logo-box img {
            width: 26px;
            height: 26px;
            object-fit: contain;
            filter: brightness(0) invert(1);
            /* jadikan putih */
        }

        /* ── Panel kiri ── */
        /* .panel-left {
            flex: 0 0 44%;
            background: linear-gradient(145deg, #4a6cf7 0%, #3a55e0 45%, #2e44cc 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-between;
            padding: 3rem 2.5rem 2rem;
            position: relative;
            overflow: hidden;
        } */

        .panel-left {
            flex: 0 0 44%;
            background:
                url("{{ asset('images/login-portrait.png') }}") center/cover no-repeat;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-between;
            padding: 3rem 2.5rem 2rem;
            position: relative;
            overflow: hidden;
        }

        /* Lingkaran dekoratif latar */
        .panel-left::before {
            content: '';
            position: absolute;
            top: -80px;
            right: -80px;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .07);
            pointer-events: none;
        }

        .panel-left::after {
            content: '';
            position: absolute;
            bottom: -60px;
            left: -60px;
            width: 220px;
            height: 220px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .05);
            pointer-events: none;
        }

        .brand-logo {
            align-self: flex-start;
            display: flex;
            align-items: center;
            gap: .6rem;
            color: #fff;
            text-decoration: none;
            z-index: 1;
        }

        .brand-logo .logo-box {
            width: 38px;
            height: 38px;
            background: rgba(255, 255, 255, .2);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .brand-logo .logo-box svg {
            width: 22px;
            height: 22px;
        }

        .brand-logo span {
            font-size: 1.15rem;
            font-weight: 700;
            letter-spacing: .5px;
        }

        /* Ilustrasi placeholder */
        .illus-wrap {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1;
            width: 100%;
        }

        .illus-svg {
            width: 85%;
            max-width: 280px;
            filter: drop-shadow(0 12px 32px rgba(0, 0, 0, .18));
        }

        /* Ilustrasi SVG inline */

        .panel-left-foot {
            z-index: 1;
            text-align: center;
            color: rgba(255, 255, 255, .7);
        }

        .panel-left-foot .socials {
            display: flex;
            gap: 1.2rem;
            justify-content: center;
            margin-bottom: .75rem;
        }

        .panel-left-foot .socials a {
            color: rgba(255, 255, 255, .7);
            text-decoration: none;
            font-size: .9rem;
            width: 32px;
            height: 32px;
            border: 1.5px solid rgba(255, 255, 255, .3);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background .2s, color .2s;
        }

        .panel-left-foot .socials a:hover {
            background: rgba(255, 255, 255, .15);
            color: #fff;
        }

        .panel-left-foot small {
            font-size: .72rem;
        }

        /* ── Panel kanan ── */
        .panel-right {
            flex: 1;
            background: #fff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 3rem 3.5rem;
        }

        .right-logo {
            font-size: 1.6rem;
            font-weight: 800;
            letter-spacing: 2px;
            color: #4a6cf7;
            margin-bottom: 2rem;
        }

        .right-logo span {
            color: #bbc6ff;
        }

        .signin-heading {
            font-size: 1.65rem;
            font-weight: 700;
            color: #1a1f36;
            margin-bottom: 2rem;
        }

        /* Fields */
        .field-group {
            margin-bottom: 1.5rem;
        }

        .field-label {
            display: block;
            font-size: .68rem;
            font-weight: 700;
            letter-spacing: 1.2px;
            color: #9ba3c4;
            text-transform: uppercase;
            margin-bottom: .45rem;
        }

        .field-input {
            width: 100%;
            border: none;
            border-bottom: 1.5px solid #e0e4f0;
            padding: .5rem 0;
            font-size: .97rem;
            color: #1a1f36;
            background: transparent;
            outline: none;
            transition: border-color .2s;
        }

        .field-input:focus {
            border-color: #4a6cf7;
        }

        .field-input.is-invalid {
            border-color: #e74c3c;
        }

        .invalid-msg {
            font-size: .78rem;
            color: #e74c3c;
            margin-top: .3rem;
            display: block;
        }

        /* Remember + forgot */
        .row-meta {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 2rem;
        }

        .check-wrap {
            display: flex;
            align-items: center;
            gap: .5rem;
            cursor: pointer;
        }

        .check-wrap input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: #4a6cf7;
            cursor: pointer;
        }

        .check-wrap label {
            font-size: .88rem;
            color: #5a607f;
            cursor: pointer;
        }

        .forgot-link {
            font-size: .88rem;
            color: #4a6cf7;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: .3rem;
        }

        .forgot-link:hover {
            text-decoration: underline;
        }

        /* Row bawah: no account + tombol */
        .row-submit {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 2rem;
        }

        .no-account {
            font-size: .88rem;
            color: #5a607f;
        }

        .no-account a {
            color: #4a6cf7;
            font-weight: 600;
            text-decoration: none;
        }

        .no-account a:hover {
            text-decoration: underline;
        }

        .btn-signin {
            background: #4a6cf7;
            color: #fff;
            border: none;
            border-radius: 50px;
            padding: .65rem 2rem;
            font-size: .95rem;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: .5rem;
            transition: background .2s, transform .1s, box-shadow .2s;
            box-shadow: 0 4px 16px rgba(74, 108, 247, .35);
        }

        .btn-signin:hover {
            background: #3a55e0;
            box-shadow: 0 6px 20px rgba(74, 108, 247, .45);
            transform: translateY(-1px);
        }

        .btn-signin:active {
            transform: translateY(0);
        }

        /* Divider */
        .divider {
            display: flex;
            align-items: center;
            gap: .75rem;
            color: #c4cae0;
            font-size: .78rem;
            margin-bottom: 1.25rem;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e8ecf8;
        }

        /* ── Responsive ── */
        @media (max-width: 700px) {
            .login-wrap {
                flex-direction: column;
                max-width: 440px;
                border-radius: 20px;
                min-height: auto;
            }

            .panel-left {
                flex: none;
                padding: 2rem;
                min-height: 200px;
                background:
                    url("{{ asset('images/login.png') }}") center/cover no-repeat;
                /* ← ganti aset */
            }

            .illus-wrap {
                display: none;
            }

            .panel-right {
                padding: 2rem;
            }

            .right-logo {
                margin-bottom: 1.25rem;
            }
        }

        /* password toggle */
        .pass-wrap {
            position: relative;
        }

        .pass-toggle {
            position: absolute;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #9ba3c4;
            cursor: pointer;
            padding: .25rem;
            line-height: 1;
        }

        .pass-toggle:hover {
            color: #4a6cf7;
        }
    </style>
</head>

<body>

    <div class="login-wrap">

        {{-- ── Panel Kiri ── --}}
        <div class="panel-left">

            <a href="#" class="brand-logo">
                <div class="logo-box">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo CS16"
                        style="width:26px;height:26px;object-fit:contain;">
                </div>
                <span>CITRA SAMUDERA 16</span>
            </a>

        </div>{{-- /panel-left --}}

        {{-- ── Panel Kanan ── --}}
        <div class="panel-right">

            <div class="right-logo">CITRACK <span>16</span></div>

            <h1 class="signin-heading">Masuk</h1>

            <form method="POST" action="{{ route('login') }}" novalidate>
                @csrf

                {{-- NRK --}}
                <div class="field-group">
                    <label class="field-label" for="employee_id_number">NRK / ID Karyawan</label>
                    <input id="employee_id_number" type="text"
                        class="field-input @error('employee_id_number') is-invalid @enderror" name="employee_id_number"
                        value="{{ old('employee_id_number') }}" placeholder="Masukkan NRK Anda" required
                        autocomplete="username" autofocus>
                    @error('employee_id_number')
                        <span class="invalid-msg">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="field-group">
                    <label class="field-label" for="password">Kata Sandi</label>
                    <div class="pass-wrap">
                        <input id="password" type="password"
                            class="field-input @error('password') is-invalid @enderror" name="password"
                            placeholder="••••••••" required autocomplete="current-password">
                        <button type="button" class="pass-toggle" id="togglePass" title="Tampilkan/sembunyikan">
                            <svg id="eyeIcon" width="18" height="18" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                <circle cx="12" cy="12" r="3" />
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <span class="invalid-msg">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Remember + Forgot --}}
                <div class="row-meta">
                    <label class="check-wrap">
                        <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label for="remember">Ingat saya</label>
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="forgot-link">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                                <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                            </svg>
                            Lupa kata sandi?
                        </a>
                    @endif
                </div>

                {{-- Submit --}}
                <div class="row-submit">
                    <span class="no-account">
                        Butuh akses? <a href="#">Hubungi Admin</a>
                    </span>
                    <button type="submit" class="btn-signin">
                        Masuk
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2.5">
                            <line x1="5" y1="12" x2="19" y2="12" />
                            <polyline points="12 5 19 12 12 19" />
                        </svg>
                    </button>
                </div>

            </form>

        </div>{{-- /panel-right --}}

    </div>

    <script>
        // Toggle password visibility
        const toggleBtn = document.getElementById('togglePass');
        const passInput = document.getElementById('password');
        if (toggleBtn && passInput) {
            toggleBtn.addEventListener('click', () => {
                const isPass = passInput.type === 'password';
                passInput.type = isPass ? 'text' : 'password';
                toggleBtn.style.color = isPass ? '#4a6cf7' : '';
            });
        }
    </script>

</body>

</html>
