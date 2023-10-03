<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_images', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('product_image_id')->unique();
            $table->text('url')->nullable();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->boolean('featured_image')->default(true);

            $table->string('product_id')->nullable();

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
        Schema::dropIfExists('product_images');
    }
}
