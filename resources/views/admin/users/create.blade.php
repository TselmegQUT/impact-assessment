@extends('layouts.app')

@section('title', 'Create User')

@section('page-title', 'Create User')

@section('content')

    <section class="page-header">
        <div class="page-header-copy">
            <h2>Create a System User</h2>

            <p>
                Create a new account and assign the user’s access
                role in the Impact Assessment System.
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
        action="{{ route('admin.users.store') }}"
    >
        @csrf

        <section
            class="panel"
            style="max-width: 900px;"
        >
            <div class="panel-header">
                <div>
                    <h2>Account Information</h2>
                </div>

                <span class="status-badge status-warning">
                    All Fields Required
                </span>
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
                            value="{{ old('name') }}"
                            placeholder="Enter the user’s full name"
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
                            value="{{ old('email') }}"
                            placeholder="name@example.com"
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
                            <option value="">
                                Select a system role
                            </option>

                            @foreach($roles as $role)
                                <option
                                    value="{{ $role->id }}"
                                    @selected(
                                        old('role_id')
                                        == $role->id
                                    )
                                >
                                    {{ $role->display_name }}
                                </option>
                            @endforeach
                        </select>

                        <span class="form-help">
                            The selected role controls which
                            pages and actions the user can access.
                        </span>

                        @error('role_id')
                            <span class="form-error">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label
                            for="password"
                            class="form-label"
                        >
                            Temporary Password *
                        </label>

                        <input
                            id="password"
                            name="password"
                            type="password"
                            class="form-control"
                            autocomplete="new-password"
                            required
                        >

                        <span class="form-help">
                            Use a strong password with letters,
                            numbers and symbols.
                        </span>

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
                            Confirm Temporary Password *
                        </label>

                        <input
                            id="password_confirmation"
                            name="password_confirmation"
                            type="password"
                            class="form-control"
                            autocomplete="new-password"
                            required
                        >
                    </div>
                </div>
            </div>

            <div
                class="panel-body"
                style="border-top: 1px solid var(--border);"
            >
                <div class="page-actions">
                    <button
                        type="submit"
                        class="button button-primary"
                    >
                        Create User
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
