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
      Schema::create('rules', function (Blueprint $table) {
         $table->id();

         $table->foreignId('studio_id')->constrained()->cascadeOnDelete();
         $table->text('before')->nullable();
         $table->text('during')->nullable();
         $table->text('after')->nullable();
         
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
      Schema::dropIfExists('rules');
   }
};
