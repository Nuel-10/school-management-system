<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentPromotion extends Model
{
    protected $fillable = [
        'student_id',
        'from_class_arm_id',
        'to_class_arm_id',
        'academic_session',
        'term',
        'decision',
        'remarks',
        'processed_by',
    ];

    /**
     * Get the student whose promotion record this is.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the student's previous class arm.
     */
    public function fromClassArm(): BelongsTo
    {
        return $this->belongsTo(
            ClassArm::class,
            'from_class_arm_id'
        );
    }

    /**
     * Get the student's destination class arm.
     */
    public function toClassArm(): BelongsTo
    {
        return $this->belongsTo(
            ClassArm::class,
            'to_class_arm_id'
        );
    }

    /**
     * Get the staff member who processed the promotion.
     */
    public function processedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'processed_by'
        );
    }
}
