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
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->json('permissions')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });

        // Add role_id to users table
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('role_id')->nullable()->after('hash_link')->constrained('roles')->nullOnDelete();
            $table->string('first_name')->nullable()->after('name');
            $table->string('last_name')->nullable()->after('first_name');
            $table->string('phone')->nullable()->after('email');
            $table->string('mobile')->nullable()->after('phone');
            $table->text('address')->nullable()->after('mobile');
            $table->string('city')->nullable()->after('address');
            $table->string('state')->nullable()->after('city');
            $table->string('postcode')->nullable()->after('state');
            $table->string('country', 2)->nullable()->after('postcode');
            $table->string('company_name')->nullable()->after('country');
            $table->string('company_registration')->nullable()->after('company_name');
            $table->string('company_phone')->nullable()->after('company_registration');
            $table->string('company_email')->nullable()->after('company_phone');
            $table->text('company_address')->nullable()->after('company_email');
            $table->string('company_website')->nullable()->after('company_address');
            $table->string('industry')->nullable()->after('company_website');
            $table->enum('status', ['active', 'inactive'])->default('active')->after('industry');
            $table->timestamp('last_login_at')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropColumn([
                'role_id', 'first_name', 'last_name', 'phone', 'mobile',
                'address', 'city', 'state', 'postcode', 'country',
                'company_name', 'company_registration', 'company_phone',
                'company_email', 'company_address', 'company_website',
                'industry', 'status', 'last_login_at'
            ]);
        });

        Schema::dropIfExists('roles');
    }
};
