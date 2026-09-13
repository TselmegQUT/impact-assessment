<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Assessment Report - {{ $project->project_code }}
    </title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            color: #1f2937;
            background: #eef4f1;
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
            margin: 35px auto;
        }

        .report-header,
        .section {
            margin-bottom: 22px;
            padding: 30px;
            border-radius: 12px;
            background: white;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.07);
        }

        .report-top {
            display: flex;
            justify-content: space-between;
            gap: 20px;
        }

        h1 {
            margin: 8px 0;
        }

        h2 {
            margin-top: 0;
            color: #14532d;
        }

        h3 {
            margin-top: 0;
        }

        .report-number {
            color: #15803d;
            font-weight: bold;
        }

        .status {
            display: inline-block;
            height: fit-content;
            padding: 8px 14px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-draft {
            color: #475569;
            background: #e2e8f0;
        }

        .status-submitted {
            color: #1d4ed8;
            background: #dbeafe;
        }

        .status-approved {
            color: #166534;
            background: #dcfce7;
        }

        .status-rejected {
            color: #991b1b;
            background: #fee2e2;
        }

        .message {
            margin-bottom: 20px;
            padding: 15px;
            border-radius: 7px;
        }

        .success {
            color: #166534;
            background: #dcfce7;
        }

        .error {
            color: #991b1b;
            background: #fee2e2;
        }

        .information-grid,
        .result-grid,
        .risk-grid {
            display: grid;
            grid-template-columns: repeat(
                2,
                minmax(0, 1fr)
            );
            gap: 16px;
        }

        .information-item,
        .result-card,
        .risk-item {
            padding: 18px;
            border-radius: 8px;
            background: #f8fafc;
        }

        .label {
            display: block;
            margin-bottom: 7px;
            color: #64748b;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .value {
            color: #14532d;
            font-size: 25px;
            font-weight: bold;
        }

        .description {
            margin-bottom: 0;
            color: #64748b;
            font-size: 14px;
        }

        .text-box {
            padding: 20px;
            line-height: 1.7;
            border-left: 5px solid #15803d;
            border-radius: 7px;
            background: #f0fdf4;
        }

        .recommendation {
            padding: 20px;
            line-height: 1.7;
            border-left: 5px solid #2563eb;
            border-radius: 7px;
            background: #eff6ff;
        }

        .review-box {
            padding: 20px;
            border-left: 5px solid #9333ea;
            border-radius: 7px;
            background: #faf5ff;
        }

        .actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 20px;
        }

        .button,
        button {
            display: inline-block;
            padding: 11px 18px;
            border: 0;
            border-radius: 6px;
            color: white;
            background: #15803d;
            font-size: 14px;
            cursor: pointer;
            text-decoration: none;
        }

        .button-secondary {
            background: #475569;
        }

        .button-blue {
            background: #2563eb;
        }

        .button-red {
            background: #dc2626;
        }

        textarea {
            width: 100%;
            min-height: 110px;
            margin: 10px 0;
            padding: 12px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            font: inherit;
        }

        .manager-actions {
            display: grid;
            grid-template-columns: repeat(
                2,
                minmax(0, 1fr)
            );
            gap: 20px;
        }

        .approval-form,
        .rejection-form {
            padding: 20px;
            border-radius: 8px;
            background: #f8fafc;
        }

        .approved-banner {
            padding: 20px;
            color: #166534;
            border: 2px solid #22c55e;
            border-radius: 8px;
            background: #f0fdf4;
        }

        .rejected-banner {
            padding: 20px;
            color: #991b1b;
            border: 2px solid #ef4444;
            border-radius: 8px;
            background: #fef2f2;
        }

        @media (max-width: 750px) {
            header {
                padding: 18px 20px;
            }

            main {
                margin: 20px;
            }

            .report-top {
                flex-direction: column;
            }

            .information-grid,
            .result-grid,
            .risk-grid,
            .manager-actions {
                grid-template-columns: 1fr;
            }
        }

        @media print {
            body {
                background: white;
            }

            header,
            .no-print {
                display: none !important;
            }

            main {
                max-width: none;
                margin: 0;
            }

            .report-header,
            .section {
                box-shadow: none;
                border: 1px solid #d1d5db;
                break-inside: avoid;
            }
        }
    </style>
</head>

