@extends('layouts.app')

@section('title', $title)

@section('page-title', $title)

@section('content')

    @php
        $roleName =
            auth()->user()->role?->name;

        $roleDisplayName =
            auth()->user()->role?->display_name
            ?? 'System User';
    @endphp

    <section class="page-header">
        <div class="page-header-copy">
            <h2>
                Welcome back,
                {{ auth()->user()->name }}
            </h2>

            <p>
                You are signed in as
                <strong>{{ $roleDisplayName }}</strong>.
                Use the dashboard below to access your assigned
                project assessment activities.
            </p>
        </div>

        <div class="page-actions">
            <a
                href="{{ route('projects.index') }}"
                class="button button-primary"
            >
                View Projects
            </a>

            @if($roleName === 'project_officer')
                <a
                    href="{{ route('projects.create') }}"
                    class="button button-secondary"
                >
                    + Create Project
                </a>
            @endif
        </div>
    </section>

    {{-- Role summary --}}
    <section class="metric-grid">
        <article class="metric-card">
            <p class="metric-label">
                Current Role
            </p>

            <p
                class="metric-value"
                style="font-size: 21px;"
            >
                {{ $roleDisplayName }}
            </p>

            <p class="metric-helper">
                Your assigned system role
            </p>
        </article>

        <article class="metric-card">
            <p class="metric-label">
                Assigned Activities
            </p>

            <p class="metric-value">
                {{ count($tasks ?? []) }}
            </p>

            <p class="metric-helper">
                Main responsibilities
            </p>
        </article>

        <article class="metric-card">
            <p class="metric-label">
                System Access
            </p>

            <p
                class="metric-value"
                style="font-size: 22px;"
            >
                Active
            </p>

            <p class="metric-helper">
                Your account is authorised
            </p>
        </article>

        <article class="metric-card">
            <p class="metric-label">
                Assessment System
            </p>

            <p
                class="metric-value"
                style="font-size: 22px;"
            >
                Online
            </p>

            <p class="metric-helper">
                Ready for project assessment
            </p>
        </article>
    </section>

    {{-- Assigned activities --}}
    <section
        class="panel"
        style="margin-bottom: 22px;"
    >
        <div class="panel-header">
            <div>
                <h2>Your Assigned Activities</h2>
            </div>

            <span class="status-badge status-success">
                {{ $roleDisplayName }}
            </span>
        </div>

        <div class="panel-body">
            <div class="form-grid">
                @forelse(($tasks ?? []) as $task)
                    <article class="panel">
                        <div class="panel-header">
                            <div
                                class="navigation-icon"
                                style="
                                    color: var(--black);
                                    background:
                                        var(--dost-blue);
                                "
                            >
                                {{ $loop->iteration }}
                            </div>

                            <span
                                class="status-badge
                                    status-neutral"
                            >
                                Assigned
                            </span>
                        </div>

                        <div class="panel-body">
                            <h3
                                style="
                                    margin-top: 0;
                                    margin-bottom: 8px;
                                "
                            >
                                {{ $task['title'] }}
                            </h3>

                            <p
                                style="
                                    margin: 0;
                                    min-height: 48px;
                                    color:
                                        var(--text-secondary);
                                    line-height: 1.6;
                                "
                            >
                                {{ $task['description'] }}
                            </p>
                        </div>
                    </article>
                @empty
                    <p>
                        No activities have been assigned to
                        this role.
                    </p>
                @endforelse
            </div>
        </div>
    </section>

    {{-- Role-specific next actions --}}
    <section class="panel">
        <div class="panel-header">
            <h2>Recommended Next Action</h2>

            <span class="status-badge status-warning">
                Next Step
            </span>
        </div>

        <div class="panel-body">
            @if($roleName === 'analyst')
                <p
                    style="
                        margin-top: 0;
                        color: var(--text-secondary);
                        line-height: 1.7;
                    "
                >
                    Open a project to review its 6P outputs,
                    validate extracted data, assess risks and
                    calculate impact results.
                </p>

                <a
                    href="{{ route('projects.index') }}"
                    class="button button-primary"
                >
                    Review Projects
                </a>
            @elseif($roleName === 'project_officer')
                <p
                    style="
                        margin-top: 0;
                        color: var(--text-secondary);
                        line-height: 1.7;
                    "
                >
                    Register a new project or open an existing
                    record to enter supporting project data.
                </p>

                <div class="page-actions">
                    <a
                        href="{{ route('projects.create') }}"
                        class="button button-primary"
                    >
                        + Create Project
                    </a>

                    <a
                        href="{{ route('projects.index') }}"
                        class="button button-secondary"
                    >
                        View Projects
                    </a>
                </div>
            @elseif($roleName === 'manager')
                <p
                    style="
                        margin-top: 0;
                        color: var(--text-secondary);
                        line-height: 1.7;
                    "
                >
                    Open the project records to review assessment
                    findings, recommendations and reports awaiting
                    a management decision.
                </p>

                <a
                    href="{{ route('projects.index') }}"
                    class="button button-primary"
                >
                    Review Assessment Reports
                </a>
            @else
                <p>
                    Open the project list to continue.
                </p>

                <a
                    href="{{ route('projects.index') }}"
                    class="button button-primary"
                >
                    View Projects
                </a>
            @endif
        </div>
    </section>

@endsection
