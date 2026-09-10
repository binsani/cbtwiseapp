<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure roles exist
        $this->call(RoleSeeder::class);

        $admin = User::updateOrCreate(
            ['email' => 'szsani@efcc.gov.ng'],
            [
                'name' => 'System Admin',
                'password' => Hash::make('Gifted@2026'),
                'email_verified_at' => now(),
                'plan' => 'premium',
                'is_suspended' => false,
            ]
        );

        if (!$admin->hasRole('admin')) {
            $admin->assignRole('admin');
        }
    }
}
