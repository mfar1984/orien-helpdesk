<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('login_attempts')->default(0)->after('status');
            $table->timestamp('locked_at')->nullable()->after('login_attempts');
            $table->timestamp('suspended_at')->nullable()->after('locked_at');
            $table->string('suspended_reason')->nullable()->after('suspended_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['login_attempts', 'locked_at', 'suspended_at', 'suspended_reason']);
        });
    }
};
