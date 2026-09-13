<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create User</title>

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

        header a {
            color: white;
            text-decoration: none;
        }

        main {
            max-width: 650px;
            margin: 40px auto;
            padding: 35px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        }

        h1 {
            margin-top: 0;
        }

        label {
            display: block;
            margin: 18px 0 7px;
            font-weight: bold;
        }

        input, select {
            width: 100%;
            padding: 12px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            font-size: 16px;
        }

        .buttons {
            display: flex;
            gap: 12px;
            margin-top: 25px;
        }

        button, .cancel {
            padding: 12px 20px;
            border: none;
            border-radius: 6px;
            font-size: 15px;
            text-decoration: none;
            cursor: pointer;
        }

        button {
            color: white;
            background: #15803d;
        }

        .cancel {
            color: #334155;
            background: #e2e8f0;
        }

        .errors {
            margin-bottom: 20px;
            padding: 14px;
            color: #991b1b;
            background: #fee2e2;
            border-radius: 6px;
        }

        .errors ul {
            margin: 0;
            padding-left: 20px;
        }
    </style>
</head>

<body>
    <header>
        <strong>Impact Assessment System</strong>

        <a href="{{ route('admin.users.index') }}">
            User Management
        </a>
    </header>

    <main>
        <h1>Create User</h1>
        <p>Create an account and assign its system role.</p>

        @if($errors->any())
            <div class="errors">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.users.store') }}">
            @csrf

            <label for="name">Full name</label>
            <input
                id="name"
                name="name"
                type="text"
                value="{{ old('name') }}"
                required
            >

            <label for="email">Email address</label>
            <input
                id="email"
                name="email"
                type="email"
                value="{{ old('email') }}"
                required
            >

            <label for="role_id">System role</label>
            <select id="role_id" name="role_id" required>
                <option value="">Select a role</option>

                @foreach($roles as $role)
                    <option
                        value="{{ $role->id }}"
                        @selected(old('role_id') == $role->id)
                    >
                        {{ $role->display_name }}
                    </option>
                @endforeach
            </select>

            <label for="password">Temporary password</label>
            <input
                id="password"
                name="password"
                type="password"
                required
            >

            <label for="password_confirmation">
                Confirm temporary password
            </label>
            <input
                id="password_confirmation"
                name="password_confirmation"
                type="password"
                required
            >

            <div class="buttons">
                <button type="submit">Create User</button>

                <a class="cancel" href="{{ route('admin.users.index') }}">
                    Cancel
                </a>
            </div>
        </form>
    </main>
</body>
</html>
