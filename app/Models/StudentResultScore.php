<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StudentResultScore extends Model
{
    protected $fillable = [
        'student_result_id',
        'assessment_component_id',
        'score',
    ];

    protected $casts = [
        'score' => 'decimal:2',
    ];

    public function studentResult(): BelongsTo
    {
        return $this->belongsTo(StudentResult::class);
    }

    public function assessmentComponent(): BelongsTo
    {
        return $this->belongsTo(AssessmentComponent::class);
    }
    public function componentScores(): HasMany
{
    return $this->hasMany(StudentResultScore::class);
}
}
