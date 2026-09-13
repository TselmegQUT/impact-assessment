<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(
            'project_risks',
            function (Blueprint $table) {
                $table->id();

                $table->foreignId('project_id')
                    ->unique()
                    ->constrained('projects')
                    ->cascadeOnDelete();

                $table->unsignedInteger(
                    'operational_technical_count'
                )->default(0);

                $table->unsignedInteger(
                    'institutional_count'
                )->default(0);

                $table->unsignedInteger(
                    'financial_count'
                )->default(0);

                $table->unsignedInteger(
                    'institutional_financial_count'
                )->default(0);

                $table->text(
                    'risk_notes'
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
        Schema::dropIfExists('project_risks');
    }
};
