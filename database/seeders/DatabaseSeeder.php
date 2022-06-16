<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        // admin
        \App\Models\Admin::factory(5)->create();
        \App\Models\Admin::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$vtnULA1pR9cviQ5cPj.BAuFNw8rySJ3E2bO5Lwj0HZiaU5C/fwY4y', // secret
            'remember_token' => Str::random(10),
        ]);

        // eo 
        // \App\Models\Organizer::factory(5)->create();
        \App\Models\Organizer::create([
            'name' => 'Senja Organizer',
            'email' => 'senja@gmail.com',
            'password' => '$2y$10$vtnULA1pR9cviQ5cPj.BAuFNw8rySJ3E2bO5Lwj0HZiaU5C/fwY4y', // secret
            'address' => 'Jalan raya utama',
        ]);

        // event category
        $is_category_not_exists = \App\Models\Category::where('name', 'Pendidikan')->orWhere('name', 'Kebudayaan')->count() == 0;
        if ($is_category_not_exists) {
            \App\Models\Category::create(['name' => 'Pendidikan']);
            \App\Models\Category::create(['name' => 'Kebudayaan']);
        }

        // create popular places 
        \App\Models\PopularPlaces::create([
            'name' => 'Concert Hall TBY',
            'lat' => -7.800253835701919,
            'lng' => 110.36749915769805
        ]);

        \App\Models\PopularPlaces::create([
            'name' => 'Gedung Societet',
            'lat' => -7.799945669865703,
            'lng' => 110.3678367539636
        ]);

        \App\Models\PopularPlaces::create([
            'name' => 'Taman Budaya DIY',
            'lat' => -7.799900572407222,
            'lng' => 110.36764329992378
        ]);


        \App\Models\PopularPlaces::create([
            'name' => 'Gedung Kesenian Kabupaten Sleman',
            'lat' => -7.719839947081696,
            'lng' =>  110.36009393309091
        ]);

        \App\Models\PopularPlaces::create([
            'name' => 'SKE',
            'lat' => -7.764205902265705,
            'lng' =>  110.35320652788593
        ]);

        \App\Models\PopularPlaces::create([
            'name' => 'Monumen Yogya Kembali',
            'lat' => -7.744815462460852,
            'lng' => 110.36831272945979
        ]);


        \App\Models\PopularPlaces::create([
            'name' => 'Jogja City Mall',
            'lat' => -7.7523936823136435,
            'lng' => 110.36093421880784
        ]);

        // create event
        // \App\Models\Event::factory(50)->create();
        // \App\Models\SubmittedEvent::factory(50)->create();


        // create place boundaries 
        // $this->call(PlaceBoundarySeeder::class);
    }
}
