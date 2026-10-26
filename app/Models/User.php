<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'school_section_id', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the school section assigned to this user.
     */
    public function schoolSection(): BelongsTo
    {
        return $this->belongsTo(SchoolSection::class);
    }

    /**
     * Get the role assigned to this user.
     */
    public function roleModel(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role', 'name');
    }

    /**
     * Check whether the user has a specific permission.
     */
    public function hasPermission(string $permission): bool
    {
        return $this->roleModel
            ?->permissions()
            ->where('name', $permission)
            ->exists() ?? false;
    }

    /**
     * Check whether the user has a specific role.
     */
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}