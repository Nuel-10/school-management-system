<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class StudentResult extends Model
{
    protected $fillable = [
        'student_id',
        'subject_id',
        'class_arm_id',
        'academic_session_id',
        'term',
        'ca_score',
        'exam_score',
        'total_score',
        'grade',
        'remark',
        'grade_scale_id',
        'status',
    ];

    protected $casts = [
        'ca_score' => 'decimal:2',
        'exam_score' => 'decimal:2',
        'total_score' => 'decimal:2',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function classArm(): BelongsTo
    {
        return $this->belongsTo(ClassArm::class);
    }

    public function academicSession(): BelongsTo
    {
        return $this->belongsTo(AcademicSession::class);
    }

    public function gradeScale(): BelongsTo
    {
        return $this->belongsTo(GradeScale::class);
    }

    public function componentScores(): HasMany
    {
        return $this->hasMany(StudentResultScore::class);
    }
        public function approvalHistories(): HasMany
    {
        return $this->hasMany(StudentResultApprovalHistory::class)
            ->with('user')
            ->latest();
    }
}
