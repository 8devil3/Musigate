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
        Schema::create('studios', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->string('vat', 13)->nullable();
            $table->string('logo')->nullable();
            $table->enum('category', ['Home', 'Professional'])->default('Professional');
            $table->text('description')->nullable();
            $table->boolean('is_record_label')->default(false);
            $table->boolean('is_visible')->default(false);
            $table->boolean('is_complete')->default(false);

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
        Schema::dropIfExists('studios');
    }
};
