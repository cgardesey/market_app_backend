<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProviderEduCertsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('provider_edu_certs', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('provider_edu_cert_id')->unique();
            $table->string('cert_title')->nullable();
            $table->string('institution_name')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('provider_id')->nullable();


            $table->foreign('provider_id')->references('provider_id')->on('providers')->onDelete('cascade');
            $table->boolean('deleted')->default(false);

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
        Schema::dropIfExists('provider_edu_certs');
    }
}
