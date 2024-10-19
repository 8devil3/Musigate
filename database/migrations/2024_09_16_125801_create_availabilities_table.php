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
        Schema::create('availabilities', function (Blueprint $table) {
            $table->id();

            $table->foreignId('studio_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('weekday'); // 1 = lunedì
            $table->enum('open_type', ['open', 'open_h24', 'close'])->default('open');
            $table->time('open_start')->nullable();
            $table->time('open_end')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('availabilities');
    }
};
