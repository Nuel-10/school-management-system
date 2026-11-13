<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReportCardSetting extends Model
{
    protected $fillable = [
        'school_section_id',
        'template_name',
        'template_key',
        'show_teacher_comment',
        'show_principal_comment',
        'show_behaviour',
        'show_psychomotor',
        'show_affective',
        'show_attendance',
        'show_class_position',
        'show_next_term_date',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'show_teacher_comment' => 'boolean',
            'show_principal_comment' => 'boolean',
            'show_behaviour' => 'boolean',
            'show_psychomotor' => 'boolean',
            'show_affective' => 'boolean',
            'show_attendance' => 'boolean',
            'show_class_position' => 'boolean',
            'show_next_term_date' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function schoolSection(): BelongsTo
    {
        return $this->belongsTo(SchoolSection::class);
    }
}