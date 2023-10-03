<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProvidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('providers', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('provider_id')->unique();
            $table->string('confirmation_token')->nullable();


            $table->string('title')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('other_name')->nullable();
            $table->string('gender')->nullable();
            $table->string('dob')->nullable();
            $table->string('marital_status')->nullable();
            $table->string('highest_edu_level')->nullable();
            $table->string('institution')->nullable();
            $table->string('about')->nullable();

            $table->string('provider_name')->nullable();

            $table->text('profile_image_url')->nullable();
            $table->string('primary_contact')->nullable();
            $table->string('auxiliary_contact')->nullable();
            $table->string('momo_number')->nullable();
            $table->string('postal_address')->nullable();
            $table->double('longitude', 20, 15)->default(0);
            $table->double('latitude', 20, 15)->default(0);
            $table->double('live_longitude', 20, 15)->default(0);
            $table->double('live_latitude', 20, 15)->default(0);
            $table->string('digital_address')->nullable();
            $table->string('street_address')->nullable();
            $table->string('years_of_operation')->nullable();
            $table->string('date_registered')->nullable();
            $table->boolean('verified')->default(false);

            $table->string('category')->nullable();
            $table->string('identification_type')->nullable();
            $table->string('association_identification_type')->nullable();
            $table->string('identification_number')->nullable();
            $table->string('association_identification_number' )->nullable();
            $table->text('identification_image_url')->nullable();
            $table->text('association_identification_image_url')->nullable();

            $table->string('vehicle_type')->nullable();
            $table->string('vehicle_registration_number')->nullable();
            $table->text('drivers_licence_image_url')->nullable();
            $table->text('drivers_licence_reverse_image_url')->nullable();
            $table->text('road_worthy_sticker_image_url')->nullable();
            $table->text('insurance_sticker_image_url')->nullable();

            $table->string('tin_number')->nullable();
            $table->string('availability')->nullable("Available");;

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
        Schema::dropIfExists('providers');
    }
}
