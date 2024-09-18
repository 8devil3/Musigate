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
            $table->string('color', 7);
            $table->unsignedTinyInteger('max_capacity')->default(4);
            $table->unsignedSmallInteger('min_price')->default(0);
            $table->unsignedSmallInteger('area')->default(16);
            $table->text('description')->nullable();

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
