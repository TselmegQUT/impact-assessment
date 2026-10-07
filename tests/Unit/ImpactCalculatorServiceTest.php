<?php

namespace Tests\Unit;

use App\Models\Project;
use App\Models\ProjectOutput;
use App\Models\ProjectRisk;
use App\Models\Role;
use App\Models\User;
use App\Services\ImpactCalculatorService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use LogicException;
use Tests\TestCase;

class ImpactCalculatorServiceTest extends TestCase
{
    use RefreshDatabase;

    private ImpactCalculatorService $calculator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->calculator = new ImpactCalculatorService();
    }

    private function createUser(): User
    {
        $role = Role::create([
            'name' => 'analyst',
            'display_name' => 'Analyst',
        ]);

        return User::create([
            'name' => 'Test Analyst',
            'email' => 'analyst' . uniqid() . '@example.com',
            'password' => 'password',
            'role_id' => $role->id,
            'is_active' => true,
        ]);
    }

    private function createProject(
        User $user,
        array $data = []
    ): Project {
        return Project::create(array_merge([
            'project_code' => 'TEST-' . uniqid(),
            'title' => 'Numerical Test Project',
            'description' => 'Project created for calculation testing.',
            'start_date' => now()->subYear()->toDateString(),
            'completion_date' => now()->subMonths(6)->toDateString(),
            'approved_budget' => 100000,
            'actual_expenditure' => 85000,
            'total_target_objectives' => 100,
            'total_accomplishments' => 85,
            'primary_output_category' => 'product',
            'status' => 'completed',
            'created_by' => $user->id,
        ], $data));
    }

    private function createOutput(
        Project $project,
        array $data = []
    ): ProjectOutput {
        return ProjectOutput::create(array_merge([
            'project_id' => $project->id,
            'entered_by' => $project->created_by,

            'policy_count' => 5,
            'policy_unit_value' => 10000,

            'patent_count' => 2,
            'patent_unit_value' => 25000,

            'product_count' => 4,
            'product_unit_value' => 15000,

            'people_services_count' => 100,
            'people_service_unit_value' => 500,

            'partnership_count' => 3,
            'partnership_total_value' => 40000,

            'publication_count' => 10,
            'publication_unit_value' => 2000,

            'alignment_level' => 'high',
        ], $data));
    }

    /*
    |--------------------------------------------------------------------------
    | Test 1: Complete project calculation
    |--------------------------------------------------------------------------
    */

    public function test_complete_project_calculates_correct_values(): void
    {
        $user = $this->createUser();

        $project = $this->createProject($user);

        $this->createOutput($project);

        ProjectRisk::create([
            'project_id' => $project->id,
            'entered_by' => $project->created_by,
            'operational_technical_count' => 2,
            'institutional_count' => 1,
            'financial_count' => 1,
            'institutional_financial_count' => 1,
        ]);

        $result = $this->calculator->calculate(
            $project->fresh()
        );

        // Success ratio
        $this->assertSame(
            0.85,
            $result['success_ratio_raw']
        );

        $this->assertSame(
            85.0,
            $result['success_ratio_percent']
        );

        // Budget efficiency
        $this->assertSame(
            1.1765,
            $result['budget_efficiency_raw']
        );

        $this->assertSame(
            117.65,
            $result['budget_efficiency_percent']
        );

        // Performance grade
        $this->assertSame(
            89.5,
            $result['performance_grade']
        );

        $this->assertSame(
            'Satisfactory',
            $result['technical_rating']
        );

        // Alignment
        $this->assertSame(
            0.8,
            $result['alignment_score']
        );

        // Relevance:
        // 5 + 1.8 + 3.2 + 1.8 + 40 + 2 + 0.8
        // = 54.6
        $this->assertSame(
            54.6,
            $result['relevance_score']
        );

        // Monetary value:
        // Policies:       5 × 10,000 = 50,000
        // Patents:        2 × 25,000 = 50,000
        // Products:       4 × 15,000 = 60,000
        // People:       100 × 500    = 50,000
        // Partnerships:               40,000
        // Publications:  10 × 2,000 = 20,000
        // Total = 270,000
        $this->assertSame(
            270000.0,
            $result['immediate_monetary_value']
        );

        // Total outputs
        // 5 + 2 + 4 + 100 + 3 + 10 = 124
        $this->assertSame(
            124,
            $result['total_outputs']
        );

        // Risk:
        // (2 × 1.2) + (1 × 1.5) + (1 × 1.8) + (1 × 2.0)
        // = 7.7
        $this->assertSame(
            7.7,
            $result['risk_intensity_score']
        );

        $this->assertSame(
            'High',
            $result['risk_level']
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Test 2: Success ratio above 100%
    |--------------------------------------------------------------------------
    */

    public function test_success_ratio_above_100_percent_is_capped_for_performance(): void
    {
        $user = $this->createUser();

        $project = $this->createProject($user, [
            'project_code' => 'TEST-CAP-' . uniqid(),
            'total_target_objectives' => 100,
            'total_accomplishments' => 120,
            'approved_budget' => 100000,
            'actual_expenditure' => 100000,
        ]);

        $this->createOutput($project, [
            'publication_count' => 1,
            'publication_unit_value' => 1000,
            'policy_count' => 0,
            'patent_count' => 0,
            'product_count' => 0,
            'people_services_count' => 0,
            'partnership_count' => 0,
            'partnership_total_value' => 0,
        ]);

        $result = $this->calculator->calculate(
            $project->fresh()
        );

        // Raw ratio is 120 / 100 = 1.2
        $this->assertSame(
            1.2,
            $result['success_ratio_raw']
        );

        $this->assertSame(
            120.0,
            $result['success_ratio_percent']
        );

        // Formula uses capped score = 1.0
        // (1.0 × 70%) + (1.0 × 30%) = 100
        $this->assertSame(
            100.0,
            $result['performance_grade']
        );

        $this->assertSame(
            'Exceptional',
            $result['technical_rating']
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Test 3: Zero target objectives
    |--------------------------------------------------------------------------
    */

    public function test_zero_target_objectives_does_not_cause_division_by_zero(): void
    {
        $user = $this->createUser();

        $project = $this->createProject($user, [
            'project_code' => 'TEST-ZERO-' . uniqid(),
            'total_target_objectives' => 0,
            'total_accomplishments' => 5,
        ]);

        $this->createOutput($project, [
            'policy_count' => 0,
            'patent_count' => 0,
            'product_count' => 0,
            'people_services_count' => 0,
            'partnership_count' => 0,
            'publication_count' => 2,
            'publication_unit_value' => 500,
            'partnership_total_value' => 0,
            'alignment_level' => 'supporting',
        ]);

        $result = $this->calculator->calculate(
            $project->fresh()
        );

        // max(1, 0) = 1
        // 5 / 1 = 5
        $this->assertSame(
            5.0,
            $result['success_ratio_raw']
        );

        $this->assertSame(
            500.0,
            $result['success_ratio_percent']
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Test 4: Monetary calculations
    |--------------------------------------------------------------------------
    */

    public function test_monetary_value_is_calculated_correctly(): void
    {
        $user = $this->createUser();

        $project = $this->createProject($user, [
            'project_code' => 'TEST-MONEY-' . uniqid(),
        ]);

        $this->createOutput($project, [
            // 3 × 12,500.50 = 37,501.50
            'policy_count' => 3,
            'policy_unit_value' => 12500.50,

            // 2 × 20,000 = 40,000
            'patent_count' => 2,
            'patent_unit_value' => 20000,

            // 4 × 5,000 = 20,000
            'product_count' => 4,
            'product_unit_value' => 5000,

            // 10 × 250 = 2,500
            'people_services_count' => 10,
            'people_service_unit_value' => 250,

            // Already a total
            'partnership_count' => 2,
            'partnership_total_value' => 15000,

            // 5 × 1,000 = 5,000
            'publication_count' => 5,
            'publication_unit_value' => 1000,

            'alignment_level' => 'pioneering',
        ]);

        $result = $this->calculator->calculate(
            $project->fresh()
        );

        // 37,501.50
        // +40,000
        // +20,000
        // +2,500
        // +15,000
        // +5,000
        // =120,001.50

        $this->assertSame(
            120001.50,
            $result['immediate_monetary_value']
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Test 5: Risk calculation
    |--------------------------------------------------------------------------
    */

    public function test_risk_intensity_is_calculated_correctly(): void
    {
        $user = $this->createUser();

        $project = $this->createProject($user, [
            'project_code' => 'TEST-RISK-' . uniqid(),
        ]);

        $this->createOutput($project, [
            'policy_count' => 1,
            'policy_unit_value' => 1000,

            'patent_count' => 0,
            'product_count' => 0,
            'people_services_count' => 0,
            'partnership_count' => 0,
            'publication_count' => 0,

            'partnership_total_value' => 0,
            'alignment_level' => 'high',
        ]);

        ProjectRisk::create([
            'project_id' => $project->id,
            'entered_by' => $project->created_by,
            'operational_technical_count' => 1,
            'institutional_count' => 1,
            'financial_count' => 0,
            'institutional_financial_count' => 0,
        ]);

        $result = $this->calculator->calculate(
            $project->fresh()
        );

        // 1 × 1.2 + 1 × 1.5 = 2.7
        $this->assertSame(
            2.7,
            $result['risk_intensity_score']
        );

        $this->assertSame(
            'Medium',
            $result['risk_level']
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Test 6: Missing output data
    |--------------------------------------------------------------------------
    */

    public function test_project_without_output_data_throws_exception(): void
    {
        $user = $this->createUser();

        $project = $this->createProject($user, [
            'project_code' => 'TEST-NO-OUTPUT-' . uniqid(),
        ]);

        $this->expectException(LogicException::class);

        $this->expectExceptionMessage(
            'The project does not have 6P output data.'
        );

        $this->calculator->calculate($project);
    }

    /*
    |--------------------------------------------------------------------------
    | Test 7: Missing budget information
    |--------------------------------------------------------------------------
    */

    public function test_budget_efficiency_is_null_when_budget_data_is_missing(): void
    {
        $user = $this->createUser();

        $project = $this->createProject($user, [
            'project_code' => 'TEST-NO-BUDGET-' . uniqid(),
            'approved_budget' => null,
            'actual_expenditure' => null,
        ]);

        $this->createOutput($project, [
            'policy_count' => 1,
            'policy_unit_value' => 1000,

            'patent_count' => 0,
            'product_count' => 0,
            'people_services_count' => 0,
            'partnership_count' => 0,
            'publication_count' => 0,

            'partnership_total_value' => 0,
            'alignment_level' => 'high',
        ]);

        $result = $this->calculator->calculate(
            $project->fresh()
        );

        $this->assertNull(
            $result['budget_efficiency_raw']
        );

        $this->assertNull(
            $result['budget_efficiency_percent']
        );

        $this->assertNull(
            $result['performance_grade']
        );

        $this->assertSame(
            'Not available',
            $result['technical_rating']
        );
    }
}