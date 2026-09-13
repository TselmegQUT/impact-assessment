<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>6P Outputs | {{ $project->title }}</title>

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
            max-width: 1000px;
            margin: 40px auto;
            padding: 35px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        }

        h1 {
            margin-top: 0;
        }

        .project-code {
            color: #15803d;
            font-weight: bold;
        }

        .errors {
            margin: 20px 0;
            padding: 14px;
            color: #991b1b;
            background: #fee2e2;
            border-radius: 6px;
        }

        .errors ul {
            margin: 0;
            padding-left: 20px;
        }

        .output-grid {
            display: grid;
            grid-template-columns: repeat(
                2,
                minmax(0, 1fr)
            );
            gap: 18px;
            margin-top: 25px;
        }

        .output-card {
            padding: 22px;
            border: 1px solid #e2e8f0;
            border-radius: 9px;
            background: #f8fafc;
        }

        .output-card h2 {
            margin: 0 0 5px;
            color: #14532d;
            font-size: 20px;
        }

        .weight {
            margin: 0 0 16px;
            color: #64748b;
            font-size: 13px;
        }

        label {
            display: block;
            margin: 14px 0 7px;
            font-weight: bold;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 11px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            font-family: Arial, sans-serif;
            font-size: 15px;
        }

        textarea {
            min-height: 110px;
            resize: vertical;
        }

        .alignment {
            margin-top: 25px;
            padding: 24px;
            border-radius: 9px;
            background: #f8fafc;
        }

        .alignment h2 {
            margin-top: 0;
        }

        .buttons {
            display: flex;
            gap: 12px;
            margin-top: 28px;
        }

        button,
        .cancel {
            padding: 12px 20px;
            border: none;
            border-radius: 6px;
            font-size: 15px;
            text-decoration: none;
            cursor: pointer;
        }

        button {
            color: white;
            background: #15803d;
        }

        button:hover {
            background: #166534;
        }

        .cancel {
            color: #334155;
            background: #e2e8f0;
        }

        @media (max-width: 750px) {
            .output-grid {
                grid-template-columns: 1fr;
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
        <span class="project-code">
            {{ $project->project_code }}
        </span>

        <h1>Enter 6P Outputs</h1>

        <p>
            Enter the quantities and monetary values produced by
            {{ $project->title }}.
        </p>

        @if($errors->any())
            <div class="errors">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form
            method="POST"
            action="{{ route(
                'projects.outputs.update',
                $project
            ) }}"
        >
            @csrf
            @method('PUT')

            <div class="output-grid">
                <section class="output-card">
                    <h2>Policy</h2>
                    <p class="weight">RCS weight: 1.0</p>

                    <label for="policy_count">
                        Number of policies
                    </label>

                    <input
                        id="policy_count"
                        name="policy_count"
                        type="number"
                        min="0"
                        value="{{ old(
                            'policy_count',
                            $output?->policy_count ?? 0
                        ) }}"
                        required
                    >

                    <label for="policy_unit_value">
                        Value per policy (₱)
                    </label>

                    <input
                        id="policy_unit_value"
                        name="policy_unit_value"
                        type="number"
                        min="0"
                        step="0.01"
                        value="{{ old(
                            'policy_unit_value',
                            $output?->policy_unit_value ?? 750000
                        ) }}"
                        required
                    >
                </section>

                <section class="output-card">
                    <h2>Patent</h2>
                    <p class="weight">RCS weight: 0.9</p>

                    <label for="patent_count">
                        Number of patents
                    </label>

                    <input
                        id="patent_count"
                        name="patent_count"
                        type="number"
                        min="0"
                        value="{{ old(
                            'patent_count',
                            $output?->patent_count ?? 0
                        ) }}"
                        required
                    >

                    <label for="patent_unit_value">
                        Value per patent (₱)
                    </label>

                    <input
                        id="patent_unit_value"
                        name="patent_unit_value"
                        type="number"
                        min="0"
                        step="0.01"
                        value="{{ old(
                            'patent_unit_value',
                            $output?->patent_unit_value ?? 250000
                        ) }}"
                        required
                    >
                </section>

                <section class="output-card">
                    <h2>Product</h2>
                    <p class="weight">RCS weight: 0.8</p>

                    <label for="product_count">
                        Number of products
                    </label>

                    <input
                        id="product_count"
                        name="product_count"
                        type="number"
                        min="0"
                        value="{{ old(
                            'product_count',
                            $output?->product_count ?? 0
                        ) }}"
                        required
                    >

                    <label for="product_unit_value">
                        Market value per product (₱)
                    </label>

                    <input
                        id="product_unit_value"
                        name="product_unit_value"
                        type="number"
                        min="0"
                        step="0.01"
                        value="{{ old(
                            'product_unit_value',
                            $output?->product_unit_value ?? 0
                        ) }}"
                        required
                    >
                </section>

                <section class="output-card">
                    <h2>People Services</h2>
                    <p class="weight">RCS weight: 0.4</p>

                    <label for="people_services_count">
                        Number of people served or trained
                    </label>

                    <input
                        id="people_services_count"
                        name="people_services_count"
                        type="number"
                        min="0"
                        value="{{ old(
                            'people_services_count',
                            $output?->people_services_count ?? 0
                        ) }}"
                        required
                    >

                    <label for="people_service_unit_value">
                        Value per person (₱)
                    </label>

                    <input
                        id="people_service_unit_value"
                        name="people_service_unit_value"
                        type="number"
                        min="0"
                        step="0.01"
                        value="{{ old(
                            'people_service_unit_value',
                            $output?->people_service_unit_value ?? 15000
                        ) }}"
                        required
                    >
                </section>

                <section class="output-card">
                    <h2>Partnership</h2>
                    <p class="weight">RCS weight: 0.6</p>

                    <label for="partnership_count">
                        Number of partnerships
                    </label>

                    <input
                        id="partnership_count"
                        name="partnership_count"
                        type="number"
                        min="0"
                        value="{{ old(
                            'partnership_count',
                            $output?->partnership_count ?? 0
                        ) }}"
                        required
                    >

                    <label for="partnership_total_value">
                        Total counterpart value (₱)
                    </label>

                    <input
                        id="partnership_total_value"
                        name="partnership_total_value"
                        type="number"
                        min="0"
                        step="0.01"
                        value="{{ old(
                            'partnership_total_value',
                            $output?->partnership_total_value ?? 0
                        ) }}"
                        required
                    >
                </section>

                <section class="output-card">
                    <h2>Publication</h2>
                    <p class="weight">RCS weight: 0.2</p>

                    <label for="publication_count">
                        Number of publications
                    </label>

                    <input
                        id="publication_count"
                        name="publication_count"
                        type="number"
                        min="0"
                        value="{{ old(
                            'publication_count',
                            $output?->publication_count ?? 0
                        ) }}"
                        required
                    >

                    <label for="publication_unit_value">
                        Value per publication (₱)
                    </label>

                    <input
                        id="publication_unit_value"
                        name="publication_unit_value"
                        type="number"
                        min="0"
                        step="0.01"
                        value="{{ old(
                            'publication_unit_value',
                            $output?->publication_unit_value ?? 50000
                        ) }}"
                        required
                    >
                </section>
            </div>

            <section class="alignment">
                <h2>Strategic Alignment</h2>

                <label for="alignment_level">
                    Alignment level
                </label>

                <select
                    id="alignment_level"
                    name="alignment_level"
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
                                $output?->alignment_level
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
                                $output?->alignment_level
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
                                $output?->alignment_level
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
                                $output?->alignment_level
                            ) === 'niche'
                        )
                    >
                        Niche or or Indirect — 0.2
                    </option>

                    <option
                        value="minimal"
                        @selected(
                            old(
                                'alignment_level',
                                $output?->alignment_level
                            ) === 'minimal'
                        )
                    >
                        Minimal — 0.1
                    </option>
                </select>

                <label for="alignment_justification">
                    Alignment justification
                </label>

                <textarea
                    id="alignment_justification"
                    name="alignment_justification"
                    placeholder="Explain why this alignment level was selected."
                    required
                >{{ old(
                    'alignment_justification',
                    $output?->alignment_justification
                ) }}</textarea>
            </section>

            <div class="buttons">
                <button type="submit">
                    Save 6P Outputs
                </button>

                <a
                    class="cancel"
                    href="{{ route('projects.show', $project) }}"
                >
                    Cancel
                </a>
            </div>
        </form>
    </main>
</body>
</html>
