<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable([
    'project_code',
    'title',
    'description',
    'implementing_agency',
    'project_leader',
    'sector',
    'start_date',
    'completion_date',
    'approved_budget',
    'actual_expenditure',
    'total_target_objectives',
    'total_accomplishments',
    'primary_output_category',
    'status',
    'created_by',
])]
class Project extends Model
{
    use HasFactory;

    /*
     * The project's 6P output information.
     */
    public function output(): HasOne
    {
        return $this->hasOne(ProjectOutput::class);
    }

    /*
     * The project's risk assessment.
     */
    public function risk(): HasOne
    {
        return $this->hasOne(ProjectRisk::class);
    }

    /*
     * The project's assessment report.
     */
    public function report(): HasOne
    {
        return $this->hasOne(
            AssessmentReport::class
        );
    }

    /*
     * All PDF and DOCX files uploaded for this project.
     */
    public function documents(): HasMany
    {
        return $this->hasMany(
            UploadedDocument::class
        )->latest();
    }

    /*
     * The user who created the project.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }

    /*
     * Convert database values to useful PHP types.
     */
    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'completion_date' => 'date',
            'approved_budget' => 'decimal:2',
            'actual_expenditure' => 'decimal:2',
            'total_target_objectives' => 'integer',
            'total_accomplishments' => 'integer',
        ];
    }
}
