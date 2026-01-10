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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('action'); // login, logout, create, update, delete, etc.
            $table->string('module'); // tickets, users, roles, settings, etc.
            $table->string('description');
            $table->text('old_values')->nullable(); // JSON of old values (for updates)
            $table->text('new_values')->nullable(); // JSON of new values (for updates)
            $table->unsignedBigInteger('subject_id')->nullable(); // ID of the affected record
            $table->string('subject_type')->nullable(); // Model class of the affected record
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();
            
            $table->index(['user_id', 'created_at']);
            $table->index(['module', 'action']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
