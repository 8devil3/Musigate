<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
        * Run the migrations.
        *
        * @return void
        */
    public function up()
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();

            $table->foreignId('studio_id')->constrained()->cascadeOnDelete();
            $table->string('name')->default('Nuova Sala');
            $table->string('color', 7)->default('#f97316');
            $table->boolean('is_published')->default(false);
            $table->boolean('is_bookable')->default(false);
            $table->unsignedTinyInteger('min_booking')->default(2); // prenotazione minima, espressa in ore
            $table->unsignedTinyInteger('max_capacity')->default(4);
            $table->unsignedSmallInteger('area')->default(20);
            $table->text('description')->nullable();
            $table->string('price_type')->default('no_price');
            // $table->unsignedInteger('hourly_price')->nullable();
            // $table->boolean('has_discounted_hourly_price')->default(false);
            // $table->unsignedInteger('discounted_hourly_price')->nullable();
            $table->unsignedInteger('monthly_price')->nullable();
            $table->boolean('has_discounted_monthly_price')->default(false);
            $table->unsignedInteger('discounted_monthly_price')->nullable();

            $table->timestamps();
        });
    }

    /**
        * Reverse the migrations.
        *
        * @return void
        */
    public function down()
    {
        Schema::dropIfExists('rooms');
    }
};
