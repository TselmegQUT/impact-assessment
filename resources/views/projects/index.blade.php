<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Projects | Impact Assessment System</title>

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
            max-width: 1200px;
            margin: 40px auto;
            padding: 30px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        }

        .top-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .create-button {
            padding: 11px 18px;
            border-radius: 6px;
            color: white;
            background: #15803d;
            text-decoration: none;
        }

        .message {
            margin-bottom: 20px;
            padding: 12px;
            color: #166534;
            background: #dcfce7;
            border-radius: 6px;
        }

        .table-container {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 14px;
            border-bottom: 1px solid #e2e8f0;
            text-align: left;
        }

        th {
            color: #334155;
            background: #f8fafc;
        }

        .status {
            display: inline-block;
            padding: 5px 10px;
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

        .pagination {
            display: flex;
            justify-content: space-between;
            margin-top: 24px;
        }

        .pagination a {
            color: #15803d;
            font-weight: bold;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <header>
        <strong>Impact Assessment System</strong>

        <a href="{{ route('dashboard') }}">
            Dashboard
        </a>
    </header>

    <main>
        <div class="top-row">
            <div>
                <h1>Projects</h1>
                <p>View and manage impact assessment projects.</p>
            </div>

            @if(
                auth()->user()->hasRole('admin') ||
                auth()->user()->hasRole('project_officer')
            )
                <a
                    class="create-button"
                    href="{{ route('projects.create') }}"
                >
                    + Create Project
                </a>
            @endif
        </div>

        @if(session('success'))
            <div class="message">
                {{ session('success') }}
            </div>
        @endif

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Project Code</th>
                        <th>Title</th>
                        <th>Sector</th>
                        <th>Approved Budget</th>
                        <th>Status</th>
                        <th>Created By</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($projects as $project)
                        <tr>
                            <td>{{ $project->project_code }}</td>

                            <td>
    <a href="{{ route('projects.show', $project) }}">
        {{ $project->title }}
    </a>
</td>

                            <td>
                                {{ $project->sector ?? 'Not specified' }}
                            </td>

                            <td>
                                @if($project->approved_budget !== null)
                                    ₱{{ number_format(
                                        (float) $project->approved_budget,
                                        2
                                    ) }}
                                @else
                                    Not entered
                                @endif
                            </td>

                            <td>
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
                            </td>

                            <td>
                                {{ $project->creator?->name ?? 'Unknown' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                No projects have been created.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($projects->hasPages())
            <div class="pagination">
                <div>
                    @if(!$projects->onFirstPage())
                        <a href="{{ $projects->previousPageUrl() }}">
                            ← Previous
                        </a>
                    @endif
                </div>

                <div>
                    @if($projects->hasMorePages())
                        <a href="{{ $projects->nextPageUrl() }}">
                            Next →
                        </a>
                    @endif
                </div>
            </div>
        @endif
    </main>
</body>
</html>
