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
        Schema::create('cancel_policy_settings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('studio_id')->constrained()->cascadeOnDelete();
            $table->boolean('has_cancel_policy')->default(false);
            $table->unsignedTinyInteger('full_refund_hours')->nullable();
            $table->unsignedTinyInteger('partial_refund_hours')->nullable();
            $table->unsignedTinyInteger('partial_refund_percentage')->nullable();
            $table->unsignedTinyInteger('no_refund_hours')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cancel_policy_settings');
    }
};
