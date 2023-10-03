<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('service_id')->unique();
            $table->string('service_category')->nullable();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->decimal('min_charge_amount', 8, 2)->default(0);
            $table->decimal('max_charge_amount', 8, 2)->default(0);
            $table->float('rating', 2, 1)->default(0.0);
            $table->integer('total_rating')->default(0);

            $table->string('provider_id')->nullable();

            $table->foreign('provider_id')->references('provider_id')->on('providers')->onDelete('cascade');

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
        Schema::dropIfExists('services');
    }
}
