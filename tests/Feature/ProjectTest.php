<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesTestData;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;
    use CreatesTestData;

    private function validProjectData(array $overrides = []): array
    {
        return array_merge([
            'project_code' => 'PRJ-001',
            'title' => 'Smart Farming Pilot',
            'start_date' => '2025-01-01',
            'completion_date' => '2025-12-31',
            'approved_budget' => 50000,
            'actual_expenditure' => 45000,
            'total_target_objectives' => 10,
            'total_accomplishments' => 8,
            'primary_output_category' => 'product',
            'status' => 'completed',
        ], $overrides);
    }

    public function test_project_officer_can_create_a_project(): void
    {
        $officer = $this->userWithRole('project_officer');

        $this->actingAs($officer)
            ->post(route('projects.store'), $this->validProjectData())
            ->assertRedirect(route('projects.index'));

        $this->assertDatabaseHas('projects', [
            'project_code' => 'PRJ-001',
            'created_by' => $officer->id,
        ]);
    }

    public function test_project_requires_code_title_and_start_date(): void
    {
        $officer = $this->userWithRole('project_officer');

        $this->actingAs($officer)
            ->post(route('projects.store'), $this->validProjectData([
                'project_code' => '',
                'title' => '',
                'start_date' => '',
            ]))
            ->assertSessionHasErrors(['project_code', 'title', 'start_date']);

        $this->assertDatabaseCount('projects', 0);
    }

    public function test_project_code_must_be_unique(): void
    {
        $officer = $this->userWithRole('project_officer');
        $this->createProject($officer, ['project_code' => 'PRJ-001']);

        $this->actingAs($officer)
            ->post(route('projects.store'), $this->validProjectData())
            ->assertSessionHasErrors('project_code');
    }

    public function test_completion_date_cannot_be_before_start_date(): void
    {
        $officer = $this->userWithRole('project_officer');

        $this->actingAs($officer)
            ->post(route('projects.store'), $this->validProjectData([
                'start_date' => '2025-06-01',
                'completion_date' => '2025-01-01',
            ]))
            ->assertSessionHasErrors('completion_date');
    }

    public function test_accomplishments_cannot_exceed_targets(): void
    {
        $officer = $this->userWithRole('project_officer');

        $this->actingAs($officer)
            ->post(route('projects.store'), $this->validProjectData([
                'total_target_objectives' => 5,
                'total_accomplishments' => 6,
            ]))
            ->assertSessionHasErrors('total_accomplishments');
    }

    public function test_analyst_cannot_create_a_project(): void
    {
        $analyst = $this->userWithRole('analyst');

        $this->actingAs($analyst)
            ->post(route('projects.store'), $this->validProjectData())
            ->assertForbidden();

        $this->assertDatabaseCount('projects', 0);
    }
}