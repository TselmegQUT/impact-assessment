<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Create Project | Impact Assessment System</title>

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
            max-width: 850px;
            margin: 40px auto;
            padding: 35px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        }

        h1 {
            margin-top: 0;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0 20px;
        }

        .full-width {
            grid-column: 1 / -1;
        }

        label {
            display: block;
            margin: 18px 0 7px;
            font-weight: bold;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            font-family: Arial, sans-serif;
            font-size: 16px;
        }

        textarea {
            min-height: 110px;
            resize: vertical;
        }

        .errors {
            margin-bottom: 20px;
            padding: 14px;
            color: #991b1b;
            background: #fee2e2;
            border-radius: 6px;
        }

        .errors ul {
            margin: 0;
            padding-left: 20px;
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

        .cancel {
            color: #334155;
            background: #e2e8f0;
        }

        @media (max-width: 700px) {
            .form-grid {
                grid-template-columns: 1fr;
            }

            .full-width {
                grid-column: auto;
            }
        }
    </style>
</head>

<body>
    <header>
        <strong>Impact Assessment System</strong>

        <a href="{{ route('projects.index') }}">
            Project List
        </a>
    </header>

    <main>
        <h1>Create Project</h1>

        <p>
            Enter the basic project and financial information.
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

        <form method="POST" action="{{ route('projects.store') }}">
            @csrf

            <div class="form-grid">
                <div>
                    <label for="project_code">Project code</label>

                    <input
                        id="project_code"
                        name="project_code"
                        type="text"
                        value="{{ old('project_code') }}"
                        placeholder="Example: DOST-2026-001"
                        required
                    >
                </div>

                <div>
                    <label for="title">Project title</label>

                    <input
                        id="title"
                        name="title"
                        type="text"
                        value="{{ old('title') }}"
                        required
                    >
                </div>

                <div class="full-width">
                    <label for="description">Description</label>

                    <textarea
                        id="description"
                        name="description"
                    >{{ old('description') }}</textarea>
                </div>

                <div>
                    <label for="implementing_agency">
                        Implementing agency
                    </label>

                    <input
                        id="implementing_agency"
                        name="implementing_agency"
                        type="text"
                        value="{{ old('implementing_agency') }}"
                    >
                </div>

                <div>
                    <label for="project_leader">Project leader</label>

                    <input
                        id="project_leader"
                        name="project_leader"
                        type="text"
                        value="{{ old('project_leader') }}"
                    >
                </div>

                <div>
                    <label for="sector">Sector</label>

                    <input
                        id="sector"
                        name="sector"
                        type="text"
                        value="{{ old('sector') }}"
                        placeholder="Example: Agriculture or Health"
                    >
                </div>

                <div>
                    <label for="status">Project status</label>

                    <select id="status" name="status" required>
                        <option
                            value="draft"
                            @selected(old('status') === 'draft')
                        >
                            Draft
                        </option>

                        <option
                            value="in_progress"
                            @selected(old('status') === 'in_progress')
                        >
                            In Progress
                        </option>

                        <option
                            value="completed"
                            @selected(old('status') === 'completed')
                        >
                            Completed
                        </option>
                    </select>
                </div>

                <div>
                    <label for="start_date">Start date</label>

                    <input
                        id="start_date"
                        name="start_date"
                        type="date"
                        value="{{ old('start_date') }}"
                        required
                    >
                </div>

                <div>
                    <label for="completion_date">
                        Completion date
                    </label>

                    <input
                        id="completion_date"
                        name="completion_date"
                        type="date"
                        value="{{ old('completion_date') }}"
                    >
                </div>

                <div>
                    <label for="approved_budget">
                        Approved budget (₱)
                    </label>

                    <input
                        id="approved_budget"
                        name="approved_budget"
                        type="number"
                        min="0"
                        step="0.01"
                        value="{{ old('approved_budget') }}"
                    >
                </div>

                <div>
                    <label for="actual_expenditure">
                        Actual expenditure (₱)
                    </label>

                    <input
                        id="actual_expenditure"
                        name="actual_expenditure"
                        type="number"
                        min="0"
                        step="0.01"
                        value="{{ old('actual_expenditure') }}"
                    >
                </div>

                <div>
                    <label for="total_target_objectives">
                        Total target objectives
                    </label>

                    <input
                        id="total_target_objectives"
                        name="total_target_objectives"
                        type="number"
                        min="1"
                        value="{{ old(
                            'total_target_objectives',
                            1
                        ) }}"
                        required
                    >
                </div>

                <div>
                    <label for="total_accomplishments">
                        Total accomplishments
                    </label>

                    <input
                        id="total_accomplishments"
                        name="total_accomplishments"
                        type="number"
                        min="0"
                        value="{{ old(
                            'total_accomplishments',
                            0
                        ) }}"
                        required
                    >
                </div>

                <div class="full-width">
                    <label for="primary_output_category">
                        Primary output category
                    </label>

                    <select
                        id="primary_output_category"
                        name="primary_output_category"
                    >
                        <option value="">
                            Select a category
                        </option>

                        <option
                            value="publication"
                            @selected(
                                old('primary_output_category')
                                === 'publication'
                            )
                        >
                            Publication
                        </option>

                        <option
                            value="people_services"
                            @selected(
                                old('primary_output_category')
                                === 'people_services'
                            )
                        >
                            People Services
                        </option>

                        <option
                            value="partnership"
                            @selected(
                                old('primary_output_category')
                                === 'partnership'
                            )
                        >
                            Partnership
                        </option>

                        <option
                            value="product"
                            @selected(
                                old('primary_output_category')
                                === 'product'
                            )
                        >
                            Product
                        </option>

                        <option
                            value="patent"
                            @selected(
                                old('primary_output_category')
                                === 'patent'
                            )
                        >
                            Patent
                        </option>

                        <option
                            value="policy"
                            @selected(
                                old('primary_output_category')
                                === 'policy'
                            )
                        >
                            Policy
                        </option>
                    </select>
                </div>
            </div>

            <div class="buttons">
                <button type="submit">
                    Save Project
                </button>

                <a
                    class="cancel"
                    href="{{ route('projects.index') }}"
                >
                    Cancel
                </a>
            </div>
        </form>
    </main>
</body>
</html>
