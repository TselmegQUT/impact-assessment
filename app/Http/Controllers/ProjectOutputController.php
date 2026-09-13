<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectOutput;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectOutputController extends Controller
{
    public function edit(Project $project): View
    {
        $output = $project->output;

        return view(
            'projects.outputs.edit',
            compact('project', 'output')
        );
    }

    public function update(
        Request $request,
        Project $project
    ): RedirectResponse {
        $validated = $request->validate([
            'policy_count' => [
                'required',
                'integer',
                'min:0',
            ],

            'policy_unit_value' => [
                'required',
                'numeric',
                'min:0',
            ],

            'patent_count' => [
                'required',
                'integer',
                'min:0',
            ],

            'patent_unit_value' => [
                'required',
                'numeric',
                'min:0',
            ],

            'product_count' => [
                'required',
                'integer',
                'min:0',
            ],

            'product_unit_value' => [
                'required',
                'numeric',
                'min:0',
            ],

            'people_services_count' => [
                'required',
                'integer',
                'min:0',
            ],

            'people_service_unit_value' => [
                'required',
                'numeric',
                'min:0',
            ],

            'partnership_count' => [
                'required',
                'integer',
                'min:0',
            ],

            'partnership_total_value' => [
                'required',
                'numeric',
                'min:0',
            ],

            'publication_count' => [
                'required',
                'integer',
                'min:0',
            ],

            'publication_unit_value' => [
                'required',
                'numeric',
                'min:0',
            ],

            'alignment_level' => [
                'required',
                'in:pioneering,high,supporting,niche,minimal',
            ],

            'alignment_justification' => [
                'required',
                'string',
                'max:2000',
            ],
        ]);

        if (
            $validated['product_count'] > 0 &&
            $validated['product_unit_value'] <= 0
        ) {
            return back()
                ->withErrors([
                    'product_unit_value' =>
                        'Enter a product unit value when products exist.',
                ])
                ->withInput();
        }

        ProjectOutput::updateOrCreate(
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
                'The 6P output information was saved successfully.'
            );
    }
}
