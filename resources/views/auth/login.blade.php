<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Impact Assessment System</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: Arial, sans-serif;
            background: #eef4f1;
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            padding: 38px;
            background: white;
            border-radius: 14px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
        }

        h1 {
            margin-bottom: 8px;
            color: #14532d;
            text-align: center;
        }

        .subtitle {
            margin-bottom: 28px;
            color: #64748b;
            text-align: center;
        }

        label {
            display: block;
            margin: 16px 0 7px;
            font-weight: bold;
            color: #334155;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #cbd5e1;
            border-radius: 7px;
            font-size: 16px;
        }

        .remember {
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 17px 0;
        }

        .remember label {
            margin: 0;
            font-weight: normal;
        }

        button {
            width: 100%;
            padding: 13px;
            border: 0;
            border-radius: 7px;
            color: white;
            background: #15803d;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
        }

        button:hover {
            background: #166534;
        }

        .error {
            margin-bottom: 18px;
            padding: 12px;
            color: #991b1b;
            background: #fee2e2;
            border-radius: 7px;
        }
    </style>
</head>

<body>
    <main class="login-card">
        <h1>Impact Assessment System</h1>
        <p class="subtitle">Sign in to continue</p>

        @if ($errors->any())
            <div class="error">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="/login">
            @csrf

            <label for="email">Email address</label>
            <input
                id="email"
                name="email"
                type="email"
                value="{{ old('email') }}"
                required
                autofocus
                autocomplete="email"
            >

            <label for="password">Password</label>
            <input
                id="password"
                name="password"
                type="password"
                required
                autocomplete="current-password"
            >

            <div class="remember">
                <input id="remember" name="remember" type="checkbox">
                <label for="remember">Remember me</label>
            </div>

            <button type="submit">Sign In</button>
        </form>
    </main>
</body>
</html>
