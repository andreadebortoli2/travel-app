<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Stop;
use Faker\Generator as Faker;
use Illuminate\Support\Testing\Fakes\Fake;

class StopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(Faker $faker): void
    {
        for ($i = 0; $i < 80; $i++) {
            $stop = new Stop();
            $stop->day_id = $faker->numberBetween(1, 20);
            $stop->name = $faker->sentence();
            $stop->position_longitude = $faker->randomFloat(6, -180, 180);
            $stop->position_latitude = $faker->randomFloat(6, -90, 90);
            $stop->notes = $faker->sentence();
            $stop->rating = $faker->numberBetween(1, 5);
            $stop->save();
        }
    }
}
