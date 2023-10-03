<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        ini_set('memory_limit', '-1');//allocate memory


        DB::table('service_categories')->insert([
            [
                'service_category_id' => '6e58d71e-aa2a-49be-8639-089018581837',
                'title' => "SuperRide >> Small Car",
                'description' => "SuperRide >> ",
                'url' => "http://41.189.178.40/service_provision/storage/app/uploads/service-categories/small_car.png",
                'tag' => "superride",
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'service_category_id' => '5cdae2ea-ff26-439b-9c74-7aed25bdd458',
                'title' => "SuperRide >> Motor Bike",
                'description' => "SuperRide >> ",
                'url' => "http://41.189.178.40/service_provision/storage/app/uploads/service-categories/motor_bike.png",
                'tag' => "superride",
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'service_category_id' => 'ca94711e-0505-41e9-9f0c-b11615ba27a0',
                'title' => "SuperRide >> Van",
                'description' => "SuperRide >> ",
                'url' => "http://41.189.178.40/service_provision/storage/app/uploads/service-categories/van.png",
                'tag' => "superride",
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
