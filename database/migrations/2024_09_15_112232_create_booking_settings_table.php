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
            $table->unsignedTinyInteger('min_booking')->default(2); // prenotazione minima, espressa in ore
            $table->unsignedTinyInteger('booking_advance')->default(1); // preavviso, espresso in giorni
            $table->unsignedSmallInteger('max_booking_horizon')->default(60); // periodo massimo in cui un utente può prenotare nel futuro, espresso in giorni
            $table->boolean('has_buffer')->default(false);
            $table->unsignedTinyInteger('buffer')->nullable(); // pausa tra una prenotazione e l'altra, espressa in minuti
            $table->boolean('has_sync')->default(false); // sincronizzazione calendari attiva/disattiva
            $table->enum('sync_mode', ['unidirezionale', 'bidirezionale'])->nullable(); // modalità di sincronizzazione calendari
            $table->string('google_calendar_id')->nullable();

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
