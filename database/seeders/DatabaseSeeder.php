<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create an admin user first
        $admin = User::updateOrCreate(
            ['email' => 'admin@cbtwise.com.ng'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'phone' => '+2348012345678',
                'state' => 'Lagos',
                'school' => 'CBTWise Academy',
                'exam_year' => now()->year,
                'plan' => 'premium',
                'email_verified_at' => now(),
            ]
        );

        $this->call([
            ExamSeeder::class,
            SubjectSeeder::class,
            RoleSeeder::class,
        ]);

        // Double check first user is admin
        $admin->assignRole('admin');
    }
}
