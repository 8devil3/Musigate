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
        Schema::create('bundles', function (Blueprint $table) {
            $table->id();

            $table->foreignId('studio_id')->constrained()->cascadeOnDelete();
            $table->string('name')->default('Nuovo pacchetto');
            $table->string('cover_path')->nullable();
            $table->string('color', 7)->default('#f97316');
            $table->boolean('is_visible')->default(false);
            $table->boolean('is_bookable')->default(false);
            $table->unsignedTinyInteger('duration')->nullable();
            $table->text('description')->nullable();
            $table->string('price_type')->default('no_price');
            $table->unsignedInteger('fixed_price')->nullable();
            $table->boolean('has_discounted_fixed_price')->default(false);
            $table->unsignedInteger('discounted_fixed_price')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bundles');
    }
};
