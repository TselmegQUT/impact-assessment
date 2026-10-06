@extends('layouts.app')
@section('title', $project->title)
@section('page-title', 'Project Details')
@section('content')
    @php
        $statusClass = match ($project->status) {
            'completed' => 'status-success',
            'in_progress' => 'status-warning',
            default => 'status-neutral',
        };
        $canAssess =
            auth()->user()->hasRole('admin')
            || auth()->user()->hasRole('project_officer')
            || auth()->user()->hasRole('analyst');
    @endphp
    <section class="page-header">
        <div class="page-header-copy">
            <h2>{{ $project->title }}</h2>
            <p>
                {{ $project->description
                    ?? 'No project description was entered.' }}
            </p>
        </div>
        <div class="page-actions">
            <a
                href="{{ route('projects.index') }}"
                class="button button-secondary"
            >
                ← Back to Projects
            </a>
        </div>
    </section>
    {{-- Project summary --}}
    <section
        class="panel"
        style="margin-bottom: 22px;"
    >
        <div class="panel-header">
            <div>
                <span
                    class="status-badge status-neutral"
                    style="margin-bottom: 8px;"
                >
                    {{ $project->project_code }}
                </span>
                <h2>{{ $project->title }}</h2>
            </div>
            <span class="status-badge {{ $statusClass }}">
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
        </div>
        <div class="table-container">
            <table class="data-table">
                <tbody>
                    <tr>
                        <th>Implementing Agency</th>
                        <td>
                            {{
                                $project->implementing_agency
                                ?? 'Not specified'
                            }}
                        </td>
                        <th>Project Leader</th>
                        <td>
                            {{
                                $project->project_leader
                                ?? 'Not specified'
                            }}
                        </td>
                    </tr>
                    <tr>
                        <th>Sector</th>
                        <td>
                            {{
                                $project->sector
                                ?? 'Not specified'
                            }}
                        </td>
                        <th>Created By</th>
                        <td>
                            {{
                                $project->creator?->name
                                ?? 'Unknown'
                            }}
                        </td>
                    </tr>
                    <tr>
                        <th>Start Date</th>
                        <td>
                            {{
                                $project->start_date
                                    ?->format('d M Y')
                                ?? 'Not entered'
                            }}
                        </td>
                        <th>Completion Date</th>
                        <td>
                            {{
                                $project->completion_date
                                    ?->format('d M Y')
                                ?? 'Not completed'
                            }}
                        </td>
                    </tr>
                    <tr>
                        <th>Primary Output</th>
                        <td colspan="3">
                            @if(
                                $project
                                    ->primary_output_category
                            )
                                {{
                                    ucwords(
                                        str_replace(
                                            '_',
                                            ' ',
                                            $project
                                                ->primary_output_category
                                        )
                                    )
                                }}
                            @else
                                Not selected
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
    {{-- Performance inputs --}}
    <section
        class="panel"
        style="margin-bottom: 22px;"
    >
        <div class="panel-header">
            <h2>Performance Inputs</h2>
        </div>
        <div class="panel-body">
            <div class="metric-grid">
                <article class="metric-card">
                    <p class="metric-label">
                        Approved Budget
                    </p>
                    <p
                        class="metric-value"
                        style="font-size: 23px;"
                    >
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
                            N/A
                        @endif
                    </p>
                    <p class="metric-helper">
                        Total approved project funding
                    </p>
                </article>
                <article class="metric-card">
                    <p class="metric-label">
                        Actual Expenditure
                    </p>
                    <p
                        class="metric-value"
                        style="font-size: 23px;"
                    >
                        @if(
                            $project->actual_expenditure
                            !== null
                        )
                            ₱{{
                                number_format(
                                    (float)
                                    $project
                                        ->actual_expenditure,
                                    2
                                )
                            }}
                        @else
                            N/A
                        @endif
                    </p>
                    <p class="metric-helper">
                        Current recorded expenditure
                    </p>
                </article>
                <article class="metric-card">
                    <p class="metric-label">
                        Target Objectives
                    </p>
                    <p class="metric-value">
                        {{
                            $project
                                ->total_target_objectives
                        }}
                    </p>
                    <p class="metric-helper">
                        Planned project objectives
                    </p>
                </article>
                <article class="metric-card">
                    <p class="metric-label">
                        Accomplishments
                    </p>
                    <p class="metric-value">
                        {{
                            $project
                                ->total_accomplishments
                        }}
                    </p>
                    <p class="metric-helper">
                        Objectives already achieved
                    </p>
                </article>
            </div>
        </div>
    </section>
    {{-- Assessment modules --}}
    <section
        class="panel"
        style="margin-bottom: 22px;"
    >
        <div class="panel-header">
            <div>
                <h2>Assessment Modules</h2>
            </div>
            <span class="status-badge status-neutral">
                Project Assessment
            </span>
        </div>
        <div class="panel-body">
            <div class="form-grid">
                {{-- 6P outputs --}}
                <article class="panel">
                    <div class="panel-header">
                        <h3>6P Outputs</h3>
                        @if($project->output)
                            <span
                                class="status-badge
                                    status-success"
                            >
                                Complete
                            </span>
                        @else
                            <span
                                class="status-badge
                                    status-warning"
                            >
                                Not Started
                            </span>
                        @endif
                    </div>
                    <div class="panel-body">
                        <p
                            style="
                                margin-top: 0;
                                color: var(--text-secondary);
                                line-height: 1.6;
                            "
                        >
                            Record publications, patents,
                            products, partnerships, policies and
                            people services.
                        </p>
                        @if($canAssess)
                            <a
                                href="{{
                                    route(
                                        'projects.outputs.edit',
                                        $project
                                    )
                                }}"
                                class="button button-primary"
                            >
                                {{
                                    $project->output
                                    ? 'Edit 6P Outputs'
                                    : 'Enter 6P Outputs'
                                }}
                            </a>
                        @endif
                    </div>
                </article>
                {{-- Risk assessment --}}
                <article class="panel">
                    <div class="panel-header">
                        <h3>Risk Assessment</h3>
                        @if($project->risk)
                            <span
                                class="status-badge
                                    status-success"
                            >
                                Complete
                            </span>
                        @else
                            <span
                                class="status-badge
                                    status-warning"
                            >
                                Not Started
                            </span>
                        @endif
                    </div>
                    <div class="panel-body">
                        <p
                            style="
                                margin-top: 0;
                                color: var(--text-secondary);
                                line-height: 1.6;
                            "
                        >
                            Record operational, institutional
                            and financial project risks.
                        </p>
                        @if($canAssess)
                            <a
                                href="{{
                                    route(
                                        'projects.risks.edit',
                                        $project
                                    )
                                }}"
                                class="button button-primary"
                            >
                                {{
                                    $project->risk
                                    ? 'Edit Risk Assessment'
                                    : 'Enter Risk Assessment'
                                }}
                            </a>
                        @endif
                    </div>
                </article>
                {{-- Project documents and data ingestion --}}
                <article class="panel">
                    <div class="panel-header">
                        <h3>Documents & Data Ingestion</h3>
                        <span class="status-badge status-neutral">
                            Upload Available
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
                            Upload PDF or DOCX project reports,
                            manage supporting files and prepare
                            documents for automatic data extraction.
                        </p>
                        <a
                            href="{{ route(
                                'projects.documents.index',
                                $project
                            ) }}"
                            class="button button-primary"
                        >
                            Manage Documents
                        </a>
                    </div>
                </article>
                {{-- Formula results --}}
                <article class="panel">
                    <div class="panel-header">
                        <h3>Formula Results</h3>
                        @if($results)
                            <span
                                class="status-badge
                                    status-success"
                            >
                                Available
                            </span>
                        @else
                            <span
                                class="status-badge
                                    status-warning"
                            >
                                Waiting for Data
                            </span>
                        @endif
                    </div>
                    <div class="panel-body">
                        <p
                            style="
                                margin-top: 0;
                                color: var(--text-secondary);
                                line-height: 1.6;
                            "
                        >
                            Project scores are calculated
                            automatically after the required
                            assessment data is entered.
                        </p>
                        @if($results)
                            <span
                                class="status-badge
                                    status-success"
                            >
                                Calculation Complete
                            </span>
                        @else
                            <span
                                class="status-badge
                                    status-neutral"
                            >
                                Complete 6P Outputs
                            </span>
                        @endif
                    </div>
                </article>
                {{-- Final report --}}
                <article class="panel">
                    <div class="panel-header">
                        <h3>Final Assessment Report</h3>
                        @if($project->report)
                            @php
                                $reportStatusClass = match (
                                    $project->report->status
                                ) {
                                    'approved' =>
                                        'status-success',
                                    'submitted' =>
                                        'status-warning',
                                    'rejected' =>
                                        'status-danger',
                                    default =>
                                        'status-neutral',
                                };
                            @endphp
                            <span
                                class="status-badge
                                    {{ $reportStatusClass }}"
                            >
                                {{
                                    ucfirst(
                                        $project
                                            ->report
                                            ->status
                                    )
                                }}
                            </span>
                        @else
                            <span
                                class="status-badge
                                    status-neutral"
                            >
                                Not Generated
                            </span>
                        @endif
                    </div>
                    <div class="panel-body">
                        @if($project->report)
                            <p
                                style="
                                    margin-top: 0;
                                    color:
                                        var(--text-secondary);
                                    line-height: 1.6;
                                "
                            >
                                The final assessment report has
                                been generated and is available
                                for review.
                            </p>
                            <div class="page-actions">
                                <a
                                    href="{{
                                        route(
                                            'projects.reports.show',
                                            $project
                                        )
                                    }}"
                                    class="button
                                        button-secondary"
                                >
                                    View Report
                                </a>
                                @if(
                                    in_array(
                                        $project
                                            ->report
                                            ->status,
                                        [
                                            'draft',
                                            'rejected',
                                        ]
                                    )
                                    && $canAssess
                                )
                                    <form
                                        method="POST"
                                        action="{{
                                            route(
                                                'projects.reports.generate',
                                                $project
                                            )
                                        }}"
                                    >
                                        @csrf
                                        <button
                                            type="submit"
                                            class="button
                                                button-primary"
                                        >
                                            Regenerate
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @elseif(
                            $project->output
                            && $project->risk
                        )
                            <p
                                style="
                                    margin-top: 0;
                                    color:
                                        var(--text-secondary);
                                    line-height: 1.6;
                                "
                            >
                                Output and risk information are
                                complete. The report is ready to
                                generate.
                            </p>
                            @if($canAssess)
                                <form
                                    method="POST"
                                    action="{{
                                        route(
                                            'projects.reports.generate',
                                            $project
                                        )
                                    }}"
                                >
                                    @csrf
                                    <button
                                        type="submit"
                                        class="button
                                            button-primary"
                                    >
                                        Generate Final Report
                                    </button>
                                </form>
                            @endif
                        @else
                            <p
                                style="
                                    margin-top: 0;
                                    color:
                                        var(--text-secondary);
                                    line-height: 1.6;
                                "
                            >
                                Complete the 6P Outputs and Risk
                                Assessment before generating a
                                final report.
                            </p>
                            <span
                                class="status-badge
                                    status-warning"
                            >
                                Required Data Incomplete
                            </span>
                        @endif
                    </div>
                </article>
            </div>
        </div>
    </section>
    {{-- Calculated results --}}
    @if($results)
        <section
            class="panel"
            style="margin-bottom: 22px;"
        >
            <div class="panel-header">
                <div>
                    <h2>Calculated Impact Results</h2>
                </div>
                <span class="status-badge status-success">
                    Automatically Calculated
                </span>
            </div>
            <div class="panel-body">
                <div class="metric-grid">
                    <article class="metric-card">
                        <p class="metric-label">
                            Success Ratio
                        </p>
                        <p class="metric-value">
                            {{
                                number_format(
                                    $results[
                                        'success_ratio_percent'
                                    ],
                                    2
                                )
                            }}%
                        </p>
                        <p class="metric-helper">
                            Accomplishments against targets
                        </p>
                    </article>
                    <article class="metric-card">
                        <p class="metric-label">
                            Budget Efficiency
                        </p>
                        <p class="metric-value">
                            @if(
                                $results[
                                    'budget_efficiency_percent'
                                ] !== null
                            )
                                {{
                                    number_format(
                                        $results[
                                            'budget_efficiency_percent'
                                        ],
                                        2
                                    )
                                }}%
                            @else
                                N/A
                            @endif
                        </p>
                        <p class="metric-helper">
                            Approved versus actual spending
                        </p>
                    </article>
                    <article class="metric-card">
                        <p class="metric-label">
                            Performance Grade
                        </p>
                        <p class="metric-value">
                            @if(
                                $results[
                                    'performance_grade'
                                ] !== null
                            )
                                {{
                                    number_format(
                                        $results[
                                            'performance_grade'
                                        ],
                                        2
                                    )
                                }}%
                            @else
                                N/A
                            @endif
                        </p>
                        <p class="metric-helper">
                            {{
                                $results[
                                    'technical_rating'
                                ]
                            }}
                        </p>
                    </article>
                    <article class="metric-card">
                        <p class="metric-label">
                            Relevance Score
                        </p>
                        <p class="metric-value">
                            {{
                                number_format(
                                    $results[
                                        'relevance_score'
                                    ],
                                    2
                                )
                            }}
                        </p>
                        <p class="metric-helper">
                            Weighted outputs and alignment
                        </p>
                    </article>
                    <article class="metric-card">
                        <p class="metric-label">
                            Immediate Monetary Value
                        </p>
                        <p
                            class="metric-value"
                            style="font-size: 22px;"
                        >
                            ₱{{
                                number_format(
                                    $results[
                                        'immediate_monetary_value'
                                    ],
                                    2
                                )
                            }}
                        </p>
                        <p class="metric-helper">
                            Estimated value of outputs
                        </p>
                    </article>
                    <article class="metric-card">
                        <p class="metric-label">
                            Impact Readiness Score
                        </p>
                        <p class="metric-value">
                            {{
                                $results[
                                    'impact_readiness_score'
                                ] !== null
                                ? number_format(
                                    $results[
                                        'impact_readiness_score'
                                    ],
                                    2
                                )
                                : 'N/A'
                            }}
                        </p>
                        <p class="metric-helper">
                            Output readiness and maturity
                        </p>
                    </article>
                    <article class="metric-card">
                        <p class="metric-label">
                            Maturation Priority
                        </p>
                        <p class="metric-value">
                            {{
                                $results[
                                    'maturation_priority_score'
                                ] !== null
                                ? number_format(
                                    $results[
                                        'maturation_priority_score'
                                    ],
                                    2
                                )
                                : 'N/A'
                            }}
                        </p>
                        <p class="metric-helper">
                            {{
                                $results[
                                    'impact_track'
                                ]
                            }}
                        </p>
                    </article>
                    <article class="metric-card">
                        <p class="metric-label">
                            Risk Intensity Score
                        </p>
                        <p class="metric-value">
                            {{
                                $results[
                                    'risk_intensity_score'
                                ] !== null
                                ? number_format(
                                    $results[
                                        'risk_intensity_score'
                                    ],
                                    2
                                )
                                : 'N/A'
                            }}
                        </p>
                        <p class="metric-helper">
                            Risk level:
                            {{
                                $results['risk_level']
                                ?? 'Not Assessed'
                            }}
                        </p>
                    </article>
                </div>
            </div>
        </section>
        {{-- Recommendations --}}
        <section class="form-grid">
            <article class="panel">
                <div class="panel-header">
                    <h2>Roadmap Recommendation</h2>
                    <span class="status-badge status-success">
                        Recommended Action
                    </span>
                </div>
                <div class="panel-body">
                    <p
                        style="
                            margin: 0;
                            line-height: 1.7;
                        "
                    >
                        {{ $results['recommendation'] }}
                    </p>
                </div>
            </article>
            <article class="panel">
                <div class="panel-header">
                    <h2>Risk Recommendation</h2>
                    @php
                        $riskStatusClass = match (
                            $results['risk_level']
                            ?? null
                        ) {
                            'Low' =>
                                'status-success',
                            'Medium' =>
                                'status-warning',
                            'High' =>
                                'status-danger',
                            default =>
                                'status-neutral',
                        };
                    @endphp
                    <span
                        class="status-badge
                            {{ $riskStatusClass }}"
                    >
                        {{
                            $results['risk_level']
                            ?? 'Not Assessed'
                        }}
                    </span>
                </div>
                <div class="panel-body">
                    <p
                        style="
                            margin: 0;
                            line-height: 1.7;
                        "
                    >
                        {{
                            $results[
                                'risk_recommendation'
                            ]
                        }}
                    </p>
                </div>
            </article>
        </section>
    @endif
@endsection
