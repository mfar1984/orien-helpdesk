<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Add user_type column - permanent identifier regardless of role changes
            $table->enum('user_type', ['administrator', 'agent', 'customer'])->default('customer')->after('role_id');
        });
        
        // Update existing users based on their current role
        DB::statement("
            UPDATE users u
            LEFT JOIN roles r ON u.role_id = r.id
            SET u.user_type = CASE
                WHEN r.slug = 'administrator' THEN 'administrator'
                WHEN r.slug = 'agent' THEN 'agent'
                ELSE 'customer'
            END
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('user_type');
        });
    }
};
