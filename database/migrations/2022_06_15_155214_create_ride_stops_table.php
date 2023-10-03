<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRideStopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ride_stops', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('ride_stop_id')->unique();
            $table->double('latitude', 20, 15)->default(0);
            $table->double('longitude', 20, 15)->default(0);
            $table->string('destination')->nullable();
            $table->string('tag')->nullable();

            $table->string('ride_history_id')->nullable();

            $table->foreign('ride_history_id')->references('ride_history_id')->on('ride_histories')->onDelete('cascade');

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
        Schema::dropIfExists('ride_stops');
    }
}
