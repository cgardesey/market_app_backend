<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRideHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ride_histories', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('ride_history_id')->unique();
            $table->dateTime('start_time')->nullable();
            $table->dateTime('end_time')->nullable();
            $table->double('pickup_longitude', 20, 15)->default(0);
            $table->double('pickup_latitude', 20, 15)->default(0);
            $table->double('destination_longitude', 20, 15)->default(0);
            $table->double('destination_latitude', 20, 15)->default(0);
            $table->dateTime('pickup_address')->nullable();
            $table->dateTime('destination_address')->nullable();
            $table->boolean('one_star')->default(false);
            $table->boolean('two_star')->default(false);
            $table->boolean('three_star')->default(false);
            $table->boolean('four_star')->default(false);
            $table->boolean('five_star')->default(false);
            $table->text('review')->nullable();
            $table->integer('ride_cancelled')->default(-1);

            $table->string('service_id')->nullable();
            $table->string('customer_id')->nullable();

            $table->foreign('service_id')->references('service_id')->on('services')->onDelete('cascade');
            $table->foreign('customer_id')->references('customer_id')->on('customers')->onDelete('cascade');

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
        Schema::dropIfExists('ride_histories');
    }
}
