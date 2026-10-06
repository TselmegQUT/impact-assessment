<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Login | DOST Impact Assessment System
    </title>

    <style>
        :root {
            --dost-blue: #00aeef;
            --dost-blue-dark: #008ac0;
            --navy: #111827;
            --text: #1f2937;
            --muted: #64748b;
            --border: #dbe3ea;
            --background: #eef4f7;
            --white: #ffffff;
            --danger: #b42318;
            --danger-background: #fef3f2;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: Arial, sans-serif;
            color: var(--text);
            background:
                linear-gradient(
                    135deg,
                    #eaf8fd 0%,
                    var(--background) 55%,
                    #f8fafc 100%
                );
        }

        .login-page {
            min-height: 100vh;
            display: grid;
            grid-template-columns: 1.1fr 0.9fr;
        }

        .information-panel {
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 100vh;
            padding: 56px 64px;
            overflow: hidden;
            color: var(--white);
            background:
                linear-gradient(
                    145deg,
                    rgba(0, 174, 239, 0.96),
                    rgba(0, 107, 153, 0.97)
                );
        }

        .information-panel::before,
        .information-panel::after {
            content: "";
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.09);
        }

        .information-panel::before {
            width: 430px;
            height: 430px;
            top: -190px;
            right: -160px;
        }

        .information-panel::after {
            width: 320px;
            height: 320px;
            bottom: -160px;
            left: -120px;
        }

        .brand,
        .hero-content,
        .panel-footer {
            position: relative;
            z-index: 1;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .brand-mark {
            display: grid;
            place-items: center;
            width: 72px;
            height: 72px;
            border: 3px solid var(--white);
            border-radius: 18px;
            font-family: "Arial Black", Arial, sans-serif;
            font-size: 19px;
            letter-spacing: -1px;
        }

        .brand-name {
            margin: 0 0 4px;
            font-family: "Arial Black", Arial, sans-serif;
            font-size: 24px;
        }

        .brand-description {
            margin: 0;
            font-size: 14px;
            line-height: 1.5;
            opacity: 0.9;
        }

        .hero-content {
            max-width: 650px;
            margin: 70px 0;
        }

        .system-label {
            display: inline-block;
            margin-bottom: 20px;
            padding: 8px 14px;
            border: 1px solid rgba(255, 255, 255, 0.35);
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.12);
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .hero-content h1 {
            max-width: 600px;
            margin: 0 0 22px;
            font-family: "Arial Black", Arial, sans-serif;
            font-size: clamp(38px, 5vw, 64px);
            line-height: 1.08;
        }

        .hero-content p {
            max-width: 570px;
            margin: 0;
            font-size: 18px;
            line-height: 1.75;
            opacity: 0.95;
        }

        .feature-list {
            display: grid;
            gap: 13px;
            margin-top: 32px;
        }

        .feature {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 15px;
            font-weight: 700;
        }

        .feature-icon {
            display: grid;
            place-items: center;
            flex: 0 0 auto;
            width: 27px;
            height: 27px;
            border-radius: 50%;
            color: var(--dost-blue-dark);
            background: var(--white);
        }

        .panel-footer {
            font-size: 13px;
            opacity: 0.85;
        }

        .form-panel {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 48px;
        }

        .login-container {
            width: 100%;
            max-width: 440px;
        }

        .mobile-brand {
            display: none;
            margin-bottom: 34px;
            text-align: center;
        }

        .mobile-brand-mark {
            display: inline-grid;
            place-items: center;
            width: 66px;
            height: 66px;
            margin-bottom: 12px;
            border-radius: 16px;
            color: var(--white);
            background: var(--dost-blue);
            font-family: "Arial Black", Arial, sans-serif;
        }

        .login-header {
            margin-bottom: 32px;
        }

        .login-header h2 {
            margin: 0 0 10px;
            color: var(--navy);
            font-family: "Arial Black", Arial, sans-serif;
            font-size: 32px;
        }

        .login-header p {
            margin: 0;
            color: var(--muted);
            font-size: 15px;
            line-height: 1.6;
        }

        .alert {
            margin-bottom: 22px;
            padding: 14px 16px;
            border: 1px solid #fecdca;
            border-radius: 10px;
            color: var(--danger);
            background: var(--danger-background);
            font-size: 14px;
            line-height: 1.5;
        }

        .success-alert {
            border-color: #a6f4c5;
            color: #067647;
            background: #ecfdf3;
        }

        .form-group {
            margin-bottom: 21px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #344054;
            font-size: 14px;
            font-weight: 700;
        }

        .input-control {
            width: 100%;
            min-height: 50px;
            padding: 12px 14px;
            border: 1px solid var(--border);
            border-radius: 10px;
            outline: none;
            color: var(--text);
            background: var(--white);
            font: inherit;
            transition:
                border-color 0.2s,
                box-shadow 0.2s;
        }

        .input-control:focus {
            border-color: var(--dost-blue);
            box-shadow: 0 0 0 4px rgba(0, 174, 239, 0.13);
        }

        .password-wrapper {
            position: relative;
        }

        .password-wrapper .input-control {
            padding-right: 78px;
        }

        .password-toggle {
            position: absolute;
            top: 50%;
            right: 12px;
            padding: 6px;
            border: 0;
            color: var(--dost-blue-dark);
            background: transparent;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
        }

        .form-options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin: 4px 0 26px;
        }

        .remember-option {
            display: flex;
            align-items: center;
            gap: 9px;
            color: #475467;
            font-size: 14px;
            cursor: pointer;
        }

        .remember-option input {
            width: 17px;
            height: 17px;
            accent-color: var(--dost-blue);
        }

        .forgot-link {
            color: var(--dost-blue-dark);
            font-size: 14px;
            font-weight: 700;
            text-decoration: none;
        }

        .forgot-link:hover {
            text-decoration: underline;
        }

        .login-button {
            width: 100%;
            min-height: 51px;
            padding: 13px 20px;
            border: 0;
            border-radius: 10px;
            color: var(--white);
            background: var(--dost-blue);
            font: inherit;
            font-weight: 700;
            cursor: pointer;
            transition:
                background 0.2s,
                transform 0.2s;
        }

        .login-button:hover {
            background: var(--dost-blue-dark);
            transform: translateY(-1px);
        }

        .security-message {
            margin: 24px 0 0;
            color: var(--muted);
            font-size: 12px;
            line-height: 1.6;
            text-align: center;
        }

        @media (max-width: 900px) {
            .login-page {
                display: block;
            }

            .information-panel {
                display: none;
            }

            .form-panel {
                padding: 32px 22px;
            }

            .mobile-brand {
                display: block;
            }

            .login-header {
                text-align: center;
            }
        }

        @media (max-width: 480px) {
            .form-panel {
                align-items: flex-start;
                padding-top: 42px;
            }

            .form-options {
                align-items: flex-start;
                flex-direction: column;
            }

            .login-header h2 {
                font-size: 28px;
            }
        }
    </style>
