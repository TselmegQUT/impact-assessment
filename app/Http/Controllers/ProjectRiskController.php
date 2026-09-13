<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectRisk;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectRiskController extends Controller
{
    public function edit(Project $project): View
    {
        $risk = $project->risk;

        return view(
            'projects.risks.edit',
            compact('project', 'risk')
        );
    }

    public function update(
        Request $request,
        Project $project
    ): RedirectResponse {
        $validated = $request->validate([
            'operational_technical_count' => [
                'required',
                'integer',
                'min:0',
            ],

            'institutional_count' => [
                'required',
                'integer',
                'min:0',
            ],

            'financial_count' => [
                'required',
                'integer',
                'min:0',
            ],

            'institutional_financial_count' => [
                'required',
                'integer',
                'min:0',
            ],

            'risk_notes' => [
                'nullable',
                'string',
                'max:5000',
            ],
        ]);

        ProjectRisk::updateOrCreate(
            [
                'project_id' => $project->id,
            ],
            array_merge($validated, [
                'entered_by' => auth()->id(),
                'verification_status' => 'draft',
                'verified_by' => null,
                'verified_at' => null,
            ])
        );

        return redirect()
            ->route('projects.show', $project)
            ->with(
                'success',
                'Risk information was saved successfully.'
            );
    }
}
