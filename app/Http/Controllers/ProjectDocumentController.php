<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\UploadedDocument;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

class ProjectDocumentController extends Controller
{
    /*
     * Display all documents belonging to one project.
     */
    public function index(Project $project): View
    {
        $project->load([
            'documents.uploader',
        ]);

        return view(
            'projects.documents.index',
            compact('project')
        );
    }

    /*
     * Validate and privately store a PDF or DOCX document.
     */
    public function store(
        Request $request,
        Project $project
    ): RedirectResponse {
        $validated = $request->validate([
            'document_type' => [
                'required',
                'string',
                'in:project_proposal,progress_report,completion_report,financial_report,other',
            ],

            'document' => [
                'required',
                'file',
                'mimes:pdf,docx',
                'max:20480',
            ],
        ], [
            'document.required' =>
                'Please select a document.',

            'document.mimes' =>
                'Only PDF and DOCX documents are allowed.',

            'document.max' =>
                'The document cannot be larger than 20 MB.',
        ]);

        $file = $request->file('document');

        $extension = strtolower(
            $file->getClientOriginalExtension()
        );

        $storedName = Str::uuid()->toString()
            . '.'
            . $extension;

        $directory = 'project-documents/'
            . $project->id;

        $path = $file->storeAs(
            $directory,
            $storedName,
            'local'
        );

        if ($path === false) {
            return back()
                ->withInput()
                ->withErrors([
                    'document' =>
                        'The document could not be saved.',
                ]);
        }

        try {
            $project->documents()->create([
                'uploaded_by' => auth()->id(),

                'document_type' =>
                    $validated['document_type'],

                'original_name' =>
                    $file->getClientOriginalName(),

                'stored_name' => $storedName,

                'file_path' => $path,

                'disk' => 'local',

                'mime_type' =>
                    $file->getMimeType(),

                'extension' => $extension,

                'file_size' =>
                    $file->getSize() ?: 0,

                'status' => 'uploaded',
            ]);
        } catch (Throwable $exception) {
            Storage::disk('local')->delete($path);

            report($exception);

            return back()
                ->withInput()
                ->withErrors([
                    'document' =>
                        'The document record could not be created.',
                ]);
        }

        return redirect()
            ->route(
                'projects.documents.index',
                $project
            )
            ->with(
                'success',
                'Document uploaded successfully.'
            );
    }

    /*
     * Download a document through an authenticated route.
     */
    public function download(
        Project $project,
        UploadedDocument $document
    ): StreamedResponse {
        $this->confirmDocumentBelongsToProject(
            $project,
            $document
        );

        abort_unless(
            Storage::disk($document->disk)
                ->exists($document->file_path),
            404,
            'The document file could not be found.'
        );

        return Storage::disk($document->disk)
            ->download(
                $document->file_path,
                $document->original_name
            );
    }

    /*
     * Delete the private file and its database record.
     */
    public function destroy(
        Project $project,
        UploadedDocument $document
    ): RedirectResponse {
        $this->confirmDocumentBelongsToProject(
            $project,
            $document
        );

        Storage::disk($document->disk)
            ->delete($document->file_path);

        $document->delete();

        return redirect()
            ->route(
                'projects.documents.index',
                $project
            )
            ->with(
                'success',
                'Document deleted successfully.'
            );
    }

    /*
     * Prevent a document from being accessed through
     * the URL of a different project.
     */
    private function confirmDocumentBelongsToProject(
        Project $project,
        UploadedDocument $document
    ): void {
        abort_unless(
            $document->project_id === $project->id,
            404
        );
    }
}
