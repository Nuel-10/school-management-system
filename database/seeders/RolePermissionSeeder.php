<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = Permission::pluck('id', 'name');

        $rolePermissions = [
            'super_admin' => [
                'manage_users',
                'manage_students',
                'manage_teachers',
                'manage_classes',
                'manage_subjects',
                'manage_student_subjects',
                'manage_teacher_subjects',
                'manage_results',
                'manage_attendance',
                'manage_finance',
                'view_finance',
                'manage_events',
                'manage_public_content',
                'view_reports',
                'manage_settings',
                'reset_teacher_passwords',
                'reset_user_passwords',
            ],

            'admin' => [
                'manage_users',
                'manage_students',
                'manage_teachers',
                'manage_classes',
                'manage_subjects',
                'manage_student_subjects',
                'manage_teacher_subjects',
                'manage_results',
                'manage_attendance',
                'view_finance',
                'manage_events',
                'manage_public_content',
                'view_reports',
                'reset_teacher_passwords',
                'reset_user_passwords',
            ],

            'principal' => [
                'manage_users',
                'manage_students',
                'manage_teachers',
                'manage_classes',
                'manage_subjects',
                'manage_student_subjects',
                'manage_teacher_subjects',
                'manage_results',
                'manage_attendance',
                'manage_finance',
                'view_finance',
                'manage_events',
                'manage_public_content',
                'view_reports',
                'reset_teacher_passwords',
                'reset_user_passwords',
            ],

            'sub_admin' => [
                'manage_students',
                'manage_classes',
                'manage_subjects',
                'manage_student_subjects',
                'manage_events',
                'view_reports',
            ],

            'teacher' => [
                'manage_results',
                'manage_attendance',
                'view_reports',
            ],

            'student' => [],
        ];

        foreach ($rolePermissions as $roleName => $permissionNames) {
            $role = Role::where('name', $roleName)->first();

            if (! $role) {
                continue;
            }

            $permissionIds = collect($permissionNames)
                ->map(fn ($permissionName) => $permissions[$permissionName] ?? null)
                ->filter()
                ->values()
                ->all();

            $role->permissions()->sync($permissionIds);
        }
    }
}