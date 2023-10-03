<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chats', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('chat_id')->unique();
            $table->string('chat_ref_id')->nullable();
            $table->string('temp_id')->nullable();
            $table->string('text')->nullable();
            $table->string('link')->nullable();
            $table->string('link_title')->nullable();
            $table->string('link_description')->nullable();
            $table->string('link_image')->nullable();
            $table->string('attachment_url')->nullable();
            $table->string('attachment_type')->nullable();
            $table->string('attachment_title')->nullable();
            $table->boolean('read_by_recipient')->default(false);
            $table->boolean('sent_by_customer')->default(false);
            $table->string('tag');
            $table->string('customer_id');
            $table->string('provider_id')->nullable();

            $table->foreign('customer_id')->references('customer_id')->on('customers')->onDelete('cascade');
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
        Schema::dropIfExists('chats');
    }
}
