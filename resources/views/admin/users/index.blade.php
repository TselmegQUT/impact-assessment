<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>User Management</title>

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
            max-width: 1200px;
            margin: 40px auto;
            padding: 30px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        }

        .top-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .create-button {
            padding: 11px 18px;
            border-radius: 6px;
            color: white;
            background: #15803d;
            text-decoration: none;
        }

        .table-container {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 14px;
            border-bottom: 1px solid #e2e8f0;
            text-align: left;
        }

        th {
            color: #334155;
            background: #f8fafc;
        }

        .status {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: bold;
        }

        .active {
            color: #166534;
            background: #dcfce7;
        }

        .inactive {
            color: #991b1b;
            background: #fee2e2;
        }

        .success-message {
            margin-bottom: 20px;
            padding: 12px;
            color: #166534;
            background: #dcfce7;
            border-radius: 6px;
        }

        .error-message {
            margin-bottom: 20px;
            padding: 12px;
            color: #991b1b;
            background: #fee2e2;
            border-radius: 6px;
        }

        .actions {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .actions form {
            margin: 0;
        }

        .edit-button,
        .status-button {
            display: inline-block;
            padding: 7px 11px;
            border: none;
            border-radius: 5px;
            font-size: 13px;
            text-decoration: none;
            cursor: pointer;
        }

        .edit-button {
            color: white;
            background: #2563eb;
        }

        .deactivate-button {
            color: white;
            background: #dc2626;
        }

        .activate-button {
            color: white;
            background: #15803d;
        }

        .current-user {
            color: #64748b;
            font-size: 13px;
        }

        .pagination {
            display: flex;
            justify-content: space-between;
            margin-top: 24px;
        }

        .pagination a {
            color: #15803d;
            font-weight: bold;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <header>
        <strong>Impact Assessment System</strong>

        <a href="{{ route('admin.dashboard') }}">
            Administrator Dashboard
        </a>
    </header>

    <main>
        <div class="top-row">
            <div>
                <h1>User Management</h1>
                <p>Create users and assign their system roles.</p>
            </div>

            <a
                class="create-button"
                href="{{ route('admin.users.create') }}"
            >
                + Create User
            </a>
        </div>

        @if(session('success'))
            <div class="success-message">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="error-message">
                {{ session('error') }}
            </div>
        @endif

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>

                            <td>{{ $user->email }}</td>

                            <td>
                                {{ $user->role?->display_name ?? 'No role' }}
                            </td>

                            <td>
                                @if($user->is_active)
                                    <span class="status active">
                                        Active
                                    </span>
                                @else
                                    <span class="status inactive">
                                        Inactive
                                    </span>
                                @endif
                            </td>

                            <td>
                                {{ $user->created_at->format('d M Y') }}
                            </td>

                            <td>
                                <div class="actions">
                                    <a
                                        class="edit-button"
                                        href="{{ route(
                                            'admin.users.edit',
                                            $user
                                        ) }}"
                                    >
                                        Edit
                                    </a>

                                    @if($user->id !== auth()->id())
                                        <form
                                            method="POST"
                                            action="{{ route(
                                                'admin.users.status',
                                                $user
                                            ) }}"
                                        >
                                            @csrf
                                            @method('PATCH')

                                            <button
                                                type="submit"
                                                class="status-button
                                                    {{ $user->is_active
                                                        ? 'deactivate-button'
                                                        : 'activate-button' }}"
                                                onclick="return confirm(
                                                    'Change this user status?'
                                                )"
                                            >
                                                {{ $user->is_active
                                                    ? 'Deactivate'
                                                    : 'Activate' }}
                                            </button>
                                        </form>
                                    @else
                                        <span class="current-user">
                                            Current account
                                        </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                No users were found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="pagination">
                <div>
                    @if(!$users->onFirstPage())
                        <a href="{{ $users->previousPageUrl() }}">
                            ← Previous
                        </a>
                    @endif
                </div>

                <div>
                    @if($users->hasMorePages())
                        <a href="{{ $users->nextPageUrl() }}">
                            Next →
                        </a>
                    @endif
                </div>
            </div>
        @endif
    </main>
</body>
</html>
