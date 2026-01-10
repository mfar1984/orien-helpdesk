<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get administrator role
        $adminRole = Role::where('slug', 'administrator')->first();

        User::updateOrCreate(
            ['username' => 'administrator'],
            [
                'name' => 'Administrator',
                'email' => 'admin@orien.local',
                'password' => Hash::make('password'),
                'role_id' => $adminRole?->id,
                'first_name' => 'Admin',
                'last_name' => 'User',
                'status' => 'active',
            ]
        );
    }
}
