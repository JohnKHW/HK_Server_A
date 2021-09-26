<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRouteSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('route_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('route_id')->cascadeOnDelete();
            $table->foreignId('schedule_id')->cascadeOnDelete();
            $table->integer('step');
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
        Schema::dropIfExists('route_schedules');
    }
}
