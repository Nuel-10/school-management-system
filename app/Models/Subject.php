<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model
{
    protected $fillable = [
        'school_section_id',
        'name',
        'code',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function schoolSection(): BelongsTo
    {
        return $this->belongsTo(SchoolSection::class);
    }

    public function classLevels(): BelongsToMany
    {
        return $this->belongsToMany(ClassLevel::class)
            ->withPivot('is_compulsory')
            ->withTimestamps();
    }

    public function results(): HasMany
    {
        return $this->hasMany(StudentResult::class);
    }
}