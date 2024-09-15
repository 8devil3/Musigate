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
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('studio_id')->constrained()->cascadeOnDelete();
            $table->text('complete_address')->nullable();
            $table->string('address')->nullable();;
            $table->string('number', 8)->nullable();
            $table->string('city')->nullable();;
            $table->string('province')->nullable();;
            $table->string('cap', 5)->nullable();;
            $table->float('lat', 16)->nullable();
            $table->float('lon', 16)->nullable();
            $table->string('notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};
