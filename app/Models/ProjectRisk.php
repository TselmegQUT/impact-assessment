<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'project_id',
    'operational_technical_count',
    'institutional_count',
    'financial_count',
    'institutional_financial_count',
    'risk_notes',
    'verification_status',
    'entered_by',
    'verified_by',
    'verified_at',
])]
class ProjectRisk extends Model
{
    use HasFactory;

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function enteredBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'entered_by'
        );
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'verified_by'
        );
    }

    protected function casts(): array
    {
        return [
            'operational_technical_count' => 'integer',
            'institutional_count' => 'integer',
            'financial_count' => 'integer',
            'institutional_financial_count' => 'integer',
            'verified_at' => 'datetime',
        ];
    }
}
