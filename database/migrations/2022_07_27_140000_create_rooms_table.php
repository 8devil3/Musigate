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
            $table->string('status')->default('bozza');
            $table->string('name')->default('Nuova Sala');
            $table->string('color', 7)->default('#f97316');
            $table->unsignedTinyInteger('max_capacity')->default(4);
            $table->unsignedSmallInteger('price')->default(0);
            $table->unsignedSmallInteger('discounted_price')->nullable();
            $table->dateTime('discount_start')->nullable();
            $table->dateTime('discount_end')->nullable();
            $table->unsignedSmallInteger('area')->default(20);
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
