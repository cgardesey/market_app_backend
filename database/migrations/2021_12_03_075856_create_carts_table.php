<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('cart_id')->unique();
            $table->string('order_id');
            $table->string('provider_id');
            $table->string('customer_id');
            $table->string('status')->nullable();
            $table->decimal('shipping_fee', 8, 2)->default(0);

            $table->foreign('provider_id')->references('provider_id')->on('providers')->onDelete('cascade');
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
        Schema::dropIfExists('carts');
    }
}
