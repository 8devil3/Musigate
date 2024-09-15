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
            $table->foreignId('room_type_id')->default(1)->constrained();
            $table->foreignId('room_status_id')->default(5)->constrained();

            $table->string('name')->default('Nuova Sala');
            $table->string('color', 7)->default(fake()->hexColor());
            $table->tinyInteger('min_booking', false, true)->default(2);
            $table->tinyInteger('max_capacity', false, true)->default(4);
            $table->smallInteger('min_price', false, true)->default(0);
            $table->smallInteger('area', false, true)->default(16);
            $table->text('desc')->nullable();

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
