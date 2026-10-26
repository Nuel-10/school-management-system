<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClassLevel extends Model
{
    protected $fillable = [
        'school_section_id',
        'name',
        'code',
        'display_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'display_order' => 'integer',
    ];

    /**
     * Get the school section this class level belongs to.
     */
    public function schoolSection(): BelongsTo
    {
        return $this->belongsTo(SchoolSection::class);
    }

    /**
     * Get the class arms under this class level.
     */
    public function classArms(): HasMany
    {
        return $this->hasMany(ClassArm::class);
    }
}