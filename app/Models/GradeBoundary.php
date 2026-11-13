<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GradeBoundary extends Model
{
    protected $fillable = [
        'grade_scale_id',
        'grade',
        'minimum_percentage',
        'maximum_percentage',
        'remark',
        'display_order',
    ];

    protected $casts = [
        'minimum_percentage' => 'decimal:2',
        'maximum_percentage' => 'decimal:2',
        'display_order' => 'integer',
    ];

    public function gradeScale(): BelongsTo
    {
        return $this->belongsTo(GradeScale::class);
    }
}
