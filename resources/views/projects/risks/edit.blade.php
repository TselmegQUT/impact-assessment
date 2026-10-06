@extends('layouts.app')

@section('title', 'Risk Assessment')

@section('page-title', 'Project Risk Assessment')

@section('content')

    @php
        $riskCategories = [
            [
                'title' => 'Operational or Technical',
                'description' =>
                    'Equipment delays, technical problems, system issues or staff turnover.',
                'weight' => '1.2',
                'name' =>
                    'operational_technical_count',
                'label' =>
                    'Number of Operational or Technical Problems',
            ],
            [
                'title' => 'Institutional Risk',
                'description' =>
                    'Partner problems, low institutional adoption or leadership changes.',
                'weight' => '1.5',
                'name' => 'institutional_count',
                'label' =>
                    'Number of Institutional Problems',
            ],
            [
                'title' => 'Financial Risk',
                'description' =>
                    'Budget cuts, delayed funding or high production and operating costs.',
                'weight' => '1.8',
                'name' => 'financial_count',
                'label' =>
                    'Number of Financial Problems',
            ],
            [
                'title' =>
                    'Institutional and Financial',
                'description' =>
                    'Combined problems involving both institutional support and project funding.',
                'weight' => '2.0',
                'name' =>
                    'institutional_financial_count',
                'label' =>
                    'Number of Combined Problems',
            ],
        ];
    @endphp

    <section class="page-header">
        <div class="page-header-copy">
            <h2>Record Project Risks</h2>

            <p>
                Enter the number of problems encountered by
                <strong>{{ $project->title }}</strong>. Record
                each problem in only one category to prevent
                duplicate risk calculations.
            </p>
        </div>

        <div class="page-actions">
            <a
                href="{{ route('projects.show', $project) }}"
                class="button button-secondary"
            >
                ← Back to Project
            </a>
        </div>
    </section>

    {{-- Project information --}}
    <section
        class="panel"
        style="margin-bottom: 22px;"
    >
        <div class="panel-body">
            <div class="page-actions">
                <span class="status-badge status-neutral">
                    {{ $project->project_code }}
                </span>

                <span class="status-badge status-success">
                    {{ $project->title }}
                </span>
            </div>
        </div>
    </section>

    <form
        method="POST"
        action="{{
            route(
                'projects.risks.update',
                $project
            )
        }}"
    >
        @csrf
        @method('PUT')

        {{-- Risk categories --}}
        <section
            class="panel"
            style="margin-bottom: 22px;"
        >
            <div class="panel-header">
                <div>
                    <h2>Risk Categories</h2>
                </div>

                <span class="status-badge status-warning">
                    Weighted Calculation
                </span>
            </div>

            <div class="panel-body">
                <div class="form-grid">
                    @foreach(
                        $riskCategories as $category
                    )
                        <article class="panel">
                            <div class="panel-header">
                                <h3>
                                    {{ $category['title'] }}
                                </h3>

                                <span
                                    class="status-badge
                                        status-warning"
                                >
                                    Weight:
                                    {{ $category['weight'] }}
                                </span>
                            </div>

                            <div class="panel-body">
                                <p
                                    style="
                                        min-height: 48px;
                                        margin-top: 0;
                                        color:
                                            var(--text-secondary);
                                        line-height: 1.6;
                                    "
                                >
                                    {{
                                        $category[
                                            'description'
                                        ]
                                    }}
                                </p>

                                <div class="form-group">
                                    <label
                                        for="{{
                                            $category['name']
                                        }}"
                                        class="form-label"
                                    >
                                        {{
                                            $category['label']
                                        }}
                                    </label>

                                    <input
                                        id="{{
                                            $category['name']
                                        }}"
                                        name="{{
                                            $category['name']
                                        }}"
                                        type="number"
                                        class="form-control"
                                        min="0"
                                        step="1"
                                        inputmode="numeric"
                                        value="{{
                                            old(
                                                $category[
                                                    'name'
                                                ],
                                                data_get(
                                                    $risk,
                                                    $category[
                                                        'name'
                                                    ],
                                                    0
                                                )
                                            )
                                        }}"
                                        required
                                    >

                                    <span class="form-help">
                                        Enter 0 when no problem
                                        was identified.
                                    </span>

                                    @error($category['name'])
                                        <span class="form-error">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- Risk notes --}}
        <section
            class="panel"
            style="margin-bottom: 22px;"
        >
            <div class="panel-header">
                <div>
                    <h2>Risk Evidence and Notes</h2>
                </div>

                <span class="status-badge status-neutral">
                    Supporting Information
                </span>
            </div>

            <div class="panel-body">
                <div class="form-group">
                    <label
                        for="risk_notes"
                        class="form-label"
                    >
                        Risk Notes
                    </label>

                    <textarea
                        id="risk_notes"
                        name="risk_notes"
                        class="form-control"
                        placeholder="Describe the identified problems, their causes, effects and any action already taken."
                    >{{ old(
                        'risk_notes',
                        $risk?->risk_notes
                    ) }}</textarea>

                    <span class="form-help">
                        Include enough evidence to support the
                        selected risk categories.
                    </span>

                    @error('risk_notes')
                        <span class="form-error">
                            {{ $message }}
                        </span>
                    @enderror
                </div>
            </div>
        </section>

        {{-- Risk formula explanation --}}
        <section
            class="panel"
            style="margin-bottom: 22px;"
        >
            <div class="panel-header">
                <h2>How the Risk Score Works</h2>
            </div>

            <div class="panel-body">
                <p
                    style="
                        margin-top: 0;
                        color: var(--text-secondary);
                        line-height: 1.7;
                    "
                >
                    The system multiplies the number of problems
                    in each category by its assigned weight. The
                    weighted values are added together to produce
                    the Risk Intensity Score.
                </p>

                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Risk Category</th>
                                <th>Weight</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach(
                                $riskCategories as $category
                            )
                                <tr>
                                    <td>
                                        {{
                                            $category[
                                                'title'
                                            ]
                                        }}
                                    </td>

                                    <td>
                                        <span
                                            class="status-badge
                                                status-neutral"
                                        >
                                            {{
                                                $category[
                                                    'weight'
                                                ]
                                            }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        {{-- Document extraction notice --}}
        <section
            class="panel"
            style="margin-bottom: 22px;"
        >
            <div class="panel-body">
                <strong>
                    Future document extraction
                </strong>

                <p
                    style="
                        margin: 8px 0 0;
                        color: var(--text-secondary);
                        line-height: 1.6;
                    "
                >
                    When the Data Ingestion module is added, the
                    system will identify possible risks from PDF
                    and DOCX documents. An analyst must review and
                    confirm the extracted risks before saving.
                </p>
            </div>
        </section>

        {{-- Form actions --}}
        <section class="panel">
            <div class="panel-body">
                <div class="page-actions">
                    <button
                        type="submit"
                        class="button button-primary"
                    >
                        Save Risk Assessment
                    </button>

                    <a
                        href="{{
                            route(
                                'projects.show',
                                $project
                            )
                        }}"
                        class="button button-secondary"
                    >
                        Cancel
                    </a>
                </div>
            </div>
        </section>
    </form>

@endsection
