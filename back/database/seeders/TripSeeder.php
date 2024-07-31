<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Trip;
use Faker\Generator as Faker;

class TripSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(Faker $faker): void
    {
        for ($i = 0; $i < 4; $i++) {
            $trip = new Trip();
            $trip->title = $faker->sentence();
            $trip->start_date = $faker->dateTimeInInterval('-1 week', '+2 weeks');
            $trip->save();
        }
    }
}
