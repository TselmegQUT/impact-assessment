<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Impact Assessment System</title>

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #eef4f1;
            color: #1f2937;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 18px 40px;
            color: white;
            background: #14532d;
        }

        main {
            max-width: 1000px;
            margin: 40px auto;
            padding: 30px;
            background: white;
            border-radius: 12px;
        }

        .role {
            color: #15803d;
            font-weight: bold;
        }

        a, button {
            display: inline-block;
            padding: 10px 18px;
            border: none;
            border-radius: 6px;
            color: white;
            background: #15803d;
            text-decoration: none;
            cursor: pointer;
        }

        form {
            display: inline;
        }
    </style>
</head>

<body>
    <header>
        <strong>Impact Assessment System</strong>

        <form method="POST" action="/logout">
            @csrf
            <button type="submit">Logout</button>
        </form>
    </header>

    <main>
        <h1>Welcome, {{ auth()->user()->name }}</h1>

        <p>
            Your role:
            <span class="role">
                {{ auth()->user()->role?->display_name ?? 'No role assigned' }}
            </span>
        </p>

        <p>You successfully logged into the system.</p>

        @if(auth()->user()->hasRole('admin'))
            <a href="{{ route('admin.dashboard') }}">
                Open Administrator Area
            </a>
        @endif
    </main>
</body>
</html>
