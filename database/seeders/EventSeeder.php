<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;


class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $csv = fopen("database/data/event_sample.csv", "r");
        $firstline = true;
        while (($data = fgetcsv($csv, 2000, ",")) !== FALSE) {
            if (!$firstline) {
                Event::create([
                    // faker
                    "description" => $faker->text(),
                    'content' => $faker->randomHtml(2, 3),

                    'start_date' => date('Y-m-d H:i:s'),
                    'end_date' => date('Y-m-d H:i:s', strtotime("+3 days")),
                    'location' => $faker->address(),
                    'photo' => $faker->imageUrl(800, 600, 'cats'),
                    'link' => 'https://www.youtube.com/watch?v=1UeNcZWSc_I',

                    'organizer_id' => \App\Models\Organizer::inRandomOrder()->first()->id,
                    'category_id' => \App\Models\Category::inRandomOrder()->first()->id,

                    // by csv
                    "name" => $data[0],
                    "lat" => $data[1],
                    "lng" => $data[2],
                ]);
            }
            $firstline = false;
        }

        fclose($csv);
    }
}
