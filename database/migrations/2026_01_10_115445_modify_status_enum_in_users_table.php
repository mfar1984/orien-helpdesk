<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modify ENUM to include 'suspended'
        DB::statement("ALTER TABLE users MODIFY COLUMN status ENUM('active', 'inactive', 'suspended') DEFAULT 'active'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // First update any 'suspended' to 'inactive'
        DB::statement("UPDATE users SET status = 'inactive' WHERE status = 'suspended'");
        
        // Then revert ENUM
        DB::statement("ALTER TABLE users MODIFY COLUMN status ENUM('active', 'inactive') DEFAULT 'active'");
    }
};
