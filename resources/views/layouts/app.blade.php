<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

    <title>
        @yield('title', 'Dashboard') |
        DOST Impact Assessment System
    </title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js',
    ])
</head>

<body>
    <div class="application-shell">

        {{-- Mobile background overlay --}}
        <button
            class="sidebar-overlay"
            id="sidebar-overlay"
            type="button"
            aria-label="Close navigation menu"
        ></button>

        {{-- Sidebar --}}
        <aside class="sidebar" id="application-sidebar">

            <div class="sidebar-brand">
                <div class="brand-symbol" aria-hidden="true">
                    DOST
                </div>

                <div class="brand-copy">
                    <strong>Impact Assessment</strong>
                    <span>Management System</span>
                </div>
            </div>

            <nav
                class="sidebar-navigation"
                aria-label="Main navigation"
            >
                <p class="navigation-label">
                    Main menu
                </p>

                <a
                    href="{{ route('dashboard') }}"
                    class="navigation-link
                        {{ request()->routeIs(
                            'dashboard',
                            '*.dashboard'
                        ) ? 'active' : '' }}"
                >
                    <span class="navigation-icon">
                        ▦
                    </span>

                    <span>Dashboard</span>
                </a>

                <a
                    href="{{ route('projects.index') }}"
                    class="navigation-link
                        {{ request()->routeIs(
                            'projects.index',
                            'projects.show',
                            'projects.create'
                        ) ? 'active' : '' }}"
                >
                    <span class="navigation-icon">
                        ▤
                    </span>

                    <span>Project Records</span>
                </a>

                @if(Route::has('documents.index'))
                    <a
                        href="{{ route('documents.index') }}"
                        class="navigation-link
                            {{ request()->routeIs(
                                'documents.*'
                            ) ? 'active' : '' }}"
                    >
                        <span class="navigation-icon">
                            ⇧
                        </span>

                        <span>Data Ingestion</span>
                    </a>
                @else
                    <div
                        class="navigation-link navigation-disabled"
                        title="This module will be added next."
                    >
                        <span class="navigation-icon">
                            ⇧
                        </span>

                        <span>Data Ingestion</span>

                        <small>Soon</small>
                    </div>
                @endif

                <a
                    href="{{ route('projects.index') }}"
                    class="navigation-link
                        {{ request()->routeIs(
                            'projects.outputs.*',
                            'projects.risks.*'
                        ) ? 'active' : '' }}"
                >
                    <span class="navigation-icon">
                        ⚙
                    </span>

                    <span>Assessment Logic</span>
                </a>

                <a
                    href="{{ route('projects.index') }}"
                    class="navigation-link
                        {{ request()->routeIs(
                            'projects.reports.*'
                        ) ? 'active' : '' }}"
                >
                    <span class="navigation-icon">
                        ◇
                    </span>

                    <span>Reports</span>
                </a>

                @if(
                    auth()->user()?->role?->name === 'admin'
                )
                    <p class="navigation-label">
                        Administration
                    </p>

                    <a
                        href="{{ route('admin.users.index') }}"
                        class="navigation-link
                            {{ request()->routeIs(
                                'admin.users.*'
                            ) ? 'active' : '' }}"
                    >
                        <span class="navigation-icon">
                            ♙
                        </span>

                        <span>User Management</span>
                    </a>
                @endif
            </nav>

            <div class="sidebar-profile">
                <div class="profile-avatar">
                    {{ strtoupper(
                        substr(
                            auth()->user()?->name ?? 'U',
                            0,
                            1
                        )
                    ) }}
                </div>

                <div class="profile-information">
                    <strong>
                        {{ auth()->user()?->name ?? 'User' }}
                    </strong>

                    <span>
                        {{
                            auth()->user()?->role?->display_name
                            ?? 'System User'
                        }}
                    </span>
                </div>
            </div>
        </aside>

        {{-- Main section --}}
        <div class="main-section">

            <header class="topbar">
                <div class="topbar-left">
                    <button
                        class="menu-button"
                        id="sidebar-toggle"
                        type="button"
                        aria-label="Open navigation menu"
                        aria-controls="application-sidebar"
                        aria-expanded="false"
                    >
                        ☰
                    </button>

                    <div>
                        <p class="topbar-eyebrow">
                            DOST Impact Assessment System
                        </p>

                        <h1>
                            @yield('page-title', 'Dashboard')
                        </h1>
                    </div>
                </div>

                <div class="topbar-right">
                    <div class="topbar-user">
                        <div class="topbar-avatar">
                            {{ strtoupper(
                                substr(
                                    auth()->user()?->name ?? 'U',
                                    0,
                                    1
                                )
                            ) }}
                        </div>

                        <div class="topbar-user-copy">
                            <strong>
                                {{ auth()->user()?->name ?? 'User' }}
                            </strong>

                            <span>
                                {{
                                    auth()->user()?->role?->display_name
                                    ?? 'System User'
                                }}
                            </span>
                        </div>
                    </div>

                    <form
                        method="POST"
                        action="{{ route('logout') }}"
                    >
                        @csrf

                        <button
                            class="logout-button"
                            type="submit"
                        >
                            Logout
                        </button>
                    </form>
                </div>
            </header>

            <main class="page-content">

                {{-- Success message --}}
                @if(session('success'))
                    <div
                        class="alert alert-success"
                        role="alert"
                    >
                        <strong>Success:</strong>
                        {{ session('success') }}
                    </div>
                @endif

                {{-- General error message --}}
                @if(session('error'))
                    <div
                        class="alert alert-error"
                        role="alert"
                    >
                        <strong>Error:</strong>
                        {{ session('error') }}
                    </div>
                @endif

                {{-- Validation errors --}}
                @if($errors->any())
                    <div
                        class="alert alert-error"
                        role="alert"
                    >
                        <strong>
                            Please correct the following:
                        </strong>

                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </main>

            <footer class="application-footer">
                <span>
                    DOST Impact Assessment System
                </span>

                <span>
                    &copy; {{ date('Y') }}
                    Department of Science and Technology
                </span>
            </footer>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
