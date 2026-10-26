<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'super_admin',
                'description' => 'Full system access across all school sections.',
            ],
            [
                'name' => 'admin',
                'description' => 'Administrative access for an assigned school section.',
            ],
            [
                'name' => 'principal',
                'description' => 'Principal-level access for an assigned school section.',
            ],
            [
                'name' => 'sub_admin',
                'description' => 'Limited administrative access for an assigned school section.',
            ],
            [
                'name' => 'teacher',
                'description' => 'Teaching and academic access for assigned school sections.',
            ],
            [
                'name' => 'student',
                'description' => 'Student access to permitted academic and personal information.',
            ],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['name' => $role['name']],
                ['description' => $role['description']]
            );
        }
    }
}