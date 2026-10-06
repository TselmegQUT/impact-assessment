@extends('layouts.app')

@section('title', 'Project Records')

@section('page-title', 'Project Records')

@section('content')

    <section class="page-header">
        <div class="page-header-copy">
            <h2>Project Records</h2>

            <p>
                View registered projects, assessment status,
                approved budgets, responsible officers and reports.
            </p>
        </div>

        @if(
            auth()->user()->hasRole('admin')
            || auth()->user()->hasRole('project_officer')
        )
            <div class="page-actions">
                <a
                    href="{{ route('projects.create') }}"
                    class="button button-primary"
                >
                    + Create Project
                </a>
            </div>
        @endif
    </section>

    {{-- Project summary --}}
    <section class="metric-grid">
        <article class="metric-card">
            <p class="metric-label">
                Total Projects
            </p>

            <p class="metric-value">
                {{ $projects->total() }}
            </p>

            <p class="metric-helper">
                All registered projects
            </p>
        </article>

        <article class="metric-card">
            <p class="metric-label">
                Records on This Page
            </p>

            <p class="metric-value">
                {{ $projects->count() }}
            </p>

            <p class="metric-helper">
                Current page project records
            </p>
        </article>

        <article class="metric-card">
            <p class="metric-label">
                Current Page
            </p>

            <p class="metric-value">
                {{ $projects->currentPage() }}
            </p>

            <p class="metric-helper">
                Page {{ $projects->currentPage() }}
                of {{ $projects->lastPage() }}
            </p>
        </article>

        <article class="metric-card">
            <p class="metric-label">
                Records Per Page
            </p>

            <p class="metric-value">
                {{ $projects->perPage() }}
            </p>

            <p class="metric-helper">
                Maximum displayed per page
            </p>
        </article>
    </section>

    {{-- Project table --}}
    <section class="panel">
        <div class="panel-header">
            <div>
                <h2>All Projects</h2>
            </div>

            <span class="status-badge status-neutral">
                {{ $projects->total() }}
                {{ Str::plural('record', $projects->total()) }}
            </span>
        </div>

        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Project</th>
                        <th>Sector</th>
                        <th>Approved Budget</th>
                        <th>Output Category</th>
                        <th>Status</th>
                        <th>Created By</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($projects as $project)
                        <tr>
                            <td>
                                <strong>
                                    {{ $project->project_code }}
                                </strong>

                                <div
                                    style="
                                        margin-top: 4px;
                                        color: var(--text-secondary);
                                    "
                                >
                                    {{ $project->title }}
                                </div>
                            </td>

                            <td>
                                {{
                                    $project->sector
                                    ?? 'Not specified'
                                }}
                            </td>

                            <td>
                                @if(
                                    $project->approved_budget
                                    !== null
                                )
                                    ₱{{
                                        number_format(
                                            (float)
                                            $project->approved_budget,
                                            2
                                        )
                                    }}
                                @else
                                    <span
                                        class="status-badge
                                            status-neutral"
                                    >
                                        Not entered
                                    </span>
                                @endif
                            </td>

                            <td>
                                @if(
                                    $project
                                        ->primary_output_category
                                )
                                    {{
                                        ucfirst(
                                            str_replace(
                                                '_',
                                                ' ',
                                                $project
                                                    ->primary_output_category
                                            )
                                        )
                                    }}
                                @else
                                    Not specified
                                @endif
                            </td>

                            <td>
                                @php
                                    $statusClass = match (
                                        $project->status
                                    ) {
                                        'completed' =>
                                            'status-success',

                                        'in_progress' =>
                                            'status-warning',

                                        default =>
                                            'status-neutral',
                                    };
                                @endphp

                                <span
                                    class="status-badge
                                        {{ $statusClass }}"
                                >
                                    {{
                                        ucfirst(
                                            str_replace(
                                                '_',
                                                ' ',
                                                $project->status
                                            )
                                        )
                                    }}
                                </span>
                            </td>

                            <td>
                                {{
                                    $project->creator?->name
                                    ?? 'Unknown'
                                }}
                            </td>

                            <td>
                                <a
                                    href="{{
                                        route(
                                            'projects.show',
                                            $project
                                        )
                                    }}"
                                    class="button button-secondary"
                                >
                                    Open
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div
                                    style="
                                        padding: 35px;
                                        text-align: center;
                                    "
                                >
                                    <strong>
                                        No projects found
                                    </strong>

                                    <p
                                        style="
                                            margin: 8px 0 18px;
                                            color:
                                                var(--text-secondary);
                                        "
                                    >
                                        Create your first project to
                                        begin an impact assessment.
                                    </p>

                                    @if(
                                        auth()->user()
                                            ->hasRole('admin')
                                        || auth()->user()
                                            ->hasRole(
                                                'project_officer'
                                            )
                                    )
                                        <a
                                            href="{{
                                                route(
                                                    'projects.create'
                                                )
                                            }}"
                                            class="button
                                                button-primary"
                                        >
                                            + Create Project
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($projects->hasPages())
            <div
                class="panel-body"
                style="
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
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
                    {{ $projects->firstItem() }}
                    to
                    {{ $projects->lastItem() }}
                    of
                    {{ $projects->total() }}
                    projects
                </p>

                <div class="page-actions">
                    @if($projects->onFirstPage())
                        <span
                            class="button button-secondary"
                            style="
                                cursor: not-allowed;
                                opacity: 0.5;
                            "
                        >
                            ← Previous
                        </span>
                    @else
                        <a
                            href="{{
                                $projects->previousPageUrl()
                            }}"
                            class="button button-secondary"
                        >
                            ← Previous
                        </a>
                    @endif

                    @if($projects->hasMorePages())
                        <a
                            href="{{ $projects->nextPageUrl() }}"
                            class="button button-primary"
                        >
                            Next →
                        </a>
                    @else
                        <span
                            class="button button-secondary"
                            style="
                                cursor: not-allowed;
                                opacity: 0.5;
                            "
                        >
                            Next →
                        </span>
                    @endif
                </div>
            </div>
        @endif
    </section>

@endsection
