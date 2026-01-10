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
        // Get permission presets from config
        $permissions = config('permissions.role_presets');

        $roles = [
            [
                'name' => 'Administrator',
                'slug' => 'administrator',
                'description' => 'Full system access with all permissions',
                'permissions' => $permissions['administrator'],
                'status' => 'active',
            ],
            [
                'name' => 'Agent',
                'slug' => 'agent',
                'description' => 'Support agent with ticket management access',
                'permissions' => $permissions['agent'],
                'status' => 'active',
            ],
            [
                'name' => 'Customer',
                'slug' => 'customer',
                'description' => 'Customer with limited access to own tickets',
                'permissions' => $permissions['customer'],
                'status' => 'active',
            ],
        ];

        foreach ($roles as $roleData) {
            Role::updateOrCreate(
                ['slug' => $roleData['slug']],
                $roleData
            );
        }
    }
}
