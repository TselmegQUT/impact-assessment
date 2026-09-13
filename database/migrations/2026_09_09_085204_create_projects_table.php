<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();

            $table->string('project_code')->unique();
            $table->string('title');
            $table->text('description')->nullable();

            $table->string('implementing_agency')->nullable();
            $table->string('project_leader')->nullable();
            $table->string('sector')->nullable();

            $table->date('start_date');
            $table->date('completion_date')->nullable();

            $table->decimal(
                'approved_budget',
                15,
                2
            )->nullable();

            $table->decimal(
                'actual_expenditure',
                15,
                2
            )->nullable();

            $table->unsignedInteger(
                'total_target_objectives'
            )->default(0);

            $table->unsignedInteger(
                'total_accomplishments'
            )->default(0);

            $table->string(
                'primary_output_category'
            )->nullable();

            $table->string('status')
                ->default('draft');

            $table->foreignId('created_by')
                ->constrained('users')
                ->restrictOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
