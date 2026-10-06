@extends('layouts.app')

@section('title', 'User Management')

@section('page-title', 'User Management')

@section('content')

    <section class="page-header">
        <div class="page-header-copy">
            <h2>System Users</h2>

            <p>
                Create user accounts, assign system roles and
                control access to the Impact Assessment System.
            </p>
        </div>

        <div class="page-actions">
            <a
                href="{{ route('admin.users.create') }}"
                class="button button-primary"
            >
                + Create User
            </a>
        </div>
    </section>

    {{-- User summary --}}
    <section class="metric-grid">
        <article class="metric-card">
            <p class="metric-label">
                Total Users
            </p>

            <p class="metric-value">
                {{ $users->total() }}
            </p>

            <p class="metric-helper">
                Registered system accounts
            </p>
        </article>

        <article class="metric-card">
            <p class="metric-label">
                Users on This Page
            </p>

            <p class="metric-value">
                {{ $users->count() }}
            </p>

            <p class="metric-helper">
                Currently displayed accounts
            </p>
        </article>

        <article class="metric-card">
            <p class="metric-label">
                Active on This Page
            </p>

            <p class="metric-value">
                {{
                    $users->getCollection()
                        ->where('is_active', true)
                        ->count()
                }}
            </p>

            <p class="metric-helper">
                Accounts with system access
            </p>
        </article>

        <article class="metric-card">
            <p class="metric-label">
                Inactive on This Page
            </p>

            <p class="metric-value">
                {{
                    $users->getCollection()
                        ->where('is_active', false)
                        ->count()
                }}
            </p>

            <p class="metric-helper">
                Access currently disabled
            </p>
        </article>
    </section>

    {{-- User table --}}
    <section class="panel">
        <div class="panel-header">
            <div>
                <h2>All User Accounts</h2>
            </div>

            <span class="status-badge status-neutral">
                {{ $users->total() }}
                {{ Str::plural('user', $users->total()) }}
            </span>
        </div>

        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Email Address</th>
                        <th>System Role</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>
                                <div
                                    style="
                                        display: flex;
                                        align-items: center;
                                        gap: 10px;
                                    "
                                >
                                    <div class="topbar-avatar">
                                        {{
                                            strtoupper(
                                                substr(
                                                    $user->name,
                                                    0,
                                                    1
                                                )
                                            )
                                        }}
                                    </div>

                                    <div>
                                        <strong>
                                            {{ $user->name }}
                                        </strong>

                                        @if(
                                            $user->id
                                            === auth()->id()
                                        )
                                            <div
                                                style="
                                                    margin-top: 3px;
                                                    color:
                                                        var(
                                                            --text-secondary
                                                        );
                                                    font-size:
                                                        11px;
                                                "
                                            >
                                                Current account
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <td>
                                {{ $user->email }}
                            </td>

                            <td>
                                <span
                                    class="status-badge
                                        status-neutral"
                                >
                                    {{
                                        $user->role
                                            ?->display_name
                                        ?? 'No role'
                                    }}
                                </span>
                            </td>

                            <td>
                                @if($user->is_active)
                                    <span
                                        class="status-badge
                                            status-success"
                                    >
                                        Active
                                    </span>
                                @else
                                    <span
                                        class="status-badge
                                            status-danger"
                                    >
                                        Inactive
                                    </span>
                                @endif
                            </td>

                            <td>
                                {{
                                    $user->created_at
                                        ?->format('d M Y')
                                    ?? 'Unknown'
                                }}
                            </td>

                            <td>
                                <div class="page-actions">
                                    <a
                                        href="{{
                                            route(
                                                'admin.users.edit',
                                                $user
                                            )
                                        }}"
                                        class="button
                                            button-secondary"
                                    >
                                        Edit
                                    </a>

                                    @if(
                                        $user->id
                                        !== auth()->id()
                                    )
                                        <form
                                            method="POST"
                                            action="{{
                                                route(
                                                    'admin.users.status',
                                                    $user
                                                )
                                            }}"
                                            onsubmit="
                                                return confirm(
                                                    'Are you sure you want to change this user status?'
                                                );
                                            "
                                        >
                                            @csrf
                                            @method('PATCH')

                                            <button
                                                type="submit"
                                                class="button
                                                    {{
                                                        $user->is_active
                                                        ? 'button-danger'
                                                        : 'button-primary'
                                                    }}"
                                            >
                                                {{
                                                    $user->is_active
                                                    ? 'Deactivate'
                                                    : 'Activate'
                                                }}
                                            </button>
                                        </form>
                                    @else
                                        <span
                                            class="status-badge
                                                status-neutral"
                                        >
                                            You
                                        </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div
                                    style="
                                        padding: 35px;
                                        text-align: center;
                                    "
                                >
                                    <strong>
                                        No users found
                                    </strong>

                                    <p
                                        style="
                                            margin: 8px 0 18px;
                                            color:
                                                var(--text-secondary);
                                        "
                                    >
                                        Create a user account and
                                        assign a system role.
                                    </p>

                                    <a
                                        href="{{
                                            route(
                                                'admin.users.create'
                                            )
                                        }}"
                                        class="button
                                            button-primary"
                                    >
                                        + Create User
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div
                class="panel-body"
                style="
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    gap: 15px;
                "
            >
                <p
                    style="
                        margin: 0;
                        color: var(--text-secondary);
                        font-size: 13px;
                    "
                >
                    Showing
                    {{ $users->firstItem() }}
                    to
                    {{ $users->lastItem() }}
                    of
                    {{ $users->total() }}
                    users
                </p>

                <div class="page-actions">
                    @if(!$users->onFirstPage())
                        <a
                            href="{{
                                $users->previousPageUrl()
                            }}"
                            class="button button-secondary"
                        >
                            ← Previous
                        </a>
                    @endif

                    @if($users->hasMorePages())
                        <a
                            href="{{
                                $users->nextPageUrl()
                            }}"
                            class="button button-primary"
                        >
                            Next →
                        </a>
                    @endif
                </div>
            </div>
        @endif
    </section>

@endsection
