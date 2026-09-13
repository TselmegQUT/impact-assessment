<?php

namespace App\Services;

use App\Models\Project;
use LogicException;

class ImpactCalculatorService
{
    public function calculate(Project $project): array
    {
        $output = $project->output;
        $risk = $project->risk;

        if (!$output) {
            throw new LogicException(
                'The project does not have 6P output data.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Success Ratio
        |--------------------------------------------------------------------------
        */

        $targetObjectives = max(
            1,
            $project->total_target_objectives
        );

        $successRatioRaw =
            $project->total_accomplishments /
            $targetObjectives;

        // The score is capped at 1.0 for the formula.
        $successRatioScore = min(
            $successRatioRaw,
            1.0
        );

        /*
        |--------------------------------------------------------------------------
        | Budget Efficiency
        |--------------------------------------------------------------------------
        */

        $budgetEfficiencyRaw = null;
        $budgetEfficiencyScore = null;

        if (
            $project->actual_expenditure !== null &&
            (float) $project->actual_expenditure > 0 &&
            $project->approved_budget !== null
        ) {
            $budgetEfficiencyRaw =
                (float) $project->approved_budget /
                (float) $project->actual_expenditure;

            // The score is capped at 1.0.
            $budgetEfficiencyScore = min(
                $budgetEfficiencyRaw,
                1.0
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Performance Grade
        |--------------------------------------------------------------------------
        */

        $performanceGrade = null;

        if ($budgetEfficiencyScore !== null) {
            $performanceGrade = (
                ($successRatioScore * 0.70) +
                ($budgetEfficiencyScore * 0.30)
            ) * 100;
        }

        $technicalRating = match (true) {
            $performanceGrade === null =>
                'Not available',

            $performanceGrade >= 90 =>
                'Exceptional',

            $performanceGrade >= 70 =>
                'Satisfactory',

            default =>
                'Requires Calibration',
        };

        /*
        |--------------------------------------------------------------------------
        | Strategic Alignment
        |--------------------------------------------------------------------------
        */

        $alignmentScores = [
            'pioneering' => 1.0,
            'high' => 0.8,
            'supporting' => 0.5,
            'niche' => 0.2,
            'minimal' => 0.1,
        ];

        $alignmentScore =
            $alignmentScores[
                $output->alignment_level
            ] ?? 0;

        /*
        |--------------------------------------------------------------------------
        | Relevance and Contribution Score
        |--------------------------------------------------------------------------
        */

        $relevanceScore =
            ($output->policy_count * 1.0) +
            ($output->patent_count * 0.9) +
            ($output->product_count * 0.8) +
            ($output->partnership_count * 0.6) +
            ($output->people_services_count * 0.4) +
            ($output->publication_count * 0.2) +
            $alignmentScore;

        /*
        |--------------------------------------------------------------------------
        | Immediate Monetary Value
        |--------------------------------------------------------------------------
        */

        $policyValue =
            $output->policy_count *
            (float) $output->policy_unit_value;

        $patentValue =
            $output->patent_count *
            (float) $output->patent_unit_value;

        $productValue =
            $output->product_count *
            (float) $output->product_unit_value;

        $peopleValue =
            $output->people_services_count *
            (float) $output->people_service_unit_value;

        $partnershipValue =
            (float) $output->partnership_total_value;

        $publicationValue =
            $output->publication_count *
            (float) $output->publication_unit_value;

        $immediateMonetaryValue =
            $policyValue +
            $patentValue +
            $productValue +
            $peopleValue +
            $partnershipValue +
            $publicationValue;

        /*
        |--------------------------------------------------------------------------
        | Total 6P Outputs
        |--------------------------------------------------------------------------
        */

        $totalOutputs =
            $output->policy_count +
            $output->patent_count +
            $output->product_count +
            $output->people_services_count +
            $output->partnership_count +
            $output->publication_count;

        /*
        |--------------------------------------------------------------------------
        | Time Maturation Index
        |--------------------------------------------------------------------------
        */

        $monthsElapsed = null;
        $timeMaturationIndex = null;

        if ($project->completion_date !== null) {
            if ($project->completion_date->isFuture()) {
                $monthsElapsed = 0;
            } else {
                $monthsElapsed = (int) floor(
                    $project->completion_date
                        ->diffInMonths(now())
                );
            }

            $timeMaturationIndex = match (true) {
                $monthsElapsed <= 12 => 4.0,
                $monthsElapsed <= 24 => 2.0,
                $monthsElapsed <= 36 => 1.2,
                default => 1.0,
            };
        }

        /*
        |--------------------------------------------------------------------------
        | Impact Readiness Score
        |--------------------------------------------------------------------------
        */

        $impactReadinessScore = null;

        if ($timeMaturationIndex !== null) {
            $impactReadinessScore = (
                $totalOutputs +
                $successRatioScore
            ) / $timeMaturationIndex;
        }

        /*
        |--------------------------------------------------------------------------
        | Maturation Priority Score
        |--------------------------------------------------------------------------
        */

        $targetMonths = [
            'publication' => 12,
            'people_services' => 18,
            'partnership' => 24,
            'product' => 36,
            'patent' => 48,
            'policy' => 60,
        ];

        $impactTracks = [
            'publication' => 'Short-Term',
            'people_services' => 'Short-Term',
            'partnership' => 'Medium-Term',
            'product' => 'Medium-Term',
            'patent' => 'Long-Term',
            'policy' => 'Long-Term',
        ];

        $primaryCategory =
            $project->primary_output_category;

        $maturationTarget =
            $targetMonths[$primaryCategory] ?? null;

        $impactTrack =
            $impactTracks[$primaryCategory]
            ?? 'Not Assigned';

        $maturationPriorityScore = null;

        if (
            $monthsElapsed !== null &&
            $maturationTarget !== null
        ) {
            $maturationPriorityScore =
                $monthsElapsed /
                $maturationTarget;
        }

        /*
        |--------------------------------------------------------------------------
        | Roadmap Recommendation
        |--------------------------------------------------------------------------
        */

        if ($maturationPriorityScore === null) {
            $recommendation =
                'Enter a completion date and primary output category.';
        } elseif ($maturationPriorityScore < 1.0) {
            $remainingMonths = max(
                0,
                $maturationTarget - $monthsElapsed
            );

            $recommendation =
                'Continue monitoring for approximately ' .
                "{$remainingMonths} more month(s).";
        } else {
            $recommendation = match ($impactTrack) {
                'Short-Term' =>
                    'Conduct an Output Verification Survey.',

                'Medium-Term' =>
                    'Conduct a Market Adoption and Outcome Study.',

                'Long-Term' =>
                    'Conduct an Econometric or Quasi-Experimental Study.',

                default =>
                    'Review the project manually.',
            };
        }

        /*
        |--------------------------------------------------------------------------
        | Risk Intensity Score
        |--------------------------------------------------------------------------
        |
        | Operational / Technical: 1.2
        | Institutional:           1.5
        | Financial:               1.8
        | Institutional + Finance: 2.0
        |
        */

        $riskIntensityScore = null;
        $riskLevel = 'Not Assessed';
        $riskRecommendation =
            'Enter the project risk assessment information.';

        if ($risk !== null) {
            $riskIntensityScore =
                ($risk->operational_technical_count * 1.2) +
                ($risk->institutional_count * 1.5) +
                ($risk->financial_count * 1.8) +
                (
                    $risk->institutional_financial_count *
                    2.0
                );

            $riskLevel = match (true) {
                $riskIntensityScore <= 2.0 =>
                    'Low',

                $riskIntensityScore <= 5.0 =>
                    'Medium',

                default =>
                    'High',
            };

            $riskRecommendation = match ($riskLevel) {
                'Low' =>
                    'Standard Deep-Dive: The project is healthy. ' .
                    'Proceed with the planned quantitative study.',

                'Medium' =>
                    'Monitored Deep-Dive: Proceed with a validation ' .
                    'step and verify the identified risk areas first.',

                'High' =>
                    'Calibrated Deep-Dive: Consider qualitative ' .
                    'methods or delay the study until the major ' .
                    'risks have been addressed.',

                default =>
                    'Review the project risks manually.',
            };
        }

        /*
        |--------------------------------------------------------------------------
        | Return Results
        |--------------------------------------------------------------------------
        */

        return [
            'success_ratio_raw' =>
                round($successRatioRaw, 4),

            'success_ratio_percent' =>
                round($successRatioRaw * 100, 2),

            'budget_efficiency_raw' =>
                $budgetEfficiencyRaw === null
                    ? null
                    : round($budgetEfficiencyRaw, 4),

            'budget_efficiency_percent' =>
                $budgetEfficiencyRaw === null
                    ? null
                    : round(
                        $budgetEfficiencyRaw * 100,
                        2
                    ),

            'performance_grade' =>
                $performanceGrade === null
                    ? null
                    : round($performanceGrade, 2),

            'technical_rating' =>
                $technicalRating,

            'alignment_score' =>
                $alignmentScore,

            'relevance_score' =>
                round($relevanceScore, 2),

            'immediate_monetary_value' =>
                round($immediateMonetaryValue, 2),

            'total_outputs' =>
                $totalOutputs,

            'months_elapsed' =>
                $monthsElapsed,

            'time_maturation_index' =>
                $timeMaturationIndex,

            'impact_readiness_score' =>
                $impactReadinessScore === null
                    ? null
                    : round(
                        $impactReadinessScore,
                        2
                    ),

            'maturation_target_months' =>
                $maturationTarget,

            'maturation_priority_score' =>
                $maturationPriorityScore === null
                    ? null
                    : round(
                        $maturationPriorityScore,
                        2
                    ),

            'impact_track' =>
                $impactTrack,

            'recommendation' =>
                $recommendation,

            'risk_intensity_score' =>
                $riskIntensityScore === null
                    ? null
                    : round(
                        $riskIntensityScore,
                        2
                    ),

            'risk_level' =>
                $riskLevel,

            'risk_recommendation' =>
                $riskRecommendation,
        ];
    }
}
