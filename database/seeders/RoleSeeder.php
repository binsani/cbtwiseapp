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

        // Create permissions
        $permissions = [
            'access-admin-dashboard',
            'manage-questions',
            'manage-users',
            'manage-payments',
            'manage-purchase-codes',
            'explain-questions',
            'take-unlimited-exams',
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(['name' => $permission]);
        }

        // Create roles and assign created permissions
        $adminRole = Role::updateOrCreate(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());

        $moderatorRole = Role::updateOrCreate(['name' => 'moderator']);
        $moderatorRole->givePermissionTo([
            'access-admin-dashboard',
            'manage-questions',
            'explain-questions',
        ]);

        $userRole = Role::updateOrCreate(['name' => 'user']);

        // Assign admin role to first user
        $firstUser = User::first();
        if ($firstUser) {
            $firstUser->assignRole('admin');
        }
    }
}
