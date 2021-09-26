<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['TRAIN', 'BUS'])->default('TRAIN');
            $table->integer('line')->nullable();
            $table->unsignedBigInteger('from_place_id');
            $table->unsignedBigInteger('to_place_id');
            $table->time('departure_time');
            $table->time('arrival_time');
            $table->integer('distance');
            $table->integer('speed');
            $table->enum('status', ['AVAILABLE', 'UNAVAILABLE'])->default('AVAILABLE')->nullable();
            $table->foreign('from_place_id')->references('id')->on('places')->cascadeOnDelete();
            $table->foreign('to_place_id')->references('id')->on('places')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('schedules');
    }
}
