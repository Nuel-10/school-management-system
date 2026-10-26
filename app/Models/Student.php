<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    protected $fillable = [
        'admission_number',
        'first_name',
        'middle_name',
        'last_name',
        'date_of_birth',
        'gender',
        'phone',
        'email',
        'parent_name',
        'parent_phone',
        'parent_email',
        'school_section_id',
        'class_arm_id',
        'status',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    /**
     * Get the school section the student belongs to.
     */
    public function schoolSection(): BelongsTo
    {
        return $this->belongsTo(SchoolSection::class);
    }

    /**
     * Get the class arm assigned to the student.
     */
    public function classArm(): BelongsTo
    {
        return $this->belongsTo(ClassArm::class);
    }

    /**
     * Get the student's promotion history.
     */
    public function promotions(): HasMany
    {
        return $this->hasMany(StudentPromotion::class);
    }
}
