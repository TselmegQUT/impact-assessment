@extends('layouts.app')

@section('title', 'Project Documents')

@section('content')
    @php
        $roleName = auth()->user()->role?->name;

        $canManageDocuments = in_array(
            $roleName,
            [
                'admin',
                'project_officer',
                'analyst',
            ],
            true
        );

        $documentTypes = [
            'project_proposal' => 'Project Proposal',
            'progress_report' => 'Progress Report',
            'completion_report' => 'Completion Report',
            'financial_report' => 'Financial Report',
            'other' => 'Other Document',
        ];

        $statusLabels = [
            'uploaded' => 'Uploaded',
            'processing' => 'Processing',
            'extracted' => 'Extracted',
            'reviewed' => 'Reviewed',
            'failed' => 'Failed',
        ];
    @endphp

    <style>
        .documents-page {
            display: grid;
            gap: 24px;
        }

        .documents-heading {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 20px;
        }

        .documents-heading h1 {
            margin: 6px 0 8px;
            color: #111827;
            font-size: 30px;
        }

        .documents-heading p {
            margin: 0;
            color: #64748b;
            line-height: 1.6;
        }

        .project-code-label {
            color: #008ac0;
            font-size: 13px;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .documents-back {
            display: inline-flex;
            align-items: center;
            min-height: 42px;
            padding: 10px 16px;
            border: 1px solid #dbe3ea;
            border-radius: 9px;
            color: #344054;
            background: #ffffff;
            font-size: 14px;
            font-weight: 700;
            text-decoration: none;
            white-space: nowrap;
        }

        .documents-back:hover {
            border-color: #00aeef;
            color: #008ac0;
        }

        .document-panel {
            padding: 26px;
            border: 1px solid #e4e9ef;
            border-radius: 14px;
            background: #ffffff;
            box-shadow: 0 5px 18px rgba(16, 24, 40, 0.05);
        }

        .document-panel-header {
            margin-bottom: 22px;
        }

        .document-panel-header h2 {
            margin: 0 0 7px;
            color: #101828;
            font-size: 20px;
        }

        .document-panel-header p {
            margin: 0;
            color: #667085;
            font-size: 14px;
            line-height: 1.6;
        }

        .upload-grid {
            display: grid;
            grid-template-columns: minmax(0, 0.8fr)
                minmax(0, 1.2fr);
            gap: 20px;
            align-items: end;
        }

        .document-field label {
            display: block;
            margin-bottom: 8px;
            color: #344054;
            font-size: 14px;
            font-weight: 700;
        }

        .document-field select,
        .document-field input[type="file"] {
            width: 100%;
            min-height: 48px;
            padding: 11px 13px;
            border: 1px solid #d0d5dd;
            border-radius: 9px;
            color: #344054;
            background: #ffffff;
            font: inherit;
        }

        .document-field select:focus,
        .document-field input[type="file"]:focus {
            border-color: #00aeef;
            outline: none;
            box-shadow: 0 0 0 4px rgba(0, 174, 239, 0.12);
        }

        .upload-help {
            margin: 9px 0 0;
            color: #667085;
            font-size: 12px;
            line-height: 1.5;
        }

        .upload-submit {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 47px;
            margin-top: 20px;
            padding: 11px 20px;
            border: 0;
            border-radius: 9px;
            color: #ffffff;
            background: #00aeef;
            font: inherit;
            font-weight: 700;
            cursor: pointer;
        }

        .upload-submit:hover {
            background: #008ac0;
        }

        .document-errors {
            margin-bottom: 22px;
            padding: 14px 17px;
            border: 1px solid #fecdca;
            border-radius: 9px;
            color: #b42318;
            background: #fef3f2;
            font-size: 14px;
        }

        .document-errors ul {
            margin: 0;
            padding-left: 20px;
        }

        .documents-table-wrapper {
            overflow-x: auto;
        }

        .documents-table {
            width: 100%;
            border-collapse: collapse;
        }

        .documents-table th {
            padding: 13px 14px;
            border-bottom: 1px solid #e4e7ec;
            color: #475467;
            background: #f8fafc;
            font-size: 12px;
            font-weight: 800;
            text-align: left;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .documents-table td {
            padding: 16px 14px;
            border-bottom: 1px solid #eaecf0;
            color: #344054;
            font-size: 14px;
            vertical-align: middle;
        }

        .documents-table tbody tr:hover {
            background: #fbfdfe;
        }

        .file-information {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 240px;
        }

        .file-icon {
            display: grid;
            place-items: center;
            flex: 0 0 auto;
            width: 42px;
            height: 42px;
            border-radius: 10px;
            color: #008ac0;
            background: #e9f8fd;
            font-size: 12px;
            font-weight: 800;
            text-transform: uppercase;
        }

        .file-name {
            display: block;
            max-width: 320px;
            overflow: hidden;
            color: #101828;
            font-weight: 700;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .file-size {
            display: block;
            margin-top: 4px;
            color: #667085;
            font-size: 12px;
        }

        .document-status {
            display: inline-flex;
            align-items: center;
            padding: 5px 10px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 800;
        }

        .status-uploaded {
            color: #175cd3;
            background: #eff8ff;
        }

        .status-processing {
            color: #b54708;
            background: #fffaeb;
        }

        .status-extracted,
        .status-reviewed {
            color: #067647;
            background: #ecfdf3;
        }

        .status-failed {
            color: #b42318;
            background: #fef3f2;
        }

        .document-actions {
            display: flex;
            align-items: center;
            gap: 8px;
            white-space: nowrap;
        }

        .document-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 36px;
            padding: 8px 12px;
            border: 1px solid #d0d5dd;
            border-radius: 8px;
            color: #344054;
            background: #ffffff;
            font-size: 12px;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
        }

        .document-action:hover {
            border-color: #00aeef;
            color: #008ac0;
        }

        .delete-action {
            border-color: #fecdca;
            color: #b42318;
        }

        .delete-action:hover {
            border-color: #f04438;
            color: #ffffff;
            background: #f04438;
        }

        .extract-action {
            color: #667085;
            background: #f2f4f7;
            cursor: not-allowed;
        }

        .empty-documents {
            padding: 48px 20px;
            color: #667085;
            text-align: center;
        }

        .empty-icon {
            display: grid;
            place-items: center;
            width: 64px;
            height: 64px;
            margin: 0 auto 16px;
            border-radius: 18px;
            color: #008ac0;
            background: #e9f8fd;
            font-size: 28px;
        }

        .empty-documents h3 {
            margin: 0 0 7px;
            color: #101828;
        }

        .empty-documents p {
            margin: 0;
        }

        .extraction-notice {
            display: flex;
            align-items: flex-start;
            gap: 13px;
            margin-top: 20px;
            padding: 15px 17px;
            border: 1px solid #b9e6fe;
            border-radius: 10px;
            color: #075985;
            background: #f0f9ff;
            font-size: 13px;
            line-height: 1.6;
        }

        .extraction-notice strong {
            display: block;
            margin-bottom: 2px;
        }

        @media (max-width: 760px) {
            .documents-heading {
                flex-direction: column;
            }

            .upload-grid {
                grid-template-columns: 1fr;
            }

            .document-panel {
                padding: 20px;
            }

            .documents-table thead {
                display: none;
            }

            .documents-table,
            .documents-table tbody,
            .documents-table tr,
            .documents-table td {
                display: block;
                width: 100%;
            }

            .documents-table tr {
                padding: 16px 0;
                border-bottom: 1px solid #eaecf0;
            }

            .documents-table td {
                padding: 7px 0;
                border: 0;
            }

            .document-actions {
                flex-wrap: wrap;
            }
        }
    </style>

    <div class="documents-page">
        <header class="documents-heading">
            <div>
                <span class="project-code-label">
                    {{ $project->project_code }}
                </span>

                <h1>Project Documents</h1>

                <p>
                    Upload and manage supporting documents for
                    {{ $project->title }}.
                </p>
            </div>

            <a
                class="documents-back"
                href="{{ route('projects.show', $project) }}"
            >
                ← Return to Project
            </a>
        </header>

        @if ($canManageDocuments)
            <section class="document-panel">
                <div class="document-panel-header">
                    <h2>Upload a document</h2>

                    <p>
                        Select the document category and upload a
                        PDF or DOCX file for this project.
                    </p>
                </div>

                @if ($errors->any())
                    <div class="document-errors">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form
                    method="POST"
                    action="{{ route(
                        'projects.documents.store',
                        $project
                    ) }}"
                    enctype="multipart/form-data"
                >
                    @csrf

                    <div class="upload-grid">
                        <div class="document-field">
                            <label for="document_type">
                                Document type
                            </label>

                            <select
                                id="document_type"
                                name="document_type"
                                required
                            >
                                <option value="">
                                    Select document type
                                </option>

                                @foreach (
                                    $documentTypes
                                    as $value => $label
                                )
                                    <option
                                        value="{{ $value }}"
                                        @selected(
                                            old('document_type')
                                            === $value
                                        )
                                    >
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="document-field">
                            <label for="document">
                                Select PDF or DOCX file
                            </label>

                            <input
                                id="document"
                                name="document"
                                type="file"
                                accept=".pdf,.docx,application/pdf,application/vnd.openxmlformats-officedocument.wordprocessingml.document"
                                required
                            >

                            <p class="upload-help">
                                Maximum file size: 20 MB.
                                Legacy .doc files are not currently
                                supported.
                            </p>
                        </div>
                    </div>

                    <button
                        class="upload-submit"
                        type="submit"
                    >
                        Upload Document
                    </button>
                </form>

                <div class="extraction-notice">
                    <span>ℹ</span>

                    <div>
                        <strong>
                            Automatic extraction is the next stage
                        </strong>

                        The document is currently stored securely.
                        We will next connect Python to extract text
                        and numerical values from it.
                    </div>
                </div>
            </section>
        @endif

        <section class="document-panel">
            <div class="document-panel-header">
                <h2>
                    Uploaded documents
                    ({{ $project->documents->count() }})
                </h2>

                <p>
                    Files are stored privately and can only be
                    downloaded through an authenticated account.
                </p>
            </div>

            @if ($project->documents->isEmpty())
                <div class="empty-documents">
                    <div class="empty-icon">⇧</div>

                    <h3>No documents uploaded</h3>

                    <p>
                        Upload the first PDF or DOCX document for
                        this project.
                    </p>
                </div>
            @else
                <div class="documents-table-wrapper">
                    <table class="documents-table">
                        <thead>
                            <tr>
                                <th>Document</th>
                                <th>Type</th>
                                <th>Uploaded by</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach (
                                $project->documents
                                as $document
                            )
                                <tr>
                                    <td>
                                        <div class="file-information">
                                            <span class="file-icon">
                                                {{ $document->extension }}
                                            </span>

                                            <div>
                                                <span class="file-name">
                                                    {{ $document->original_name }}
                                                </span>

                                                <span class="file-size">
                                                    {{ $document->readableFileSize() }}
                                                </span>
                                            </div>
                                        </div>
                                    </td>

                                    <td>
                                        {{ $documentTypes[
                                            $document->document_type
                                        ] ?? 'Other Document' }}
                                    </td>

                                    <td>
                                        {{ $document->uploader?->name
                                            ?? 'Unknown user' }}
                                    </td>

                                    <td>
                                        <span
                                            class="
                                                document-status
                                                status-{{ $document->status }}
                                            "
                                        >
                                            {{ $statusLabels[
                                                $document->status
                                            ] ?? ucfirst(
                                                $document->status
                                            ) }}
                                        </span>
                                    </td>

                                    <td>
                                        {{ $document->created_at
                                            ->format('d M Y, g:i A') }}
                                    </td>

                                    <td>
                                        <div class="document-actions">
                                            <a
                                                class="document-action"
                                                href="{{ route(
                                                    'projects.documents.download',
                                                    [
                                                        $project,
                                                        $document,
                                                    ]
                                                ) }}"
                                            >
                                                Download
                                            </a>

                                            <button
                                                class="
                                                    document-action
                                                    extract-action
                                                "
                                                type="button"
                                                disabled
                                                title="Python extraction will be added next"
                                            >
                                                Extract
                                            </button>

                                            @if ($canManageDocuments)
                                                <form
                                                    method="POST"
                                                    action="{{ route(
                                                        'projects.documents.destroy',
                                                        [
                                                            $project,
                                                            $document,
                                                        ]
                                                    ) }}"
                                                    onsubmit="return confirm(
                                                        'Delete this document?'
                                                    );"
                                                >
                                                    @csrf
                                                    @method('DELETE')

                                                    <button
                                                        class="
                                                            document-action
                                                            delete-action
                                                        "
                                                        type="submit"
                                                    >
                                                        Delete
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>

                                @if (
                                    $document->status === 'failed'
                                    && $document->error_message
                                )
                                    <tr>
                                        <td colspan="6">
                                            <div class="document-errors">
                                                Extraction error:
                                                {{ $document->error_message }}
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>
    </div>
@endsection
