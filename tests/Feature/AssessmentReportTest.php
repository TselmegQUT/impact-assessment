<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesTestData;
use Tests\TestCase;

class AssessmentReportTest extends TestCase
{
    use RefreshDatabase;
    use CreatesTestData;

    public function test_analyst_can_generate_a_report(): void
    {
        $analyst = $this->userWithRole('analyst');
        $project = $this->createProject($analyst);
        $this->addOutput($project);
        $this->addRisk($project);

        $response = $this->actingAs($analyst)
            ->post(route('projects.reports.generate', $project));

        $response->assertRedirect(route('projects.reports.show', $project));

        $this->assertDatabaseHas('assessment_reports', [
            'project_id' => $project->id,
            'status' => 'draft',
            'generated_by' => $analyst->id,
        ]);
    }

    public function test_report_cannot_be_generated_without_output_data(): void
    {
        $analyst = $this->userWithRole('analyst');
        $project = $this->createProject($analyst);

        $this->actingAs($analyst)
            ->post(route('projects.reports.generate', $project))
            ->assertSessionHas('error');

        $this->assertDatabaseMissing('assessment_reports', [
            'project_id' => $project->id,
        ]);
    }

    public function test_report_cannot_be_generated_without_risk_data(): void
    {
        $analyst = $this->userWithRole('analyst');
        $project = $this->createProject($analyst);
        $this->addOutput($project);

        $this->actingAs($analyst)
            ->post(route('projects.reports.generate', $project))
            ->assertSessionHas('error');

        $this->assertDatabaseMissing('assessment_reports', [
            'project_id' => $project->id,
        ]);
    }

    public function test_draft_report_can_be_submitted(): void
    {
        $analyst = $this->userWithRole('analyst');
        $project = $this->createProject($analyst);
        $report = $this->addReport($project, 'draft');

        $this->actingAs($analyst)
            ->patch(route('projects.reports.submit', $project))
            ->assertSessionHas('success');

        $this->assertSame('submitted', $report->fresh()->status);
    }

    public function test_manager_can_approve_a_submitted_report(): void
    {
        $manager = $this->userWithRole('manager');
        $project = $this->createProject($manager);
        $report = $this->addReport($project, 'submitted');

        $this->actingAs($manager)
            ->patch(route('projects.reports.approve', $project))
            ->assertSessionHas('success');

        $report->refresh();
        $this->assertSame('approved', $report->status);
        $this->assertSame($manager->id, $report->reviewed_by);
    }

    public function test_manager_can_reject_a_submitted_report_with_notes(): void
    {
        $manager = $this->userWithRole('manager');
        $project = $this->createProject($manager);
        $report = $this->addReport($project, 'submitted');

        $this->actingAs($manager)
            ->patch(route('projects.reports.reject', $project), [
                'review_notes' => 'Please check the budget figures.',
            ])
            ->assertSessionHas('success');

        $report->refresh();
        $this->assertSame('rejected', $report->status);
        $this->assertSame('Please check the budget figures.', $report->review_notes);
    }

    public function test_reject_requires_review_notes(): void
    {
        $manager = $this->userWithRole('manager');
        $project = $this->createProject($manager);
        $report = $this->addReport($project, 'submitted');

        $this->actingAs($manager)
            ->patch(route('projects.reports.reject', $project), [
                'review_notes' => '',
            ])
            ->assertSessionHasErrors('review_notes');

        $this->assertSame('submitted', $report->fresh()->status);
    }

    public function test_draft_report_cannot_be_approved(): void
    {
        $manager = $this->userWithRole('manager');
        $project = $this->createProject($manager);
        $report = $this->addReport($project, 'draft');

        $this->actingAs($manager)
            ->patch(route('projects.reports.approve', $project))
            ->assertSessionHas('error');

        $this->assertSame('draft', $report->fresh()->status);
    }

    public function test_analyst_cannot_approve_a_report(): void
    {
        $analyst = $this->userWithRole('analyst');
        $project = $this->createProject($analyst);
        $report = $this->addReport($project, 'submitted');

        $this->actingAs($analyst)
            ->patch(route('projects.reports.approve', $project))
            ->assertForbidden();

        $this->assertSame('submitted', $report->fresh()->status);
    }

    public function test_submitted_report_cannot_be_regenerated(): void
    {
        $analyst = $this->userWithRole('analyst');
        $project = $this->createProject($analyst);
        $this->addOutput($project);
        $this->addRisk($project);
        $report = $this->addReport($project, 'submitted');

        $this->actingAs($analyst)
            ->post(route('projects.reports.generate', $project))
            ->assertSessionHas('error');

        $this->assertSame('submitted', $report->fresh()->status);
    }
}