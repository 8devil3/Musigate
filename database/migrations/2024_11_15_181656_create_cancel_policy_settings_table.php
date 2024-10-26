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
            $table->unsignedTinyInteger('no_refund_hours')->default(24);
            $table->unsignedTinyInteger('partial_refund_hours')->default(48);
            $table->unsignedTinyInteger('partial_refund_percentage')->default(50);

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
