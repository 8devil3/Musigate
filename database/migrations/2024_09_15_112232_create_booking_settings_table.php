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
        Schema::create('booking_settings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('studio_id')->constrained()->cascadeOnDelete();
            $table->boolean('is_confirmation_required')->default(false);
            $table->unsignedTinyInteger('booking_advance')->default(1); // preavviso, espresso in giorni
            $table->unsignedSmallInteger('max_booking_horizon')->default(60); // periodo massimo in cui un utente può prenotare nel futuro, espresso in giorni
            $table->boolean('allow_fractional_durations')->default(false); // indica se accettare durate di prenotazione frazionate, esempio: prenotazione di 2,5 ore
            $table->boolean('has_sync')->default(false); // sincronizzazione calendari attiva/disattiva
            $table->enum('sync_mode', ['unidirezionale', 'bidirezionale'])->nullable(); // modalità di sincronizzazione calendari
            $table->string('google_calendar_id')->nullable();
            $table->string('default_calendar_view')->default('dayGridMonth');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_settings');
    }
};
