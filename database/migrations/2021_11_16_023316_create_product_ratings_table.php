<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductRatingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_ratings', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('product_rating_id')->unique();
            $table->string('product_id');
            $table->boolean('one_star')->default(false);
            $table->boolean('two_star')->default(false);
            $table->boolean('three_star')->default(false);
            $table->boolean('four_star')->default(false);
            $table->boolean('five_star')->default(false);
            $table->string('review')->nullable();

            $table->foreign('product_id')->references('product_id')->on('products')->onDelete('cascade');

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
        Schema::dropIfExists('product_ratings');
    }
}
