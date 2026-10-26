<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClassArm extends Model
{
    protected $fillable = [
        'class_level_id',
        'name',
        'code',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the class level this arm belongs to.
     */
    public function classLevel(): BelongsTo
    {
        return $this->belongsTo(ClassLevel::class);
    }

    /**
     * Get the students assigned to this class arm.
     */
    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }
}