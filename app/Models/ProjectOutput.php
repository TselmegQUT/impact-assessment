<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'project_id',
    'policy_count',
    'policy_unit_value',
    'patent_count',
    'patent_unit_value',
    'product_count',
    'product_unit_value',
    'people_services_count',
    'people_service_unit_value',
    'partnership_count',
    'partnership_total_value',
    'publication_count',
    'publication_unit_value',
    'alignment_level',
    'alignment_justification',
    'verification_status',
    'entered_by',
    'verified_by',
    'verified_at',
])]
class ProjectOutput extends Model
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
            'policy_count' => 'integer',
            'policy_unit_value' => 'decimal:2',

            'patent_count' => 'integer',
            'patent_unit_value' => 'decimal:2',

            'product_count' => 'integer',
            'product_unit_value' => 'decimal:2',

            'people_services_count' => 'integer',
            'people_service_unit_value' => 'decimal:2',

            'partnership_count' => 'integer',
            'partnership_total_value' => 'decimal:2',

            'publication_count' => 'integer',
            'publication_unit_value' => 'decimal:2',

            'verified_at' => 'datetime',
        ];
    }
}
