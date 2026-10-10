<?php

namespace Tests\Feature;

use App\Models\UploadedDocument;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\Concerns\CreatesTestData;
use Tests\TestCase;

class ProjectDocumentTest extends TestCase
{
    use RefreshDatabase;
    use CreatesTestData;

    protected function setUp(): void
    {
        parent::setUp();

        // Use a fake disk so no real files are written.
        Storage::fake('local');
    }

    public function test_project_officer_can_upload_a_pdf(): void
    {
        $officer = $this->userWithRole('project_officer');
        $project = $this->createProject($officer);

        $this->actingAs($officer)
            ->post(route('projects.documents.store', $project), [
                'document_type' => 'progress_report',
                'document' => UploadedFile::fake()->create('report.pdf', 200, 'application/pdf'),
            ])
            ->assertRedirect(route('projects.documents.index', $project));

        $document = UploadedDocument::first();

        $this->assertSame('report.pdf', $document->original_name);
        Storage::disk('local')->assertExists($document->file_path);
    }

    public function test_only_pdf_and_docx_files_are_allowed(): void
    {
        $officer = $this->userWithRole('project_officer');
        $project = $this->createProject($officer);

        $this->actingAs($officer)
            ->post(route('projects.documents.store', $project), [
                'document_type' => 'other',
                'document' => UploadedFile::fake()->create('photo.png', 100, 'image/png'),
            ])
            ->assertSessionHasErrors('document');

        $this->assertDatabaseCount('uploaded_documents', 0);
    }

    public function test_files_over_20mb_are_rejected(): void
    {
        $officer = $this->userWithRole('project_officer');
        $project = $this->createProject($officer);

        $this->actingAs($officer)
            ->post(route('projects.documents.store', $project), [
                'document_type' => 'other',
                'document' => UploadedFile::fake()->create('big.pdf', 20481, 'application/pdf'),
            ])
            ->assertSessionHasErrors('document');
    }

    public function test_user_can_download_a_document(): void
    {
        $officer = $this->userWithRole('project_officer');
        $project = $this->createProject($officer);
        $document = $this->storeDocument($project, $officer->id);

        $this->actingAs($officer)
            ->get(route('projects.documents.download', [$project, $document]))
            ->assertOk()
            ->assertDownload('report.pdf');
    }

    public function test_document_can_be_deleted(): void
    {
        $officer = $this->userWithRole('project_officer');
        $project = $this->createProject($officer);
        $document = $this->storeDocument($project, $officer->id);

        $this->actingAs($officer)
            ->delete(route('projects.documents.destroy', [$project, $document]))
            ->assertRedirect(route('projects.documents.index', $project));

        $this->assertDatabaseMissing('uploaded_documents', ['id' => $document->id]);
        Storage::disk('local')->assertMissing($document->file_path);
    }

    public function test_document_cannot_be_opened_through_another_project(): void
    {
        $officer = $this->userWithRole('project_officer');
        $project = $this->createProject($officer);
        $otherProject = $this->createProject($officer);
        $document = $this->storeDocument($project, $officer->id);

        $this->actingAs($officer)
            ->get(route('projects.documents.download', [$otherProject, $document]))
            ->assertNotFound();
    }

    public function test_manager_cannot_upload_documents(): void
    {
        $manager = $this->userWithRole('manager');
        $project = $this->createProject($manager);

        $this->actingAs($manager)
            ->post(route('projects.documents.store', $project), [
                'document_type' => 'other',
                'document' => UploadedFile::fake()->create('report.pdf', 100, 'application/pdf'),
            ])
            ->assertForbidden();
    }

    private function storeDocument($project, int $userId): UploadedDocument
    {
        $path = "project-documents/{$project->id}/stored.pdf";
        Storage::disk('local')->put($path, 'PDF content');

        return $project->documents()->create([
            'uploaded_by' => $userId,
            'document_type' => 'progress_report',
            'original_name' => 'report.pdf',
            'stored_name' => 'stored.pdf',
            'file_path' => $path,
            'disk' => 'local',
            'mime_type' => 'application/pdf',
            'extension' => 'pdf',
            'file_size' => 11,
            'status' => 'uploaded',
        ]);
    }
}