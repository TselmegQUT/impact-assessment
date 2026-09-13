<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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

    public function output(): HasOne
    {
        return $this->hasOne(ProjectOutput::class);
    }

    public function risk(): HasOne
    {
        return $this->hasOne(ProjectRisk::class);
    }

    public function report(): HasOne
    {
        return $this->hasOne(
            AssessmentReport::class
        );
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }

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
