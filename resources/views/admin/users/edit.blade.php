@extends('layouts.app')

@section('title', 'Edit User')

@section('page-title', 'Edit User')

@section('content')

    <section class="page-header">
        <div class="page-header-copy">
            <h2>Edit User Account</h2>

            <p>
                Update account information, change the system role
                or assign a new password.
            </p>
        </div>

        <div class="page-actions">
            <a
                href="{{ route('admin.users.index') }}"
                class="button button-secondary"
            >
                ← Back to Users
            </a>
        </div>
    </section>

    <form
        method="POST"
        action="{{
            route(
                'admin.users.update',
                $user
            )
        }}"
    >
        @csrf
        @method('PUT')

        <section
            class="panel"
            style="max-width: 900px;"
        >
            <div class="panel-header">
                <div>
                    <h2>Account Information</h2>
                </div>

                @if($user->is_active)
                    <span
                        class="status-badge status-success"
                    >
                        Active
                    </span>
                @else
                    <span
                        class="status-badge status-danger"
                    >
                        Inactive
                    </span>
                @endif
            </div>

            <div class="panel-body">
                <div class="form-grid">

                    <div class="form-group">
                        <label
                            for="name"
                            class="form-label"
                        >
                            Full Name *
                        </label>

                        <input
                            id="name"
                            name="name"
                            type="text"
                            class="form-control"
                            value="{{
                                old(
                                    'name',
                                    $user->name
                                )
                            }}"
                            autocomplete="name"
                            required
                        >

                        @error('name')
                            <span class="form-error">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label
                            for="email"
                            class="form-label"
                        >
                            Email Address *
                        </label>

                        <input
                            id="email"
                            name="email"
                            type="email"
                            class="form-control"
                            value="{{
                                old(
                                    'email',
                                    $user->email
                                )
                            }}"
                            autocomplete="email"
                            required
                        >

                        @error('email')
                            <span class="form-error">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <div class="form-group full-width">
                        <label
                            for="role_id"
                            class="form-label"
                        >
                            System Role *
                        </label>

                        <select
                            id="role_id"
                            name="role_id"
                            class="form-control"
                            required
                        >
                            @foreach($roles as $role)
                                <option
                                    value="{{ $role->id }}"
                                    @selected(
                                        old(
                                            'role_id',
                                            $user->role_id
                                        ) == $role->id
                                    )
                                >
                                    {{ $role->display_name }}
                                </option>
                            @endforeach
                        </select>

                        <span class="form-help">
                            Changing the role will change the
                            pages and actions this user can access.
                        </span>

                        @error('role_id')
                            <span class="form-error">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                </div>
            </div>
        </section>

        <section
            class="panel"
            style="
                max-width: 900px;
                margin-top: 22px;
            "
        >
            <div class="panel-header">
                <div>
                    <h2>Change Password</h2>
                </div>

                <span class="status-badge status-neutral">
                    Optional
                </span>
            </div>

            <div class="panel-body">
                <p
                    style="
                        margin-top: 0;
                        color: var(--text-secondary);
                        line-height: 1.6;
                    "
                >
                    Leave both password fields empty if you do
                    not want to change this user’s password.
                </p>

                <div class="form-grid">
                    <div class="form-group">
                        <label
                            for="password"
                            class="form-label"
                        >
                            New Password
                        </label>

                        <input
                            id="password"
                            name="password"
                            type="password"
                            class="form-control"
                            autocomplete="new-password"
                        >

                        @error('password')
                            <span class="form-error">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label
                            for="password_confirmation"
                            class="form-label"
                        >
                            Confirm New Password
                        </label>

                        <input
                            id="password_confirmation"
                            name="password_confirmation"
                            type="password"
                            class="form-control"
                            autocomplete="new-password"
                        >
                    </div>
                </div>
            </div>
        </section>

        <section
            class="panel"
            style="
                max-width: 900px;
                margin-top: 22px;
            "
        >
            <div class="panel-body">
                <div class="page-actions">
                    <button
                        type="submit"
                        class="button button-primary"
                    >
                        Save Changes
                    </button>

                    <a
                        href="{{ route('admin.users.index') }}"
                        class="button button-secondary"
                    >
                        Cancel
                    </a>
                </div>
            </div>
        </section>
    </form>

@endsection
