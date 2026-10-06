<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UploadedDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'uploaded_by',
        'document_type',
        'original_name',
        'stored_name',
        'file_path',
        'disk',
        'mime_type',
        'extension',
        'file_size',
        'status',
        'extracted_text',
        'extraction_data',
        'extraction_confidence',
        'error_message',
        'processed_at',
        'reviewed_at',
    ];

    /*
     * The project connected to this document.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /*
     * The user who uploaded this document.
     */
    public function uploader(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'uploaded_by'
        );
    }

    /*
     * Convert database values to useful PHP types.
     */
    protected function casts(): array
    {
        return [
            'file_size' => 'integer',
            'extraction_data' => 'array',
            'extraction_confidence' => 'decimal:2',
            'processed_at' => 'datetime',
            'reviewed_at' => 'datetime',
        ];
    }

    /*
     * Return a readable file size.
     */
    public function readableFileSize(): string
    {
        $bytes = $this->file_size;

        if ($bytes < 1024) {
            return $bytes . ' B';
        }

        if ($bytes < 1024 * 1024) {
            return number_format(
                $bytes / 1024,
                1
            ) . ' KB';
        }

        return number_format(
            $bytes / (1024 * 1024),
            1
        ) . ' MB';
    }

    /*
     * Check whether extraction was successful.
     */
    public function hasExtractedData(): bool
    {
        return $this->status === 'extracted'
            || $this->status === 'reviewed';
    }

    /*
     * Check whether document processing failed.
     */
    public function hasFailed(): bool
    {
        return $this->status === 'failed';
    }
}
