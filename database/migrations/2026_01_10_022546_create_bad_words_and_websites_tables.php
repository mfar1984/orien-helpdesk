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
        // Bad Words Table
        Schema::create('bad_words', function (Blueprint $table) {
            $table->id();
            $table->string('word');
            $table->text('reason')->nullable();
            $table->enum('severity', ['low', 'medium', 'high'])->default('medium');
            $table->foreignId('added_by')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });

        // Bad Websites Table
        Schema::create('bad_websites', function (Blueprint $table) {
            $table->id();
            $table->string('url');
            $table->text('reason')->nullable();
            $table->enum('severity', ['low', 'medium', 'high'])->default('medium');
            $table->foreignId('added_by')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bad_websites');
        Schema::dropIfExists('bad_words');
    }
};
