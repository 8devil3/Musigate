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
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            //1 = superadmin, 2 = studio, 3 = artista
            $table->foreignId('role_id')->default(2)->constrained();

            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->rememberToken();

            $table->text('google_id')->nullable();
            $table->text('google_token')->nullable();
            $table->dateTime('google_token_expires_at')->nullable();
            $table->text('google_refresh_token')->nullable();
            $table->json('google_scopes')->nullable();

            $table->string('paypal_token_type')->nullable();
            $table->string('paypal_access_token')->nullable();
            $table->string('paypal_refresh_token')->nullable();
            $table->dateTime('paypal_access_token_expiration_at')->nullable();
            $table->json('paypal_scopes')->nullable();
            $table->string('paypal_nonce')->nullable();

            $table->string('first_name');
            $table->string('last_name');
            $table->string('avatar')->nullable();

            $table->boolean('is_active')->default(true);
            $table->boolean('tos');
            $table->boolean('privacy');

            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
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
