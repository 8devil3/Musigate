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
        Schema::create('bundle_timeband_prices', function (Blueprint $table) {
            $table->id();

            $table->foreignId('bundle_id')->constrained()->cascadeOnDelete();
            $table->foreignId('timeband_id')->nullable()->constrained()->cascadeOnDelete();
            $table->unsignedInteger('price')->default(1);
            $table->boolean('has_discounted_price')->default(false);
            $table->unsignedInteger('discounted_price')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bundle_timeband_prices');
    }
};