</head>

<body>
    <main class="login-page">
        <section class="information-panel">
            <div class="brand">
                <div class="brand-mark">DOST</div>

                <div>
                    <p class="brand-name">DOST</p>

                    <p class="brand-description">
                        Department of Science and Technology
                    </p>
                </div>
            </div>

            <div class="hero-content">
                <span class="system-label">
                    Project Monitoring Platform
                </span>

                <h1>
                    Impact Assessment System
                </h1>

                <p>
                    A centralized platform for recording projects,
                    assessing outputs, evaluating risks and generating
                    evidence-based impact assessment reports.
                </p>

                <div class="feature-list">
                    <div class="feature">
                        <span class="feature-icon">✓</span>
                        Role-based and secure access
                    </div>

                    <div class="feature">
                        <span class="feature-icon">✓</span>
                        Standardized project assessment
                    </div>

                    <div class="feature">
                        <span class="feature-icon">✓</span>
                        Automated calculations and reports
                    </div>
                </div>
            </div>

            <div class="panel-footer">
                Authorized personnel only
            </div>
        </section>

        <section class="form-panel">
            <div class="login-container">
                <div class="mobile-brand">
                    <div class="mobile-brand-mark">
                        DOST
                    </div>

                    <strong>
                        Impact Assessment System
                    </strong>
                </div>

                <header class="login-header">
                    <h2>Welcome back</h2>

                    <p>
                        Enter your account credentials to access
                        the system dashboard.
                    </p>
                </header>

                @if (session('status'))
                    <div class="alert success-alert">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert" role="alert">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form
                    method="POST"
                    action="{{ route('login') }}"
                >
                    @csrf

                    <div class="form-group">
                        <label for="email">
                            Email address
                        </label>

                        <input
                            class="input-control"
                            id="email"
                            name="email"
                            type="email"
                            value="{{ old('email') }}"
                            placeholder="name@example.com"
                            autocomplete="email"
                            required
                            autofocus
                        >
                    </div>

                    <div class="form-group">
                        <label for="password">
                            Password
                        </label>

                        <div class="password-wrapper">
                            <input
                                class="input-control"
                                id="password"
                                name="password"
                                type="password"
                                placeholder="Enter your password"
                                autocomplete="current-password"
                                required
                            >

                            <button
                                class="password-toggle"
                                id="password-toggle"
                                type="button"
                                aria-controls="password"
                            >
                                Show
                            </button>
                        </div>
                    </div>

                    <div class="form-options">
                        <label class="remember-option">
                            <input
                                name="remember"
                                type="checkbox"
                                value="1"
                                @checked(old('remember'))
                            >

                            Remember me
                        </label>

                        @if (Route::has('password.request'))
                            <a
                                class="forgot-link"
                                href="{{ route('password.request') }}"
                            >
                                Forgot password?
                            </a>
                        @endif
                    </div>

                    <button
                        class="login-button"
                        type="submit"
                    >
                        Sign in to dashboard
                    </button>
                </form>

                <p class="security-message">
                    This system contains controlled project
                    information. Do not share your login credentials.
                </p>
            </div>
        </section>
    </main>

    <script>
        const toggleButton =
            document.getElementById('password-toggle');

        const passwordInput =
            document.getElementById('password');

        toggleButton.addEventListener('click', function () {
            const passwordIsHidden =
                passwordInput.type === 'password';

            passwordInput.type =
                passwordIsHidden ? 'text' : 'password';

            toggleButton.textContent =
                passwordIsHidden ? 'Hide' : 'Show';
        });
    </script>
</body>
</html>
