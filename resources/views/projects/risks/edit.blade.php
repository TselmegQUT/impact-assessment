<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Risk Assessment | {{ $project->title }}</title>

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
            max-width: 950px;
            margin: 40px auto;
            padding: 35px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        }

        h1 {
            margin-top: 5px;
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

        .risk-grid {
            display: grid;
            grid-template-columns: repeat(
                2,
                minmax(0, 1fr)
            );
            gap: 18px;
            margin-top: 25px;
        }

        .risk-card {
            padding: 22px;
            border: 1px solid #e2e8f0;
            border-radius: 9px;
            background: #f8fafc;
        }

        .risk-card h2 {
            margin: 0 0 6px;
            color: #14532d;
            font-size: 20px;
        }

        .weight {
            display: inline-block;
            padding: 4px 9px;
            border-radius: 15px;
            color: #92400e;
            background: #fef3c7;
            font-size: 13px;
            font-weight: bold;
        }

        .description {
            min-height: 58px;
            color: #64748b;
            font-size: 14px;
        }

        label {
            display: block;
            margin: 15px 0 7px;
            font-weight: bold;
        }

        input,
        textarea {
            width: 100%;
            padding: 11px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            font-family: Arial, sans-serif;
            font-size: 15px;
        }

        textarea {
            min-height: 130px;
            resize: vertical;
        }

        .notes {
            margin-top: 25px;
            padding: 22px;
            border-radius: 9px;
            background: #f8fafc;
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
            .risk-grid {
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

        <h1>Risk Assessment</h1>

        <p>
            Enter the number of problems encountered in each
            category. Record every problem in only one category.
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
                'projects.risks.update',
                $project
            ) }}"
        >
            @csrf
            @method('PUT')

            <div class="risk-grid">
                <section class="risk-card">
                    <h2>Operational or Technical</h2>

                    <span class="weight">
                        Weight: 1.2
                    </span>

                    <p class="description">
                        Equipment delays, technical problems,
                        system issues or staff turnover.
                    </p>

                    <label for="operational_technical_count">
                        Number of problems
                    </label>

                    <input
                        id="operational_technical_count"
                        name="operational_technical_count"
                        type="number"
                        min="0"
                        value="{{ old(
                            'operational_technical_count',
                            $risk?->operational_technical_count ?? 0
                        ) }}"
                        required
                    >
                </section>

                <section class="risk-card">
                    <h2>Institutional Risk</h2>

                    <span class="weight">
                        Weight: 1.5
                    </span>

                    <p class="description">
                        Partner problems, low institutional
                        adoption or leadership changes.
                    </p>

                    <label for="institutional_count">
                        Number of problems
                    </label>

                    <input
                        id="institutional_count"
                        name="institutional_count"
                        type="number"
                        min="0"
                        value="{{ old(
                            'institutional_count',
                            $risk?->institutional_count ?? 0
                        ) }}"
                        required
                    >
                </section>

                <section class="risk-card">
                    <h2>Financial Risk</h2>

                    <span class="weight">
                        Weight: 1.8
                    </span>

                    <p class="description">
                        Budget cuts, delayed funding or high
                        production and operating costs.
                    </p>

                    <label for="financial_count">
                        Number of problems
                    </label>

                    <input
                        id="financial_count"
                        name="financial_count"
                        type="number"
                        min="0"
                        value="{{ old(
                            'financial_count',
                            $risk?->financial_count ?? 0
                        ) }}"
                        required
                    >
                </section>

                <section class="risk-card">
                    <h2>Institutional and Financial</h2>

                    <span class="weight">
                        Weight: 2.0
                    </span>

                    <p class="description">
                        A combined problem involving both
                        institutional support and funding.
                    </p>

                    <label for="institutional_financial_count">
                        Number of combined problems
                    </label>

                    <input
                        id="institutional_financial_count"
                        name="institutional_financial_count"
                        type="number"
                        min="0"
                        value="{{ old(
                            'institutional_financial_count',
                            $risk?->institutional_financial_count ?? 0
                        ) }}"
                        required
                    >
                </section>
            </div>

            <section class="notes">
                <label for="risk_notes">
                    Risk notes
                </label>

                <textarea
                    id="risk_notes"
                    name="risk_notes"
                    placeholder="Describe the problems, their causes and any action already taken."
                >{{ old(
                    'risk_notes',
                    $risk?->risk_notes
                ) }}</textarea>
            </section>

            <div class="buttons">
                <button type="submit">
                    Save Risk Assessment
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
