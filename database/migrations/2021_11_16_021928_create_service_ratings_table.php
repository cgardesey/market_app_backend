<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceRatingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_ratings', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('service_rating_id')->unique();
            $table->string('service_id');
            $table->boolean('one_star')->default(false);
            $table->boolean('two_star')->default(false);
            $table->boolean('three_star')->default(false);
            $table->boolean('four_star')->default(false);
            $table->boolean('five_star')->default(false);
            $table->string('review')->nullable();

            $table->foreign('service_id')->references('service_id')->on('services')->onDelete('cascade');

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
        Schema::dropIfExists('service_ratings');
    }
}
