<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            [
                'name' => 'manage_users',
                'description' => 'Create, edit, deactivate and manage system users.',
            ],
            [
                'name' => 'manage_students',
                'description' => 'Create, edit and manage student records.',
            ],
            [
                'name' => 'manage_teachers',
                'description' => 'Create, edit and manage teacher records.',
            ],
            [
                'name' => 'manage_classes',
                'description' => 'Create, edit and manage classes and class arms.',
            ],
            [
                'name' => 'manage_subjects',
                'description' => 'Create, edit and manage subjects.',
            ],
            [
                'name' => 'manage_student_subjects',
                'description' => 'Assign subjects to students.',
            ],
            [
                'name' => 'manage_teacher_subjects',
                'description' => 'Assign subjects to teachers.',
            ],
            [
                'name' => 'manage_results',
                'description' => 'Create, edit and manage student results.',
            ],
            [
                'name' => 'manage_attendance',
                'description' => 'Create, edit and manage attendance records.',
            ],
            [
                'name' => 'manage_finance',
                'description' => 'Access and manage school financial records.',
            ],
            [
                'name' => 'view_finance',
                'description' => 'View financial records without full management access.',
            ],
            [
                'name' => 'manage_events',
                'description' => 'Create, edit and publish school events and announcements.',
            ],
            [
                'name' => 'manage_public_content',
                'description' => 'Manage information displayed on the public school website.',
            ],
            [
                'name' => 'view_reports',
                'description' => 'View school reports and administrative summaries.',
            ],
            [
                'name' => 'manage_settings',
                'description' => 'Manage school system settings.',
            ],
            [
                'name' => 'reset_teacher_passwords',
                'description' => 'Reset or change teacher login passwords.',
            ],
            [
                'name' => 'reset_user_passwords',
                'description' => 'Reset passwords for permitted users.',
            ],
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['name' => $permission['name']],
                ['description' => $permission['description']]
            );
        }
    }
}