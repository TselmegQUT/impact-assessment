<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(
            'project_outputs',
            function (Blueprint $table) {
                $table->id();

                $table->foreignId('project_id')
                    ->unique()
                    ->constrained('projects')
                    ->cascadeOnDelete();

                $table->unsignedInteger(
                    'policy_count'
                )->default(0);

                $table->decimal(
                    'policy_unit_value',
                    15,
                    2
                )->default(750000);

                $table->unsignedInteger(
                    'patent_count'
                )->default(0);

                $table->decimal(
                    'patent_unit_value',
                    15,
                    2
                )->default(250000);

                $table->unsignedInteger(
                    'product_count'
                )->default(0);

                $table->decimal(
                    'product_unit_value',
                    15,
                    2
                )->default(0);

                $table->unsignedInteger(
                    'people_services_count'
                )->default(0);

                $table->decimal(
                    'people_service_unit_value',
                    15,
                    2
                )->default(15000);

                $table->unsignedInteger(
                    'partnership_count'
                )->default(0);

                $table->decimal(
                    'partnership_total_value',
                    15,
                    2
                )->default(0);

                $table->unsignedInteger(
                    'publication_count'
                )->default(0);

                $table->decimal(
                    'publication_unit_value',
                    15,
                    2
                )->default(50000);

                $table->string(
                    'alignment_level'
                )->nullable();

                $table->text(
                    'alignment_justification'
                )->nullable();

                $table->string(
                    'verification_status'
                )->default('draft');

                $table->foreignId('entered_by')
                    ->constrained('users')
                    ->restrictOnDelete();

                $table->foreignId('verified_by')
                    ->nullable()
                    ->constrained('users')
                    ->nullOnDelete();

                $table->timestamp(
                    'verified_at'
                )->nullable();

                $table->timestamps();
            }
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('project_outputs');
    }
};
