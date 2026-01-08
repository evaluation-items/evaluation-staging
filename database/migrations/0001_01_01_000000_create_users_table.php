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
        // Modify the existing 'users' table
        Schema::table('public.users', function (Blueprint $table) {
            $table->id()->change(); // Ensure the primary key is 'id'
            $table->string('name')->nullable(false)->change();
            $table->string('email')->unique()->change();
            $table->timestamp('email_verified_at')->nullable()->change();
            $table->string('password')->nullable(false)->change();
          //  $table->rememberToken(); // Add remember_token if not already present
            //$table->timestamps();   // Add created_at and updated_at if not already present

            // Ensure additional columns exist
            $table->integer('role')->nullable(false)->default(0)->change();
            $table->string('phone')->nullable()->change();
            $table->string('picture')->nullable()->change();
            $table->bigInteger('dept_id')->nullable()->change();
            $table->integer('role_manage')->default(0)->change();
            $table->integer('login_user')->nullable()->change();
        });

        // Create the 'password_reset_tokens' table
        Schema::create('public.password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // Create the 'sessions' table
        Schema::create('public.sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
