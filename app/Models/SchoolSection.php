<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SchoolSection extends Model
{
    protected $fillable = [
        'name',
        'code',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the users assigned to this school section.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get the class levels in this school section.
     */
    public function classLevels(): HasMany
    {
        return $this->hasMany(ClassLevel::class);
    }

    /**
     * Get the students in this school section.
     */
    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }
}