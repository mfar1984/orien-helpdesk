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
        Schema::create('ticket_replies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained('tickets')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->text('message');
            $table->boolean('is_internal_note')->default(false);
            $table->string('status_changed_from')->nullable();
            $table->string('status_changed_to')->nullable();
            $table->string('priority_changed_from')->nullable();
            $table->string('priority_changed_to')->nullable();
            $table->string('assigned_from')->nullable();
            $table->string('assigned_to')->nullable();
            $table->timestamps();
            
            $table->index('ticket_id');
            $table->index('user_id');
            $table->index('is_internal_note');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_replies');
    }
};