<body>
    <header>
        <strong>Impact Assessment System</strong>

        <a href="{{ route('projects.show', $project) }}">
            ← Return to Project
        </a>
    </header>

    <main>
        @if(session('success'))
            <div class="message success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="message error">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="message error">
                <strong>Please correct the following:</strong>

                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <section class="report-header">
            <div class="report-top">
                <div>
                    <span class="report-number">
                        {{ $report->report_number }}
                    </span>

                    <h1>Final Impact Assessment Report</h1>

                    <p>
                        {{ $project->title }}
                        ({{ $project->project_code }})
                    </p>
                </div>

                <span
                    class="status
                    status-{{ $report->status }}"
                >
                    {{ $report->status }}
                </span>
            </div>

            <div class="actions no-print">
                <a
                    class="button button-secondary"
                    href="{{ route(
                        'projects.show',
                        $project
                    ) }}"
                >
                    Return to Project
                </a>

                <button
                    type="button"
                    class="button-blue"
                    onclick="window.print()"
                >
                    Print Report
                </button>
            </div>
        </section>

        <section class="section">
            <h2>1. Project Information</h2>

            <div class="information-grid">
                <div class="information-item">
                    <span class="label">
                        Project title
                    </span>

                    {{ $project->title }}
                </div>

                <div class="information-item">
                    <span class="label">
                        Project code
                    </span>

                    {{ $project->project_code }}
                </div>

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

                    {{ $project->sector
                        ?? 'Not specified' }}
                </div>

                <div class="information-item">
                    <span class="label">
                        Project status
                    </span>

                    {{ ucwords(str_replace(
                        '_',
                        ' ',
                        $project->status
                    )) }}
                </div>

                <div class="information-item">
                    <span class="label">
                        Start date
                    </span>

                    {{ $project->start_date
                        ->format('d M Y') }}
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
            </div>
        </section>

        <section class="section">
            <h2>2. Executive Summary</h2>

            <div class="text-box">
                {{ $report->executive_summary }}
            </div>
        </section>

        <section class="section">
            <h2>3. Performance Results</h2>

            <div class="result-grid">
                <div class="result-card">
                    <span class="label">
                        Success Ratio
                    </span>

                    <div class="value">
                        {{ number_format(
                            $results[
                                'success_ratio_percent'
                            ] ?? 0,
                            2
                        ) }}%
                    </div>

                    <p class="description">
                        Project accomplishments compared
                        with target objectives.
                    </p>
                </div>

                <div class="result-card">
                    <span class="label">
                        Budget Efficiency
                    </span>

                    <div class="value">
                        @if(
                            isset(
                                $results[
                                    'budget_efficiency_percent'
                                ]
                            )
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

                    <p class="description">
                        Approved budget compared with
                        actual expenditure.
                    </p>
                </div>

                <div class="result-card">
                    <span class="label">
                        Performance Grade
                    </span>

                    <div class="value">
                        @if(
                            isset(
                                $results[
                                    'performance_grade'
                                ]
                            )
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

                    <p class="description">
                        {{ $results['technical_rating']
                            ?? 'Not available' }}
                    </p>
                </div>

                <div class="result-card">
                    <span class="label">
                        Relevance Score
                    </span>

                    <div class="value">
                        {{ number_format(
                            $results['relevance_score'] ?? 0,
                            2
                        ) }}
                    </div>

                    <p class="description">
                        Weighted 6P outputs and alignment.
                    </p>
                </div>

                <div class="result-card">
                    <span class="label">
                        Immediate Monetary Value
                    </span>

                    <div class="value">
                        ₱{{ number_format(
                            $results[
                                'immediate_monetary_value'
                            ] ?? 0,
                            2
                        ) }}
                    </div>

                    <p class="description">
                        Estimated value of project outputs.
                    </p>
                </div>

                <div class="result-card">
                    <span class="label">
                        Impact Readiness Score
                    </span>

                    <div class="value">
                        {{ isset(
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
                            : 'N/A' }}
                    </div>

                    <p class="description">
                        Project readiness adjusted for time.
                    </p>
                </div>

                <div class="result-card">
                    <span class="label">
                        Maturation Priority Score
                    </span>

                    <div class="value">
                        {{ isset(
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
                            : 'N/A' }}
                    </div>

                    <p class="description">
                        {{ $results['impact_track']
                            ?? 'Not assigned' }}
                    </p>
                </div>

                <div class="result-card">
                    <span class="label">
                        Time Maturation Index
                    </span>

                    <div class="value">
                        {{ $results[
                            'time_maturation_index'
                        ] ?? 'N/A' }}
                    </div>

                    <p class="description">
                        Months elapsed:
                        {{ $results['months_elapsed']
                            ?? 'N/A' }}
                    </p>
                </div>
            </div>
        </section>

        <section class="section">
            <h2>4. Risk Assessment</h2>

            <div class="risk-grid">
                <div class="risk-item">
                    <span class="label">
                        Operational / Technical
                    </span>

                    {{ $project->risk
                        ->operational_technical_count }}
                </div>

                <div class="risk-item">
                    <span class="label">
                        Institutional
                    </span>

                    {{ $project->risk
                        ->institutional_count }}
                </div>

                <div class="risk-item">
                    <span class="label">
                        Financial
                    </span>

                    {{ $project->risk
                        ->financial_count }}
                </div>

                <div class="risk-item">
                    <span class="label">
                        Institutional + Financial
                    </span>

                    {{ $project->risk
                        ->institutional_financial_count }}
                </div>

                <div class="result-card">
                    <span class="label">
                        Risk Intensity Score
                    </span>

                    <div class="value">
                        {{ isset(
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
                            : 'N/A' }}
                    </div>
                </div>

                <div class="result-card">
                    <span class="label">
                        Risk Level
                    </span>

                    <div class="value">
                        {{ $results['risk_level']
                            ?? 'Not assessed' }}
                    </div>
                </div>
            </div>

            @if($project->risk->risk_notes)
                <div class="text-box" style="margin-top: 18px;">
                    <strong>Risk notes:</strong>

                    <p>
                        {{ $project->risk->risk_notes }}
                    </p>
                </div>
            @endif
        </section>

        <section class="section">
            <h2>5. Assessment Findings</h2>

            <div class="text-box">
                {{ $report->findings }}
            </div>
        </section>

        <section class="section">
            <h2>6. Final Recommendation</h2>

            <div class="recommendation">
                {{ $report->final_recommendation }}
            </div>
        </section>

        <section class="section">
            <h2>7. Report Information</h2>

            <div class="information-grid">
                <div class="information-item">
                    <span class="label">
                        Generated by
                    </span>

                    {{ $report->generatedBy?->name
                        ?? 'Unknown' }}
                </div>

                <div class="information-item">
                    <span class="label">
                        Generated date
                    </span>

                    {{ $report->generated_at
                        ? $report->generated_at
                            ->format('d M Y, h:i A')
                        : 'Not available' }}
                </div>

                <div class="information-item">
                    <span class="label">
                        Reviewed by
                    </span>

                    {{ $report->reviewedBy?->name
                        ?? 'Not reviewed' }}
                </div>

                <div class="information-item">
                    <span class="label">
                        Reviewed date
                    </span>

                    {{ $report->reviewed_at
                        ? $report->reviewed_at
                            ->format('d M Y, h:i A')
                        : 'Not reviewed' }}
                </div>
            </div>
        </section>

        @if($report->review_notes)
            <section class="section">
                <h2>Manager Review Notes</h2>

                <div class="review-box">
                    {{ $report->review_notes }}
                </div>
            </section>
        @endif

        @if($report->status === 'approved')
            <section class="section">
                <div class="approved-banner">
                    <h3>Report Approved</h3>

                    <p>
                        This final assessment report was
                        approved by
                        {{ $report->reviewedBy?->name
                            ?? 'the Manager' }}.
                    </p>
                </div>
            </section>
        @endif

        @if($report->status === 'rejected')
            <section class="section">
                <div class="rejected-banner">
                    <h3>Report Returned for Revision</h3>

                    <p>
                        Review the Manager's comments,
                        correct the project information and
                        generate the report again.
                    </p>
                </div>
            </section>
        @endif

        @if(
            in_array(
                $report->status,
                ['draft', 'rejected']
            ) &&
            (
                auth()->user()->hasRole('admin') ||
                auth()->user()->hasRole('project_officer') ||
                auth()->user()->hasRole('analyst')
            )
        )
            <section class="section no-print">
                <h2>Submit Report</h2>

                <p>
                    After checking all information, submit
                    this report to the Manager for review.
                </p>

                <form
                    method="POST"
                    action="{{ route(
                        'projects.reports.submit',
                        $project
                    ) }}"
                >
                    @csrf
                    @method('PATCH')

                    <button type="submit">
                        Submit to Manager
                    </button>
                </form>
            </section>
        @endif

        @if(
            $report->status === 'submitted' &&
            auth()->user()->hasRole('manager')
        )
            <section class="section no-print">
                <h2>Manager Review</h2>

                <div class="manager-actions">
                    <form
                        class="approval-form"
                        method="POST"
                        action="{{ route(
                            'projects.reports.approve',
                            $project
                        ) }}"
                    >
                        @csrf
                        @method('PATCH')

                        <h3>Approve Report</h3>

                        <label for="approval_notes">
                            Review notes (optional)
                        </label>

                        <textarea
                            id="approval_notes"
                            name="review_notes"
                            placeholder="Enter optional approval notes"
                        ></textarea>

                        <button type="submit">
                            Approve Report
                        </button>
                    </form>

                    <form
                        class="rejection-form"
                        method="POST"
                        action="{{ route(
                            'projects.reports.reject',
                            $project
                        ) }}"
                    >
                        @csrf
                        @method('PATCH')

                        <h3>Reject Report</h3>

                        <label for="rejection_notes">
                            Reason for rejection
                        </label>

                        <textarea
                            id="rejection_notes"
                            name="review_notes"
                            required
                            placeholder="Explain what must be corrected"
                        ></textarea>

                        <button
                            type="submit"
                            class="button-red"
                        >
                            Reject Report
                        </button>
                    </form>
                </div>
            </section>
        @endif
    </main>
</body>
</html>
