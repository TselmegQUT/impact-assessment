<?php

namespace Tests\Concerns;

use App\Models\AssessmentReport;
use App\Models\Project;
use App\Models\ProjectOutput;
use App\Models\ProjectRisk;
use App\Models\Role;
use App\Models\User;

/*
 * Shared helpers so each test can build users, projects,
 * outputs, risks and reports in one line.
 */
trait CreatesTestData
{
    protected function userWithRole(string $role, array $attributes = []): User
    {
        $roleModel = Role::firstOrCreate(
            ['name' => $role],
            ['display_name' => ucwords(str_replace('_', ' ', $role))]
        );

        return User::factory()->create(array_merge([
            'role_id' => $roleModel->id,
            'is_active' => true,
        ], $attributes));
    }

    protected function createProject(User $creator, array $attributes = []): Project
    {
        return Project::create(array_merge([
            'project_code' => 'TEST-' . uniqid(),
            'title' => 'Test Project',
            'description' => 'Project created for feature testing.',
            'start_date' => now()->subYear()->toDateString(),
            'completion_date' => now()->subMonths(6)->toDateString(),
            'approved_budget' => 100000,
            'actual_expenditure' => 85000,
            'total_target_objectives' => 100,
            'total_accomplishments' => 85,
            'primary_output_category' => 'product',
            'status' => 'completed',
            'created_by' => $creator->id,
        ], $attributes));
    }

    protected function addOutput(Project $project): ProjectOutput
    {
        return ProjectOutput::create([
            'project_id' => $project->id,
            'entered_by' => $project->created_by,
            'policy_count' => 1,
            'policy_unit_value' => 10000,
            'patent_count' => 0,
            'patent_unit_value' => 0,
            'product_count' => 2,
            'product_unit_value' => 5000,
            'people_services_count' => 10,
            'people_service_unit_value' => 100,
            'partnership_count' => 1,
            'partnership_total_value' => 20000,
            'publication_count' => 3,
            'publication_unit_value' => 1000,
            'alignment_level' => 'high',
            'alignment_justification' => 'Supports the regional plan.',
        ]);
    }

    protected function addRisk(Project $project): ProjectRisk
    {
        return ProjectRisk::create([
            'project_id' => $project->id,
            'entered_by' => $project->created_by,
            'operational_technical_count' => 1,
            'institutional_count' => 1,
            'financial_count' => 0,
            'institutional_financial_count' => 0,
        ]);
    }

    protected function addReport(Project $project, string $status): AssessmentReport
    {
        return $project->report()->create([
            'report_number' => 'IAR-' . str_pad((string) $project->id, 5, '0', STR_PAD_LEFT),
            'status' => $status,
            'executive_summary' => 'Summary',
            'findings' => 'Findings',
            'final_recommendation' => 'Recommendation',
            'calculation_results' => [],
            'generated_by' => $project->created_by,
            'generated_at' => now(),
        ]);
    }
}