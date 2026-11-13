<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StudentReportCard extends Model
{
    protected $fillable = [
        'student_id',
        'class_arm_id',
        'academic_session_id',
        'term',
        'class_teacher_comment',
        'class_teacher_name',
        'principal_comment',
        'principal_name',
        'attendance_present',
        'attendance_total',
        'class_position',
        'next_term_begins',
        'status',
        'prepared_by',
        'approved_by',
        'approved_at',
    ];

    protected function casts(): array
    {
        return [
            'attendance_present' => 'integer',
            'attendance_total' => 'integer',
            'class_position' => 'integer',
            'next_term_begins' => 'date',
            'approved_at' => 'datetime',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function classArm(): BelongsTo
    {
        return $this->belongsTo(ClassArm::class);
    }

    public function academicSession(): BelongsTo
    {
        return $this->belongsTo(AcademicSession::class);
    }

    public function preparer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'prepared_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function assessmentRatings(): HasMany
    {
        return $this->hasMany(StudentReportCardAssessmentRating::class);
    }
}