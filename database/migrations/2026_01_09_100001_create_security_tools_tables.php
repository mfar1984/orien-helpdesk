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
        // Banned Emails Table
        Schema::create('banned_emails', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->text('reason');
            $table->foreignId('added_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });

        // Banned IPs Table
        Schema::create('banned_ips', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address')->unique();
            $table->text('reason');
            $table->foreignId('added_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });

        // Whitelist Emails Table
        Schema::create('whitelist_emails', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->text('reason');
            $table->foreignId('added_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });

        // Whitelist IPs Table
        Schema::create('whitelist_ips', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address')->unique();
            $table->text('reason');
            $table->foreignId('added_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whitelist_ips');
        Schema::dropIfExists('whitelist_emails');
        Schema::dropIfExists('banned_ips');
        Schema::dropIfExists('banned_emails');
    }
};
