<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>{{ $project->title }}</title>

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
            max-width: 1100px;
            margin: 40px auto;
        }

        .project-header,
        .section {
            margin-bottom: 22px;
            padding: 30px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.07);
        }

        h1 {
            margin: 5px 0 8px;
        }

        h2 {
            margin-top: 0;
        }

        .project-code {
            color: #15803d;
            font-weight: bold;
        }

        .message {
            margin-bottom: 22px;
            padding: 14px;
            color: #166534;
            background: #dcfce7;
            border-radius: 7px;
        }

        .status {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: bold;
        }

        .draft {
            color: #475569;
            background: #e2e8f0;
        }

        .in-progress {
            color: #92400e;
            background: #fef3c7;
        }

        .completed {
            color: #166534;
            background: #dcfce7;
        }

        .information-grid,
        .result-grid,
        .module-grid {
            display: grid;
            grid-template-columns: repeat(
                2,
                minmax(0, 1fr)
            );
            gap: 18px;
        }

        .information-item,
        .result-card,
        .module {
            padding: 18px;
            border-radius: 8px;
            background: #f8fafc;
        }

        .label {
            display: block;
            margin-bottom: 7px;
            color: #64748b;
            font-size: 13px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .result-value {
            color: #14532d;
            font-size: 27px;
            font-weight: bold;
        }

        .result-description {
            margin-bottom: 0;
            color: #64748b;
            font-size: 14px;
        }

        .module {
            border-left: 5px solid #15803d;
        }

        .module h3 {
            margin-top: 0;
        }

        .button {
            display: inline-block;
            margin-top: 10px;
            padding: 10px 16px;
            border-radius: 6px;
            color: white;
            background: #15803d;
            text-decoration: none;
        }

        .pending {
            color: #92400e;
            font-size: 13px;
            font-weight: bold;
        }

        .available {
            color: #166534;
            font-size: 13px;
            font-weight: bold;
        }

        .recommendation {
            padding: 20px;
            border-left: 5px solid #2563eb;
            border-radius: 7px;
            background: #eff6ff;
        }

        .recommendation h3 {
            margin-top: 0;
        }

        .risk-recommendation {
            margin-top: 18px;
            padding: 20px;
            border-left: 5px solid #dc2626;
            border-radius: 7px;
            background: #fef2f2;
        }

        .risk-recommendation h3 {
            margin-top: 0;
        }

        .risk-low {
            color: #166534;
        }

        .risk-medium {
            color: #b45309;
        }

        .risk-high {
            color: #b91c1c;
        }

        @media (max-width: 750px) {
            header {
                padding: 18px 20px;
            }

            main {
                margin: 20px;
            }

            .information-grid,
            .result-grid,
            .module-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <header>
        <strong>Impact Assessment System</strong>

        <a href="{{ route('projects.index') }}">
            ← Return to Projects
        </a>
    </header>

    <main>
        @if(session('success'))
            <div class="message">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
    <div class="message" style="
        color: #991b1b;
        background: #fee2e2;
    ">
        {{ session('error') }}
    </div>
@endif

        <section class="project-header">
            <span class="project-code">
                {{ $project->project_code }}
            </span>

            <h1>{{ $project->title }}</h1>

            <p>
                {{ $project->description
                    ?? 'No project description was entered.' }}
            </p>

            @if($project->status === 'completed')
                <span class="status completed">
                    Completed
                </span>
            @elseif($project->status === 'in_progress')
                <span class="status in-progress">
                    In Progress
                </span>
            @else
                <span class="status draft">
                    Draft
                </span>
            @endif
        </section>

        <section class="section">
            <h2>Project Information</h2>

            <div class="information-grid">
                <div class="information-item">
                    <span class="label">
                        Implementing agency
                    </span>

                    {{ $project->implementing_agency
                        ?? 'Not specified' }}
                </div>

                <div class="information-item">
                    <span class="label">
                        Project leader
                    </span>

                    {{ $project->project_leader
                        ?? 'Not specified' }}
                </div>

                <div class="information-item">
                    <span class="label">
                        Sector
                    </span>

                    {{ $project->sector ?? 'Not specified' }}
                </div>

                <div class="information-item">
                    <span class="label">
                        Created by
                    </span>

                    {{ $project->creator?->name ?? 'Unknown' }}
                </div>

                <div class="information-item">
                    <span class="label">
                        Start date
                    </span>

                    {{ $project->start_date->format('d M Y') }}
                </div>

                <div class="information-item">
                    <span class="label">
                        Completion date
                    </span>

                    {{ $project->completion_date
                        ? $project->completion_date
                            ->format('d M Y')
                        : 'Not completed' }}
                </div>
            </div>
        </section>

        <section class="section">
            <h2>Performance Inputs</h2>

            <div class="information-grid">
                <div class="information-item">
                    <span class="label">
                        Approved budget
                    </span>

                    {{ $project->approved_budget !== null
                        ? '₱' . number_format(
                            (float) $project->approved_budget,
                            2
                        )
                        : 'Not entered' }}
                </div>

                <div class="information-item">
                    <span class="label">
                        Actual expenditure
                    </span>

                    {{ $project->actual_expenditure !== null
                        ? '₱' . number_format(
                            (float) $project->actual_expenditure,
                            2
                        )
                        : 'Not entered' }}
                </div>

                <div class="information-item">
                    <span class="label">
                        Target objectives
                    </span>

                    {{ $project->total_target_objectives }}
                </div>

                <div class="information-item">
                    <span class="label">
                        Accomplishments
                    </span>

                    {{ $project->total_accomplishments }}
                </div>

                <div class="information-item">
                    <span class="label">
                        Primary output
                    </span>

                    {{ $project->primary_output_category
                        ? ucwords(str_replace(
                            '_',
                            ' ',
                            $project->primary_output_category
                        ))
                        : 'Not selected' }}
                </div>
            </div>
        </section>

        <section class="section">
            <h2>Assessment Modules</h2>

            <div class="module-grid">
                <div class="module">
                    <h3>6P Outputs</h3>

                    @if($project->output)
                        <p>
                            The project output information has
                            been entered.
                        </p>

                        <span class="available">
                            Data available
                        </span>
                    @else
                        <p>
                            Enter output quantities, monetary
                            values and strategic alignment.
                        </p>

                        <span class="pending">
                            Not started
                        </span>
                    @endif

                    @if(
                        auth()->user()->hasRole('admin') ||
                        auth()->user()->hasRole('project_officer') ||
                        auth()->user()->hasRole('analyst')
                    )
                        <br>

                        <a
                            class="button"
                            href="{{ route(
                                'projects.outputs.edit',
                                $project
                            ) }}"
                        >
                            {{ $project->output
                                ? 'Edit 6P Outputs'
                                : 'Enter 6P Outputs' }}
                        </a>
                    @endif
                </div>

                <div class="module">
                    <h3>Risk Assessment</h3>

                    @if($project->risk)
                        <p>
                            The project risk information has
                            been entered.
                        </p>

                        <span class="available">
                            Risk data available
                        </span>
                    @else
                        <p>
                            Record project problems and their
                            risk categories.
                        </p>

                        <span class="pending">
                            Not started
                        </span>
                    @endif

                    @if(
                        auth()->user()->hasRole('admin') ||
                        auth()->user()->hasRole('project_officer') ||
                        auth()->user()->hasRole('analyst')
                    )
                        <br>

                        <a
                            class="button"
                            href="{{ route(
                                'projects.risks.edit',
                                $project
                            ) }}"
                        >
                            {{ $project->risk
                                ? 'Edit Risk Assessment'
                                : 'Enter Risk Assessment' }}
                        </a>
                    @endif
                </div>

                <div class="module">
                    <h3>Formula Results</h3>

                    @if($results)
                        <p>
                            The project scores were calculated
                            automatically.
                        </p>

                        <span class="available">
                            Results available
                        </span>
                    @else
                        <p>
                            Results require completed 6P
                            output information.
                        </p>

                        <span class="pending">
                            Not calculated
                        </span>
                    @endif
                </div>

                <div class="module">
    <h3>Final Report</h3>

    @if($project->report)
        <p>
            The final assessment report has
            been generated.
        </p>

        <span class="available">
            Status:
            {{ ucfirst($project->report->status) }}
        </span>

        <br>

        <a
            class="button"
            href="{{ route(
                'projects.reports.show',
                $project
            ) }}"
        >
            View Final Report
        </a>

        @if(
            in_array(
                $project->report->status,
                ['draft', 'rejected']
            ) &&
            (
                auth()->user()->hasRole('admin') ||
                auth()->user()->hasRole('project_officer') ||
                auth()->user()->hasRole('analyst')
            )
        )
            <form
                method="POST"
                action="{{ route(
                    'projects.reports.generate',
                    $project
                ) }}"
                style="display: inline;"
            >
                @csrf

                <button
                    type="submit"
                    class="button"
                >
                    Regenerate Report
                </button>
            </form>
        @endif
    @elseif(
        $project->output &&
        $project->risk
    )
        <p>
            The required output and risk data
            are ready.
        </p>

        <span class="available">
            Ready to generate
        </span>

        @if(
            auth()->user()->hasRole('admin') ||
            auth()->user()->hasRole('project_officer') ||
            auth()->user()->hasRole('analyst')
        )
            <form
                method="POST"
                action="{{ route(
                    'projects.reports.generate',
                    $project
                ) }}"
            >
                @csrf

                <button
                    type="submit"
                    class="button"
                >
                    Generate Final Report
                </button>
            </form>
        @endif
    @else
        <p>
            Complete the 6P Outputs and Risk
            Assessment before generating the report.
        </p>

        <span class="pending">
            Required information is incomplete
        </span>
    @endif
