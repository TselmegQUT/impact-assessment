<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(
            'assessment_reports',
            function (Blueprint $table) {
                $table->id();

                $table->foreignId('project_id')
                    ->unique()
                    ->constrained()
                    ->cascadeOnDelete();

                $table->string('report_number')
                    ->unique();

                $table->enum('status', [
                    'draft',
                    'submitted',
                    'approved',
                    'rejected',
                ])->default('draft');

                $table->longText('executive_summary')
                    ->nullable();

                $table->longText('findings')
                    ->nullable();

                $table->longText('final_recommendation')
                    ->nullable();

                $table->json('calculation_results')
                    ->nullable();

                $table->foreignId('generated_by')
                    ->nullable()
                    ->constrained('users')
                    ->nullOnDelete();

                $table->foreignId('reviewed_by')
                    ->nullable()
                    ->constrained('users')
                    ->nullOnDelete();

                $table->text('review_notes')
                    ->nullable();

                $table->timestamp('generated_at')
                    ->nullable();

                $table->timestamp('reviewed_at')
                    ->nullable();

                $table->timestamps();
            }
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('assessment_reports');
    }
};
