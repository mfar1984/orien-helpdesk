<?php

namespace Database\Seeders;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Database\Seeder;

class ActivityLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        
        if ($users->isEmpty()) {
            $this->command->info('No users found. Skipping activity log seeding.');
            return;
        }

        $activities = [
            ['action' => 'login', 'module' => 'auth', 'description' => 'User logged in successfully'],
            ['action' => 'logout', 'module' => 'auth', 'description' => 'User logged out'],
            ['action' => 'create', 'module' => 'tickets', 'description' => 'Ticket created: Network Issue #1'],
            ['action' => 'update', 'module' => 'tickets', 'description' => 'Ticket updated: Network Issue #1'],
            ['action' => 'delete', 'module' => 'tickets', 'description' => 'Ticket deleted: Old Support Request #5'],
            ['action' => 'create', 'module' => 'users', 'description' => 'User created: John Doe (john@example.com)'],
            ['action' => 'update', 'module' => 'users', 'description' => 'User updated: Jane Smith (jane@example.com)'],
            ['action' => 'lock', 'module' => 'users', 'description' => 'User locked: Test User (test@example.com)'],
            ['action' => 'unlock', 'module' => 'users', 'description' => 'User unlocked: Test User (test@example.com)'],
            ['action' => 'suspend', 'module' => 'users', 'description' => 'User suspended: Bad Actor (bad@example.com) - Reason: Spam activity'],
            ['action' => 'unsuspend', 'module' => 'users', 'description' => 'User unsuspended: Reformed User (reformed@example.com)'],
            ['action' => 'assign', 'module' => 'tickets', 'description' => 'Ticket #3 assigned to: Support Team'],
            ['action' => 'status_change', 'module' => 'tickets', 'description' => 'Ticket status changed from "Open" to "In Progress"'],
            ['action' => 'password_change', 'module' => 'auth', 'description' => 'Password changed successfully'],
            ['action' => 'create', 'module' => 'roles', 'description' => 'Role created: Supervisor'],
            ['action' => 'update', 'module' => 'settings', 'description' => 'General settings updated'],
            ['action' => 'create', 'module' => 'knowledgebase', 'description' => 'Article created: How to Reset Password'],
            ['action' => 'delete', 'module' => 'knowledgebase', 'description' => 'Article deleted: Outdated FAQ'],
            ['action' => 'failed_login', 'module' => 'auth', 'description' => 'Failed login attempt for: hacker@bad.com'],
            ['action' => 'restore', 'module' => 'tickets', 'description' => 'Ticket restored: Important Request #10'],
        ];

        $ips = ['192.168.1.1', '10.0.0.1', '172.16.0.1', '127.0.0.1', '203.0.113.50', '198.51.100.25'];
        $userAgents = [
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36',
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
            'Mozilla/5.0 (iPhone; CPU iPhone OS 14_0 like Mac OS X) AppleWebKit/605.1.15',
            'Mozilla/5.0 (Linux; Android 11; SM-G991B) AppleWebKit/537.36',
        ];

        // Create 50 sample activity logs
        for ($i = 0; $i < 50; $i++) {
            $activity = $activities[array_rand($activities)];
            $user = $users->random();
            
            // For failed_login, user_id should be null
            $userId = $activity['action'] === 'failed_login' ? null : $user->id;
            
            ActivityLog::create([
                'user_id' => $userId,
                'action' => $activity['action'],
                'module' => $activity['module'],
                'description' => $activity['description'],
                'ip_address' => $ips[array_rand($ips)],
                'user_agent' => $userAgents[array_rand($userAgents)],
                'created_at' => now()->subHours(rand(0, 168)), // Random time within last 7 days
                'updated_at' => now()->subHours(rand(0, 168)),
            ]);
        }

        $this->command->info('Created 50 sample activity logs.');
    }
}
