@extends('layouts.app')

@section(
    'title',
    'Assessment Report - ' . $project->project_code
)

@section('page-title', 'Final Assessment Report')

@section('content')

    @php
        $reportStatusClass = match ($report->status) {
            'approved' => 'status-success',
            'submitted' => 'status-warning',
            'rejected' => 'status-danger',
            default => 'status-neutral',
        };

        $canSubmit =
            auth()->user()->hasRole('admin')
            || auth()->user()->hasRole('project_officer')
            || auth()->user()->hasRole('analyst');

        $performanceMetrics = [
            [
                'label' => 'Success Ratio',
                'value' => number_format(
                    $results[
                        'success_ratio_percent'
                    ] ?? 0,
                    2
                ) . '%',
                'description' =>
                    'Accomplishments compared with targets.',
            ],
            [
                'label' => 'Budget Efficiency',
                'value' =>
                    isset(
                        $results[
                            'budget_efficiency_percent'
                        ]
                    )
                    ? number_format(
                        $results[
                            'budget_efficiency_percent'
                        ],
                        2
                    ) . '%'
                    : 'N/A',
                'description' =>
                    'Approved budget compared with expenditure.',
            ],
            [
                'label' => 'Performance Grade',
                'value' =>
                    isset(
                        $results[
                            'performance_grade'
                        ]
                    )
                    ? number_format(
                        $results[
                            'performance_grade'
                        ],
                        2
                    ) . '%'
                    : 'N/A',
                'description' =>
                    $results[
                        'technical_rating'
                    ] ?? 'Not available',
            ],
            [
                'label' => 'Relevance Score',
                'value' => number_format(
                    $results[
                        'relevance_score'
                    ] ?? 0,
                    2
                ),
                'description' =>
                    'Weighted outputs and alignment.',
            ],
            [
                'label' => 'Immediate Monetary Value',
                'value' => '₱' . number_format(
                    $results[
                        'immediate_monetary_value'
                    ] ?? 0,
                    2
                ),
                'description' =>
                    'Estimated financial value of outputs.',
            ],
            [
                'label' => 'Impact Readiness Score',
                'value' =>
                    isset(
                        $results[
                            'impact_readiness_score'
                        ]
                    )
                    ? number_format(
                        $results[
                            'impact_readiness_score'
                        ],
                        2
                    )
                    : 'N/A',
                'description' =>
                    'Project readiness adjusted for maturity.',
            ],
            [
                'label' => 'Maturation Priority',
                'value' =>
                    isset(
                        $results[
                            'maturation_priority_score'
                        ]
                    )
                    ? number_format(
                        $results[
                            'maturation_priority_score'
                        ],
                        2
                    )
                    : 'N/A',
                'description' =>
                    $results[
                        'impact_track'
                    ] ?? 'Not assigned',
            ],
            [
                'label' => 'Risk Intensity Score',
                'value' =>
                    isset(
                        $results[
                            'risk_intensity_score'
                        ]
                    )
                    ? number_format(
                        $results[
                            'risk_intensity_score'
                        ],
                        2
                    )
                    : 'N/A',
                'description' =>
                    'Risk level: '
                    . (
                        $results[
                            'risk_level'
                        ] ?? 'Not assessed'
                    ),
            ],
        ];

        $riskCategories = [
            [
                'label' =>
                    'Operational or Technical',
                'value' =>
                    $project->risk
                        ?->operational_technical_count
                    ?? 0,
            ],
            [
                'label' => 'Institutional',
                'value' =>
                    $project->risk
                        ?->institutional_count
                    ?? 0,
            ],
            [
                'label' => 'Financial',
                'value' =>
                    $project->risk
                        ?->financial_count
                    ?? 0,
            ],
            [
                'label' =>
                    'Institutional and Financial',
                'value' =>
                    $project->risk
                        ?->institutional_financial_count
                    ?? 0,
            ],
        ];
    @endphp

    <style>
        .report-text {
            white-space: pre-line;
            line-height: 1.8;
        }

        .report-document-title {
            font-family:
                "Arial Black",
                Arial,
                sans-serif;

            font-size: 30px;
        }

        @media print {
            .sidebar,
            .topbar,
            .application-footer,
            .no-print,
            .sidebar-overlay {
                display: none !important;
            }

            .main-section {
                margin-left: 0 !important;
            }

            .page-content {
                max-width: none !important;
                padding: 0 !important;
            }

            body {
                background: white !important;
            }

            .panel,
            .metric-card {
                box-shadow: none !important;
                break-inside: avoid;
            }
        }
    </style>

    {{-- Report title --}}
    <section
        class="panel"
        style="margin-bottom: 22px;"
    >
        <div class="panel-header">
            <div>
                <span
                    class="status-badge status-neutral"
                    style="margin-bottom: 10px;"
                >
                    {{ $report->report_number }}
                </span>

                <h2 class="report-document-title">
                    Final Impact Assessment Report
                </h2>

                <p
                    style="
                        margin: 8px 0 0;
                        color: var(--text-secondary);
                    "
                >
                    {{ $project->title }}
                    ({{ $project->project_code }})
                </p>
            </div>

            <span
                class="status-badge
                    {{ $reportStatusClass }}"
            >
                {{ ucfirst($report->status) }}
            </span>
        </div>

        <div class="panel-body no-print">
            <div class="page-actions">
                <a
                    href="{{
                        route(
                            'projects.show',
                            $project
                        )
                    }}"
                    class="button button-secondary"
                >
                    ← Return to Project
                </a>

                <button
                    type="button"
                    class="button button-primary"
                    onclick="window.print()"
                >
                    Print Report
                </button>
            </div>
        </div>
    </section>

    {{-- Project information --}}
    <section
        class="panel"
        style="margin-bottom: 22px;"
    >
        <div class="panel-header">
            <h2>1. Project Information</h2>
        </div>

        <div class="table-container">
            <table class="data-table">
                <tbody>
                    <tr>
                        <th>Project Title</th>
                        <td>{{ $project->title }}</td>

                        <th>Project Code</th>
                        <td>{{ $project->project_code }}</td>
                    </tr>

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

                        <th>Project Status</th>

                        <td>
                            {{
                                ucwords(
                                    str_replace(
                                        '_',
                                        ' ',
                                        $project->status
                                    )
                                )
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
                        <th>Approved Budget</th>

                        <td>
                            @if(
                                $project->approved_budget
                                !== null
                            )
                                ₱{{
                                    number_format(
                                        (float)
                                        $project
                                            ->approved_budget,
                                        2
                                    )
                                }}
                            @else
                                Not entered
                            @endif
                        </td>

                        <th>Actual Expenditure</th>

                        <td>
                            @if(
                                $project
                                    ->actual_expenditure
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
                                Not entered
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>

    {{-- Executive summary --}}
    <section
        class="panel"
        style="margin-bottom: 22px;"
    >
        <div class="panel-header">
            <h2>2. Executive Summary</h2>
        </div>

        <div class="panel-body">
            <div class="report-text">
                {{ $report->executive_summary }}
            </div>
        </div>
    </section>

    {{-- Performance results --}}
    <section
        class="panel"
        style="margin-bottom: 22px;"
    >
        <div class="panel-header">
            <h2>3. Performance Results</h2>

            <span class="status-badge status-success">
                Automatically Calculated
            </span>
        </div>

        <div class="panel-body">
            <div class="metric-grid">
                @foreach(
                    $performanceMetrics as $metric
                )
                    <article class="metric-card">
                        <p class="metric-label">
                            {{ $metric['label'] }}
                        </p>

                        <p
                            class="metric-value"
                            style="
                                font-size:
                                    {{
                                        strlen(
                                            $metric['value']
                                        ) > 15
                                        ? '19px'
                                        : '28px'
                                    }};
                            "
                        >
                            {{ $metric['value'] }}
                        </p>

                        <p class="metric-helper">
                            {{ $metric['description'] }}
                        </p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Risk assessment --}}
    <section
        class="panel"
        style="margin-bottom: 22px;"
    >
        <div class="panel-header">
            <h2>4. Risk Assessment</h2>

            @php
                $riskStatusClass = match (
                    $results['risk_level']
                    ?? null
                ) {
                    'Low' => 'status-success',
                    'Medium' => 'status-warning',
                    'High' => 'status-danger',
                    default => 'status-neutral',
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
            <div class="form-grid">
                @foreach(
                    $riskCategories as $category
                )
                    <article class="metric-card">
                        <p class="metric-label">
                            {{ $category['label'] }}
                        </p>

                        <p class="metric-value">
                            {{ $category['value'] }}
                        </p>

                        <p class="metric-helper">
                            Recorded problems
                        </p>
                    </article>
                @endforeach
            </div>

            @if($project->risk?->risk_notes)
                <div
                    class="alert alert-error"
                    style="margin: 20px 0 0;"
                >
                    <strong>Risk notes:</strong>

                    <div
                        class="report-text"
                        style="margin-top: 8px;"
                    >
                        {{ $project->risk->risk_notes }}
                    </div>
                </div>
            @endif
        </div>
    </section>

    {{-- Findings --}}
    <section
        class="panel"
        style="margin-bottom: 22px;"
    >
        <div class="panel-header">
            <h2>5. Assessment Findings</h2>
        </div>

        <div class="panel-body">
            <div class="report-text">
                {{ $report->findings }}
            </div>
        </div>
    </section>

    {{-- Recommendation --}}
    <section
        class="panel"
        style="margin-bottom: 22px;"
    >
        <div class="panel-header">
            <h2>6. Final Recommendation</h2>

            <span class="status-badge status-success">
                Recommended Action
            </span>
        </div>

        <div class="panel-body">
            <div class="report-text">
                {{ $report->final_recommendation }}
            </div>
        </div>
    </section>

    {{-- Report information --}}
    <section
        class="panel"
        style="margin-bottom: 22px;"
    >
        <div class="panel-header">
            <h2>7. Report Information</h2>
        </div>

        <div class="table-container">
            <table class="data-table">
                <tbody>
                    <tr>
                        <th>Generated By</th>

                        <td>
                            {{
                                $report->generatedBy?->name
                                ?? 'Unknown'
                            }}
                        </td>

                        <th>Generated Date</th>

                        <td>
                            {{
                                $report->generated_at
                                    ?->format(
                                        'd M Y, h:i A'
                                    )
                                ?? 'Not available'
                            }}
                        </td>
                    </tr>

                    <tr>
                        <th>Reviewed By</th>

                        <td>
                            {{
                                $report->reviewedBy?->name
                                ?? 'Not reviewed'
                            }}
                        </td>

                        <th>Reviewed Date</th>

                        <td>
                            {{
                                $report->reviewed_at
                                    ?->format(
                                        'd M Y, h:i A'
                                    )
                                ?? 'Not reviewed'
                            }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>

    {{-- Manager review notes --}}
    @if($report->review_notes)
        <section
            class="panel"
            style="margin-bottom: 22px;"
        >
            <div class="panel-header">
                <h2>Manager Review Notes</h2>
            </div>

            <div class="panel-body">
                <div class="report-text">
                    {{ $report->review_notes }}
                </div>
            </div>
        </section>
    @endif

    {{-- Approved status --}}
    @if($report->status === 'approved')
        <div class="alert alert-success">
            <strong>Report Approved</strong>

            <p style="margin-bottom: 0;">
                This final assessment report was approved by
                {{
                    $report->reviewedBy?->name
                    ?? 'the Manager'
                }}.
            </p>
        </div>
    @endif

    {{-- Rejected status --}}
    @if($report->status === 'rejected')
        <div class="alert alert-error">
            <strong>Report Returned for Revision</strong>

            <p style="margin-bottom: 0;">
                Review the manager’s comments, correct the
                project data and generate the report again.
            </p>
        </div>
    @endif

    {{-- Submit report --}}
    @if(
        in_array(
            $report->status,
            ['draft', 'rejected']
        )
        && $canSubmit
    )
        <section class="panel no-print">
            <div class="panel-header">
                <h2>Submit Report for Review</h2>

                <span class="status-badge status-warning">
                    Manager Review Required
                </span>
            </div>

            <div class="panel-body">
                <p
                    style="
                        margin-top: 0;
                        color: var(--text-secondary);
                        line-height: 1.7;
                    "
                >
                    Check all project information before
                    submitting. After submission, the manager
                    can approve or return the report.
                </p>

                <form
                    method="POST"
                    action="{{
                        route(
                            'projects.reports.submit',
                            $project
                        )
                    }}"
                >
                    @csrf
                    @method('PATCH')

                    <button
                        type="submit"
                        class="button button-primary"
                    >
                        Submit to Manager
                    </button>
                </form>
            </div>
        </section>
    @endif

    {{-- Manager actions --}}
    @if(
        $report->status === 'submitted'
        && auth()->user()->hasRole('manager')
    )
        <section class="panel no-print">
            <div class="panel-header">
                <h2>Manager Review</h2>

                <span class="status-badge status-warning">
                    Decision Required
                </span>
            </div>

            <div class="panel-body">
                <div class="form-grid">

                    {{-- Approval --}}
                    <form
                        method="POST"
                        action="{{
                            route(
                                'projects.reports.approve',
                                $project
                            )
                        }}"
                    >
                        @csrf
                        @method('PATCH')

                        <div class="panel">
                            <div class="panel-header">
                                <h3>Approve Report</h3>

                                <span
                                    class="status-badge
                                        status-success"
                                >
                                    Approval
                                </span>
                            </div>

                            <div class="panel-body">
                                <div class="form-group">
                                    <label
                                        for="approval_notes"
                                        class="form-label"
                                    >
                                        Review Notes
                                        (Optional)
                                    </label>

                                    <textarea
                                        id="approval_notes"
                                        name="review_notes"
                                        class="form-control"
                                        placeholder="Enter optional approval notes."
                                    ></textarea>
                                </div>

                                <button
                                    type="submit"
                                    class="button
                                        button-primary"
                                    style="margin-top: 16px;"
                                >
                                    Approve Report
                                </button>
                            </div>
                        </div>
                    </form>

                    {{-- Rejection --}}
                    <form
                        method="POST"
                        action="{{
                            route(
                                'projects.reports.reject',
                                $project
                            )
                        }}"
                    >
                        @csrf
                        @method('PATCH')

                        <div class="panel">
                            <div class="panel-header">
                                <h3>Return for Revision</h3>

                                <span
                                    class="status-badge
                                        status-danger"
                                >
                                    Rejection
                                </span>
                            </div>

                            <div class="panel-body">
                                <div class="form-group">
                                    <label
                                        for="rejection_notes"
                                        class="form-label"
                                    >
                                        Reason for Rejection *
                                    </label>

                                    <textarea
                                        id="rejection_notes"
                                        name="review_notes"
                                        class="form-control"
                                        required
                                        placeholder="Explain what must be corrected."
                                    ></textarea>

                                    @error('review_notes')
                                        <span
                                            class="form-error"
                                        >
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>

                                <button
                                    type="submit"
                                    class="button button-danger"
                                    style="margin-top: 16px;"
                                >
                                    Return Report
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    @endif

@endsection
