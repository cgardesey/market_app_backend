<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_images', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('service_image_id')->unique();
            $table->text('url')->nullable();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->boolean('featured_image')->default(true);

            $table->string('service_id')->nullable();

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
        Schema::dropIfExists('service_images');
    }
}
