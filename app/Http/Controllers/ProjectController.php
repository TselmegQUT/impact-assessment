<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Services\ImpactCalculatorService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(): View
    {
        $projects = Project::with('creator')
            ->latest()
            ->paginate(10);

        return view(
            'projects.index',
            compact('projects')
        );
    }

    public function create(): View
    {
        return view('projects.create');
    }

    public function store(
        Request $request
    ): RedirectResponse {
        $validated = $request->validate([
            'project_code' => [
                'required',
                'string',
                'max:100',
                'unique:projects,project_code',
            ],

            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'implementing_agency' => [
                'nullable',
                'string',
                'max:255',
            ],

            'project_leader' => [
                'nullable',
                'string',
                'max:255',
            ],

            'sector' => [
                'nullable',
                'string',
                'max:255',
            ],

            'start_date' => [
                'required',
                'date',
            ],

            'completion_date' => [
                'nullable',
                'date',
                'after_or_equal:start_date',
            ],

            'approved_budget' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'actual_expenditure' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'total_target_objectives' => [
                'required',
                'integer',
                'min:1',
            ],

            'total_accomplishments' => [
                'required',
                'integer',
                'min:0',
                'lte:total_target_objectives',
            ],

            'primary_output_category' => [
                'nullable',
                'in:publication,people_services,partnership,product,patent,policy',
            ],

            'status' => [
                'required',
                'in:draft,in_progress,completed',
            ],
        ]);

        $validated['created_by'] = auth()->id();

        Project::create($validated);

        return redirect()
            ->route('projects.index')
            ->with(
                'success',
                'Project created successfully.'
            );
    }

    public function show(
        Project $project,
        ImpactCalculatorService $calculator
    ): View {
        $project->load([
    'creator',
    'output',
    'risk',
    'report',
]);

        $results = null;

        if ($project->output !== null) {
            $results = $calculator->calculate(
                $project
            );
        }

        return view(
            'projects.show',
            compact('project', 'results')
        );
    }
}
