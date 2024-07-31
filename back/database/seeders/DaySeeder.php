<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Day;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

class DaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(Faker $faker): void
    {
        for ($i = 0; $i < 20; $i++) {
            $day = new Day();
            $day->trip_id = $faker->numberBetween(1, 4);
            $day->date = $faker->dateTimeInInterval('-1 week', '+2 weeks');
            $day->title = $faker->words(3, true);
            $day->slug = Str::of($day->title)->slug('-');
            $day->description = $faker->sentence();
            $day->save();
        }
    }
}
