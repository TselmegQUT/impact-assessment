<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesTestData;
use Tests\TestCase;

class ProjectDataEntryTest extends TestCase
{
    use RefreshDatabase;
    use CreatesTestData;

    private function validOutputData(array $overrides = []): array
    {
        return array_merge([
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
        ], $overrides);
    }

    public function test_analyst_can_save_6p_outputs(): void
    {
        $analyst = $this->userWithRole('analyst');
        $project = $this->createProject($analyst);

        $this->actingAs($analyst)
            ->put(route('projects.outputs.update', $project), $this->validOutputData())
            ->assertRedirect(route('projects.show', $project));

        $this->assertDatabaseHas('project_outputs', [
            'project_id' => $project->id,
            'product_count' => 2,
            'alignment_level' => 'high',
        ]);
    }

    public function test_products_need_a_unit_value(): void
    {
        $analyst = $this->userWithRole('analyst');
        $project = $this->createProject($analyst);

        $this->actingAs($analyst)
            ->put(route('projects.outputs.update', $project), $this->validOutputData([
                'product_count' => 3,
                'product_unit_value' => 0,
            ]))
            ->assertSessionHasErrors('product_unit_value');

        $this->assertDatabaseMissing('project_outputs', ['project_id' => $project->id]);
    }

    public function test_output_counts_cannot_be_negative(): void
    {
        $analyst = $this->userWithRole('analyst');
        $project = $this->createProject($analyst);

        $this->actingAs($analyst)
            ->put(route('projects.outputs.update', $project), $this->validOutputData([
                'policy_count' => -1,
            ]))
            ->assertSessionHasErrors('policy_count');
    }

    public function test_alignment_level_must_be_a_known_value(): void
    {
        $analyst = $this->userWithRole('analyst');
        $project = $this->createProject($analyst);

        $this->actingAs($analyst)
            ->put(route('projects.outputs.update', $project), $this->validOutputData([
                'alignment_level' => 'excellent',
            ]))
            ->assertSessionHasErrors('alignment_level');
    }

    public function test_analyst_can_save_risks(): void
    {
        $analyst = $this->userWithRole('analyst');
        $project = $this->createProject($analyst);

        $this->actingAs($analyst)
            ->put(route('projects.risks.update', $project), [
                'operational_technical_count' => 2,
                'institutional_count' => 1,
                'financial_count' => 0,
                'institutional_financial_count' => 0,
                'risk_notes' => 'Supplier delays.',
            ])
            ->assertRedirect(route('projects.show', $project));

        $this->assertDatabaseHas('project_risks', [
            'project_id' => $project->id,
            'operational_technical_count' => 2,
        ]);
    }

    public function test_risk_counts_are_required(): void
    {
        $analyst = $this->userWithRole('analyst');
        $project = $this->createProject($analyst);

        $this->actingAs($analyst)
            ->put(route('projects.risks.update', $project), [])
            ->assertSessionHasErrors([
                'operational_technical_count',
                'institutional_count',
                'financial_count',
                'institutional_financial_count',
            ]);
    }

    public function test_manager_cannot_save_outputs_or_risks(): void
    {
        $manager = $this->userWithRole('manager');
        $project = $this->createProject($manager);

        $this->actingAs($manager)
            ->put(route('projects.outputs.update', $project), $this->validOutputData())
            ->assertForbidden();

        $this->actingAs($manager)
            ->put(route('projects.risks.update', $project), [
                'operational_technical_count' => 1,
                'institutional_count' => 0,
                'financial_count' => 0,
                'institutional_financial_count' => 0,
            ])
            ->assertForbidden();
    }
}