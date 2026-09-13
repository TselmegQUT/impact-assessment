<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Services\ImpactCalculatorService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AssessmentReportController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Generate or regenerate report
    |--------------------------------------------------------------------------
    */

    public function generate(
        Project $project,
        ImpactCalculatorService $calculator
    ): RedirectResponse {
        $project->load([
            'creator',
            'output',
            'risk',
            'report',
        ]);

        if ($project->output === null) {
            return back()->with(
                'error',
                'Please enter the 6P output data before generating the report.'
            );
        }

        if ($project->risk === null) {
            return back()->with(
                'error',
                'Please complete the risk assessment before generating the report.'
            );
        }

        if (
            $project->report !== null &&
            in_array(
                $project->report->status,
                ['submitted', 'approved'],
                true
            )
        ) {
            return back()->with(
                'error',
                'A submitted or approved report cannot be regenerated.'
            );
        }

        $results = $calculator->calculate($project);

        $reportNumber =
            'IAR-' .
            str_pad(
                (string) $project->id,
                5,
                '0',
                STR_PAD_LEFT
            );

        $executiveSummary =
            "This report presents the impact assessment " .
            "results for {$project->title} " .
            "({$project->project_code}). The project was " .
            "implemented by " .
            ($project->implementing_agency ?? 'an unspecified agency') .
            '.';

        $performanceGrade =
            $results['performance_grade'] !== null
                ? number_format(
                    $results['performance_grade'],
                    2
                ) . '%'
                : 'not available';

        $riskScore =
            $results['risk_intensity_score'] !== null
                ? number_format(
                    $results['risk_intensity_score'],
                    2
                )
                : 'not available';

        $findings =
            "The project received a performance grade of " .
            "{$performanceGrade}, with a technical rating of " .
            "{$results['technical_rating']}. " .
            "Its relevance and contribution score is " .
            number_format(
                $results['relevance_score'],
                2
            ) .
            ", and its estimated immediate monetary value is ₱" .
            number_format(
                $results['immediate_monetary_value'],
                2
            ) .
            ". The Risk Intensity Score is {$riskScore}, " .
            "classified as {$results['risk_level']} risk.";

        $finalRecommendation =
            $results['recommendation'] .
            ' ' .
            $results['risk_recommendation'];

        $project->report()->updateOrCreate(
            [
                'project_id' => $project->id,
            ],
            [
                'report_number' => $reportNumber,
                'status' => 'draft',
                'executive_summary' =>
                    $executiveSummary,
                'findings' =>
                    $findings,
                'final_recommendation' =>
                    $finalRecommendation,
                'calculation_results' =>
                    $results,
                'generated_by' =>
                    auth()->id(),
                'reviewed_by' =>
                    null,
                'review_notes' =>
                    null,
                'generated_at' =>
                    now(),
                'reviewed_at' =>
                    null,
            ]
        );

        return redirect()
            ->route(
                'projects.reports.show',
                $project
            )
            ->with(
                'success',
                'Assessment report generated successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Display report
    |--------------------------------------------------------------------------
    */

    public function show(
        Project $project
    ): View|RedirectResponse {
        $project->load([
            'creator',
            'output',
            'risk',
            'report.generatedBy',
            'report.reviewedBy',
        ]);

        if ($project->report === null) {
            return redirect()
                ->route(
                    'projects.show',
                    $project
                )
                ->with(
                    'error',
                    'Generate the assessment report first.'
                );
        }

        return view(
            'reports.show',
            [
                'project' => $project,
                'report' => $project->report,
                'results' =>
                    $project->report
                        ->calculation_results,
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Submit report to manager
    |--------------------------------------------------------------------------
    */

    public function submit(
        Project $project
    ): RedirectResponse {
        $report = $project->report;

        if ($report === null) {
            return back()->with(
                'error',
                'Generate the report before submitting it.'
            );
        }

        if (
            !in_array(
                $report->status,
                ['draft', 'rejected'],
                true
            )
        ) {
            return back()->with(
                'error',
                'Only a draft or rejected report can be submitted.'
            );
        }

        $report->update([
            'status' => 'submitted',
            'reviewed_by' => null,
            'reviewed_at' => null,
        ]);

        return back()->with(
            'success',
            'Report submitted to the Manager successfully.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Approve report
    |--------------------------------------------------------------------------
    */

    public function approve(
        Request $request,
        Project $project
    ): RedirectResponse {
        $report = $project->report;

        if ($report === null) {
            return back()->with(
                'error',
                'The report does not exist.'
            );
        }

        if ($report->status !== 'submitted') {
            return back()->with(
                'error',
                'Only a submitted report can be approved.'
            );
        }

        $validated = $request->validate([
            'review_notes' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ]);

        $report->update([
            'status' => 'approved',
            'reviewed_by' => auth()->id(),
            'review_notes' =>
                $validated['review_notes'] ?? null,
            'reviewed_at' => now(),
        ]);

        return back()->with(
            'success',
            'Assessment report approved successfully.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Reject report
    |--------------------------------------------------------------------------
    */

    public function reject(
        Request $request,
        Project $project
    ): RedirectResponse {
        $report = $project->report;

        if ($report === null) {
            return back()->with(
                'error',
                'The report does not exist.'
            );
        }

        if ($report->status !== 'submitted') {
            return back()->with(
                'error',
                'Only a submitted report can be rejected.'
            );
        }

        $validated = $request->validate([
            'review_notes' => [
                'required',
                'string',
                'max:2000',
            ],
        ]);

        $report->update([
            'status' => 'rejected',
            'reviewed_by' => auth()->id(),
            'review_notes' =>
                $validated['review_notes'],
            'reviewed_at' => now(),
        ]);

        return back()->with(
            'success',
            'Assessment report rejected and returned for revision.'
        );
    }
}
