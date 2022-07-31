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
        Schema::create('room_info', function (Blueprint $table) {
            $table->id();
            $table->string('room_type')->nullable();
            $table->string('floor')->nullable();
            $table->string('section')->nullable();
            $table->string('timestamp')->nullable();
            $table->text('additional_info')->nullable();
            $table->integer('room_id')->nullable();
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
        Schema::dropIfExists('room_info');
    }
};
