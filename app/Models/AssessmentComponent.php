<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AssessmentComponent extends Model
{
    protected $fillable = [
        'assessment_scheme_id',
        'name',
        'component_type',
        'maximum_score',
        'display_order',
    ];

    protected $casts = [
        'maximum_score' => 'decimal:2',
        'display_order' => 'integer',
    ];

    public function assessmentScheme(): BelongsTo
    {
        return $this->belongsTo(AssessmentScheme::class);
    }

    public function studentScores(): HasMany
    {
        return $this->hasMany(StudentResultScore::class);
    }
}
