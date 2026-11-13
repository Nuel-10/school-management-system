<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReportCardRatingScale extends Model
{
    protected $fillable = [
        'school_section_id',
        'label',
        'score',
        'description',
        'display_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'score' => 'decimal:2',
            'display_order' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function schoolSection(): BelongsTo
    {
        return $this->belongsTo(SchoolSection::class);
    }
}