<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProvisionTaggablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('provision_taggables', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('provision_taggable_id')->unique();
            $table->string('provision_taggable_type')->nullable();

            $table->string('provision_tag_id')->nullable();

            $table->foreign('provision_tag_id')->references('provision_tag_id')->on('provision_tags')->onDelete('cascade');

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
        Schema::dropIfExists('provision_taggables');
    }
}
