@extends('layouts.app')

@section('title', '6P Outputs')

@section('page-title', '6P Output Assessment')

@section('content')

    @php
        $outputTypes = [
            [
                'title' => 'Policy',
                'description' =>
                    'Policies, standards or regulatory outputs.',
                'weight' => '1.0',
                'count_name' => 'policy_count',
                'count_label' => 'Number of Policies',
                'value_name' => 'policy_unit_value',
                'value_label' => 'Value per Policy (₱)',
                'default_value' => 750000,
            ],
            [
                'title' => 'Patent',
                'description' =>
                    'Registered patents and intellectual property.',
                'weight' => '0.9',
                'count_name' => 'patent_count',
                'count_label' => 'Number of Patents',
                'value_name' => 'patent_unit_value',
                'value_label' => 'Value per Patent (₱)',
                'default_value' => 250000,
            ],
            [
                'title' => 'Product',
                'description' =>
                    'Products, technologies and innovations.',
                'weight' => '0.8',
                'count_name' => 'product_count',
                'count_label' => 'Number of Products',
                'value_name' => 'product_unit_value',
                'value_label' => 'Market Value per Product (₱)',
                'default_value' => 0,
            ],
            [
                'title' => 'Partnership',
                'description' =>
                    'Institutional and industry partnerships.',
                'weight' => '0.6',
                'count_name' => 'partnership_count',
                'count_label' => 'Number of Partnerships',
                'value_name' => 'partnership_total_value',
                'value_label' => 'Total Counterpart Value (₱)',
                'default_value' => 0,
            ],
            [
                'title' => 'People Services',
                'description' =>
                    'People trained, assisted or served.',
                'weight' => '0.4',
                'count_name' => 'people_services_count',
                'count_label' =>
                    'Number of People Served or Trained',
                'value_name' =>
                    'people_service_unit_value',
                'value_label' => 'Value per Person (₱)',
                'default_value' => 15000,
            ],
            [
                'title' => 'Publication',
                'description' =>
                    'Published research and knowledge outputs.',
                'weight' => '0.2',
                'count_name' => 'publication_count',
                'count_label' => 'Number of Publications',
                'value_name' =>
                    'publication_unit_value',
                'value_label' =>
                    'Value per Publication (₱)',
                'default_value' => 50000,
            ],
        ];
    @endphp

    <section class="page-header">
        <div class="page-header-copy">
            <h2>Enter 6P Project Outputs</h2>

            <p>
                Enter the quantities and monetary values produced
                by <strong>{{ $project->title }}</strong>. These
                values are used to calculate relevance, financial
                value and impact readiness.
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
                'projects.outputs.update',
                $project
            )
        }}"
    >
        @csrf
        @method('PUT')

        <section
            class="panel"
            style="margin-bottom: 22px;"
        >
            <div class="panel-header">
                <div>
                    <h2>Output Quantities and Values</h2>
                </div>

                <span class="status-badge status-warning">
                    Six Output Categories
                </span>
            </div>

            <div class="panel-body">
                <div class="form-grid">
                    @foreach($outputTypes as $type)
                        <article class="panel">
                            <div class="panel-header">
                                <div>
                                    <h3>{{ $type['title'] }}</h3>
                                </div>

                                <span
                                    class="status-badge
                                        status-neutral"
                                >
                                    Weight:
                                    {{ $type['weight'] }}
                                </span>
                            </div>

                            <div class="panel-body">
                                <p
                                    style="
                                        margin-top: 0;
                                        color:
                                            var(--text-secondary);
                                        line-height: 1.5;
                                    "
                                >
                                    {{ $type['description'] }}
                                </p>

                                <div class="form-group">
                                    <label
                                        for="{{
                                            $type[
                                                'count_name'
                                            ]
                                        }}"
                                        class="form-label"
                                    >
                                        {{
                                            $type[
                                                'count_label'
                                            ]
                                        }}
                                    </label>

                                    <input
                                        id="{{
                                            $type[
                                                'count_name'
                                            ]
                                        }}"
                                        name="{{
                                            $type[
                                                'count_name'
                                            ]
                                        }}"
                                        type="number"
                                        class="form-control"
                                        min="0"
                                        step="1"
                                        inputmode="numeric"
                                        value="{{
                                            old(
                                                $type[
                                                    'count_name'
                                                ],
                                                data_get(
                                                    $output,
                                                    $type[
                                                        'count_name'
                                                    ],
                                                    0
                                                )
                                            )
                                        }}"
                                        required
                                    >

                                    @error(
                                        $type['count_name']
                                    )
                                        <span class="form-error">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>

                                <div
                                    class="form-group"
                                    style="margin-top: 16px;"
                                >
                                    <label
                                        for="{{
                                            $type[
                                                'value_name'
                                            ]
                                        }}"
                                        class="form-label"
                                    >
                                        {{
                                            $type[
                                                'value_label'
                                            ]
                                        }}
                                    </label>

                                    <input
                                        id="{{
                                            $type[
                                                'value_name'
                                            ]
                                        }}"
                                        name="{{
                                            $type[
                                                'value_name'
                                            ]
                                        }}"
                                        type="number"
                                        class="form-control"
                                        min="0"
                                        step="0.01"
                                        inputmode="decimal"
                                        value="{{
                                            old(
                                                $type[
                                                    'value_name'
                                                ],
                                                data_get(
                                                    $output,
                                                    $type[
                                                        'value_name'
                                                    ],
                                                    $type[
                                                        'default_value'
                                                    ]
                                                )
                                            )
                                        }}"
                                        required
                                    >

                                    @error(
                                        $type['value_name']
                                    )
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

        {{-- Strategic alignment --}}
        <section
            class="panel"
            style="margin-bottom: 22px;"
        >
            <div class="panel-header">
                <div>
                    <h2>Strategic Alignment</h2>
                </div>

                <span class="status-badge status-neutral">
                    Required
                </span>
            </div>

            <div class="panel-body">
                <div class="form-grid">
                    <div class="form-group">
                        <label
                            for="alignment_level"
                            class="form-label"
                        >
                            Alignment Level *
                        </label>

                        <select
                            id="alignment_level"
                            name="alignment_level"
                            class="form-control"
                            required
                        >
                            <option value="">
                                Select an alignment level
                            </option>

                            <option
                                value="pioneering"
                                @selected(
                                    old(
                                        'alignment_level',
                                        $output
                                            ?->alignment_level
                                    ) === 'pioneering'
                                )
                            >
                                Pioneering or Critical — 1.0
                            </option>

                            <option
                                value="high"
                                @selected(
                                    old(
                                        'alignment_level',
                                        $output
                                            ?->alignment_level
                                    ) === 'high'
                                )
                            >
                                High Strategic Fit — 0.8
                            </option>

                            <option
                                value="supporting"
                                @selected(
                                    old(
                                        'alignment_level',
                                        $output
                                            ?->alignment_level
                                    ) === 'supporting'
                                )
                            >
                                Supporting — 0.5
                            </option>

                            <option
                                value="niche"
                                @selected(
                                    old(
                                        'alignment_level',
                                        $output
                                            ?->alignment_level
                                    ) === 'niche'
                                )
                            >
                                Niche or Indirect — 0.2
                            </option>

                            <option
                                value="minimal"
                                @selected(
                                    old(
                                        'alignment_level',
                                        $output
                                            ?->alignment_level
                                    ) === 'minimal'
                                )
                            >
                                Minimal — 0.1
                            </option>
                        </select>

                        <span class="form-help">
                            Select the level that best represents
                            the project’s strategic contribution.
                        </span>

                        @error('alignment_level')
                            <span class="form-error">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label
                            for="alignment_justification"
                            class="form-label"
                        >
                            Alignment Justification *
                        </label>

                        <textarea
                            id="alignment_justification"
                            name="alignment_justification"
                            class="form-control"
                            placeholder="Explain why this alignment level was selected."
                            required
                        >{{ old(
                            'alignment_justification',
                            $output?->alignment_justification
                        ) }}</textarea>

                        <span class="form-help">
                            Provide evidence or a short explanation.
                        </span>

                        @error('alignment_justification')
                            <span class="form-error">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                </div>
            </div>
        </section>

        {{-- Future extraction notice --}}
        <section
            class="panel"
            style="margin-bottom: 22px;"
        >
            <div class="panel-body">
                <strong>
                    Document extraction preparation
                </strong>

                <p
                    style="
                        margin: 8px 0 0;
                        color: var(--text-secondary);
                        line-height: 1.6;
                    "
                >
                    In the next development stage, values extracted
                    from uploaded PDF and DOCX documents will be
                    displayed in these fields for review. A user
                    must confirm the values before saving them.
                </p>
            </div>
        </section>

        <section class="panel">
            <div class="panel-body">
                <div class="page-actions">
                    <button
                        type="submit"
                        class="button button-primary"
                    >
                        Save 6P Outputs
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
