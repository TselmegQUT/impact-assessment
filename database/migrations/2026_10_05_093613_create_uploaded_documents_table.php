<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('uploaded_documents', function (Blueprint $table) {
            $table->id();

            $table->foreignId('project_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('uploaded_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            /*
             * Examples:
             * project_proposal
             * progress_report
             * completion_report
             * financial_report
             * other
             */
            $table->string('document_type')
                ->default('other');

            $table->string('original_name');

            $table->string('stored_name');

            $table->string('file_path');

            $table->string('disk')
                ->default('local');

            $table->string('mime_type')
                ->nullable();

            $table->string('extension', 20)
                ->nullable();

            $table->unsignedBigInteger('file_size')
                ->default(0);

            /*
             * Processing statuses:
             * uploaded
             * processing
             * extracted
             * reviewed
             * failed
             */
            $table->string('status')
                ->default('uploaded');

            /*
             * Raw text returned by the Python extractor.
             */
            $table->longText('extracted_text')
                ->nullable();

            /*
             * Structured fields returned by Python.
             */
            $table->json('extraction_data')
                ->nullable();

            /*
             * Confidence percentage from 0.00 to 100.00.
             */
            $table->decimal(
                'extraction_confidence',
                5,
                2
            )->nullable();

            $table->text('error_message')
                ->nullable();

            $table->timestamp('processed_at')
                ->nullable();

            $table->timestamp('reviewed_at')
                ->nullable();

            $table->timestamps();

            $table->index([
                'project_id',
                'status',
            ]);

            $table->index([
                'uploaded_by',
                'created_at',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('uploaded_documents');
    }
};
