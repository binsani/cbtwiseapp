<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Define all Required Permissions
        $permissions = [
            'view_admin_dashboard',
            'manage_users',
            'manage_questions',
            'manage_exams',
            'manage_subscriptions',
            'view_analytics',
            'manage_messages',
            'manage_reports',
            'manage_purchase_codes',
            'run_bulk_seeder',
            'manage_notifications',
            'manage_settings',
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // 2. Define Roles and Assign Corresponding Permissions
        
        // Admin: Full system control
        $adminRole = Role::updateOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $adminRole->syncPermissions(Permission::all());

        // Moderator: Review and resolve reports, curate questions, check messages & notifications
        $moderatorRole = Role::updateOrCreate(['name' => 'moderator', 'guard_name' => 'web']);
        $moderatorRole->syncPermissions([
            'view_admin_dashboard',
            'manage_questions',
            'manage_reports',
            'manage_messages',
            'manage_notifications',
        ]);

        // Support: User assistance, purchase code generation, contact messages
        $supportRole = Role::updateOrCreate(['name' => 'support', 'guard_name' => 'web']);
        $supportRole->syncPermissions([
            'view_admin_dashboard',
            'manage_users',
            'manage_messages',
            'manage_purchase_codes',
            'manage_notifications',
        ]);

        // Content Editor: Question bank authoring, exam & subject curation, bulk seeder
        $contentEditorRole = Role::updateOrCreate(['name' => 'content_editor', 'guard_name' => 'web']);
        $contentEditorRole->syncPermissions([
            'view_admin_dashboard',
            'manage_questions',
            'manage_exams',
            'run_bulk_seeder',
        ]);

        // Analyst: Performance stats, revenue, questions coverage, and metrics
        $analystRole = Role::updateOrCreate(['name' => 'analyst', 'guard_name' => 'web']);
        $analystRole->syncPermissions([
            'view_admin_dashboard',
            'view_analytics',
        ]);

        // Standard Student / User
        $userRole = Role::updateOrCreate(['name' => 'user', 'guard_name' => 'web']);

        // 3. Assign admin role to first user or demo user
        $firstUser = User::first();
        if ($firstUser && !$firstUser->hasRole('admin')) {
            $firstUser->assignRole('admin');
        }
    }
}
