<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

 <title>{{ $title ?? 'Admin Dashboard' }} | Impact Assessment System</title>
    <style>
        * {
            box-sizing: border-box;
        }

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

        header form {
            margin: 0;
        }

        .logout {
            padding: 9px 16px;
            border: 1px solid white;
            border-radius: 6px;
            color: white;
            background: transparent;
            cursor: pointer;
        }

        main {
            max-width: 1000px;
            margin: 40px auto;
            padding: 35px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        }

        .role {
            color: #15803d;
            font-weight: bold;
        }

        .task-grid {
            display: grid;
            grid-template-columns: repeat(
                auto-fit,
                minmax(210px, 1fr)
            );
            gap: 18px;
            margin-top: 28px;
        }

        .task {
            padding: 22px;
            border-left: 5px solid #15803d;
            border-radius: 8px;
            background: #f8fafc;
        }

        .task h3 {
            margin: 0 0 8px;
        }
    </style>
</head>

<body>
    <header>
        <strong>Impact Assessment System</strong>

        <form method="POST" action="/logout">
            @csrf
            <button class="logout" type="submit">
                Logout
            </button>
        </form>
    </header>

    <main>
        <h1>{{ $title ?? 'Admin Dashboard' }}</h1>

        <p>
            Welcome, <strong>{{ auth()->user()->name }}</strong>
        </p>

        <p>
            Your role:
            <span class="role">
                {{ auth()->user()->role->display_name }}
            </span>
        </p>

        <div class="task-grid">
            @foreach(($tasks ?? []) as $task)
                <section class="task">
                    <h3>{{ $task['title'] }}</h3>
                    <p>{{ $task['description'] }}</p>
                </section>
            @endforeach
        </div>
    </main>
</body>
</html>