</div>
            </div>
        </section>

        @if($results)
            <section class="section">
                <h2>Calculated Results</h2>

                <div class="result-grid">
                    <div class="result-card">
                        <span class="label">
                            Success Ratio
                        </span>

                        <div class="result-value">
                            {{ number_format(
                                $results[
                                    'success_ratio_percent'
                                ],
                                2
                            ) }}%
                        </div>

                        <p class="result-description">
                            Accomplishments compared with
                            target objectives.
                        </p>
                    </div>

                    <div class="result-card">
                        <span class="label">
                            Budget Efficiency
                        </span>

                        <div class="result-value">
                            @if(
                                $results[
                                    'budget_efficiency_percent'
                                ] !== null
                            )
                                {{ number_format(
                                    $results[
                                        'budget_efficiency_percent'
                                    ],
                                    2
                                ) }}%
                            @else
                                N/A
                            @endif
                        </div>

                        <p class="result-description">
                            Approved budget compared with
                            actual expenditure.
                        </p>
                    </div>

                    <div class="result-card">
                        <span class="label">
                            Performance Grade
                        </span>

                        <div class="result-value">
                            @if(
                                $results[
                                    'performance_grade'
                                ] !== null
                            )
                                {{ number_format(
                                    $results[
                                        'performance_grade'
                                    ],
                                    2
                                ) }}%
                            @else
                                N/A
                            @endif
                        </div>

                        <p class="result-description">
                            {{ $results['technical_rating'] }}
                        </p>
                    </div>

                    <div class="result-card">
                        <span class="label">
                            Relevance Score
                        </span>

                        <div class="result-value">
                            {{ number_format(
                                $results['relevance_score'],
                                2
                            ) }}
                        </div>

                        <p class="result-description">
                            Weighted 6P outputs and strategic
                            alignment.
                        </p>
                    </div>

                    <div class="result-card">
                        <span class="label">
                            Immediate Monetary Value
                        </span>

                        <div class="result-value">
                            ₱{{ number_format(
                                $results[
                                    'immediate_monetary_value'
                                ],
                                2
                            ) }}
                        </div>

                        <p class="result-description">
                            Estimated financial value of all
                            recorded outputs.
                        </p>
                    </div>

                    <div class="result-card">
                        <span class="label">
                            Impact Readiness Score
                        </span>

                        <div class="result-value">
                            {{ $results[
                                'impact_readiness_score'
                            ] !== null
                                ? number_format(
                                    $results[
                                        'impact_readiness_score'
                                    ],
                                    2
                                )
                                : 'N/A' }}
                        </div>

                        <p class="result-description">
                            Outputs and success adjusted for
                            project maturity.
                        </p>
                    </div>

                    <div class="result-card">
                        <span class="label">
                            Maturation Priority Score
                        </span>

                        <div class="result-value">
                            {{ $results[
                                'maturation_priority_score'
                            ] !== null
                                ? number_format(
                                    $results[
                                        'maturation_priority_score'
                                    ],
                                    2
                                )
                                : 'N/A' }}
                        </div>

                        <p class="result-description">
                            {{ $results['impact_track'] }}
                        </p>
                    </div>

                    <div class="result-card">
                        <span class="label">
                            Time Maturation Index
                        </span>

                        <div class="result-value">
                            {{ $results[
                                'time_maturation_index'
                            ] ?? 'N/A' }}
                        </div>

                        <p class="result-description">
                            Months elapsed:
                            {{ $results['months_elapsed']
                                ?? 'N/A' }}
                        </p>
                    </div>

                    <div class="result-card">
                        <span class="label">
                            Risk Intensity Score
                        </span>

                        <div class="result-value">
                            {{ $results[
                                'risk_intensity_score'
                            ] !== null
                                ? number_format(
                                    $results[
                                        'risk_intensity_score'
                                    ],
                                    2
                                )
                                : 'N/A' }}
                        </div>

                        <p class="result-description">
                            Risk level:

                            @if($results['risk_level'] === 'Low')
                                <strong class="risk-low">
                                    Low
                                </strong>
                            @elseif(
                                $results['risk_level'] === 'Medium'
                            )
                                <strong class="risk-medium">
                                    Medium
                                </strong>
                            @elseif(
                                $results['risk_level'] === 'High'
                            )
                                <strong class="risk-high">
                                    High
                                </strong>
                            @else
                                <strong>
                                    Not Assessed
                                </strong>
                            @endif
                        </p>
                    </div>
                </div>
            </section>

            <section class="section">
                <div class="recommendation">
                    <h3>Roadmap Recommendation</h3>

                    <p>
                        {{ $results['recommendation'] }}
                    </p>
                </div>

                <div class="risk-recommendation">
                    <h3>Risk Recommendation</h3>

                    <p>
                        {{ $results[
                            'risk_recommendation'
                        ] }}
                    </p>
                </div>
            </section>
        @endif
    </main>
</body>
</html>
