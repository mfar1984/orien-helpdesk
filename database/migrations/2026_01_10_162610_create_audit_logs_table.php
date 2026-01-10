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
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('user_type', ['administrator', 'agent', 'customer'])->nullable();
            $table->string('action')->default('view'); // view, download, export, etc.
            $table->string('module'); // tickets, users, knowledgebase, etc.
            $table->string('route_name')->nullable(); // Laravel route name
            $table->string('url'); // Full URL accessed
            $table->string('method')->default('GET'); // HTTP method
            $table->string('subject_type')->nullable(); // Model class if viewing specific item
            $table->unsignedBigInteger('subject_id')->nullable(); // ID of viewed item
            $table->string('subject_name')->nullable(); // Name/title of viewed item
            $table->ipAddress('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->json('request_data')->nullable(); // Query params, filters used
            $table->integer('response_code')->default(200);
            $table->integer('response_time_ms')->nullable(); // Response time in milliseconds
            $table->timestamp('created_at')->useCurrent();
            
            // Indexes for faster queries
            $table->index(['user_id', 'created_at']);
            $table->index(['module', 'created_at']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
