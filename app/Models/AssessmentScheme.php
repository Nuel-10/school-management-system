<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AssessmentScheme extends Model
{
    protected $fillable = [
        'school_section_id',
        'class_level_id',
        'subject_id',
        'academic_session_id',
        'name',
        'total_marks',
        'is_active',
    ];

    protected $casts = [
        'total_marks' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function schoolSection(): BelongsTo
    {
        return $this->belongsTo(SchoolSection::class);
    }

    public function classLevel(): BelongsTo
    {
        return $this->belongsTo(ClassLevel::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function academicSession(): BelongsTo
    {
        return $this->belongsTo(AcademicSession::class);
    }

    public function components(): HasMany
    {
        return $this->hasMany(AssessmentComponent::class)
            ->orderBy('display_order');
    }
}
