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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('room_id')->nullable()->constrained()->nullOnDeleteOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDeleteOnDelete();
            $table->dateTime('start');
            $table->unsignedTinyInteger('duration');
            $table->dateTime('end');
            $table->unsignedTinyInteger('guests');
            $table->uuid('qr_code')->unique();
            $table->boolean('is_temp')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
