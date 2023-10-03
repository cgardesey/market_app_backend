<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('customer_id')->unique();
            $table->string('confirmation_token')->nullable();

            $table->text('profile_image_url')->nullable();
            $table->string('name')->nullable();
            $table->string('gender')->nullable();
            $table->string('primary_contact')->nullable();
            $table->string('auxiliary_contact')->nullable();
            $table->double('live_longitude', 20, 15)->default(0);
            $table->double('live_latitude', 20, 15)->default(0);
            $table->double('longitude', 20, 15)->default(0);
            $table->double('latitude', 20, 15)->default(0);
            $table->string('digital_address')->nullable();
            $table->string('street_address')->nullable();
            $table->string('user_id')->nullable();

            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');

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
        Schema::dropIfExists('customers');
    }
}
