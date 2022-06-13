<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $jsonResource = file_get_contents('database/data/jogja-known-place.json');
        $jsonParsed = json_decode($jsonResource, true);
        $jogja = $jsonParsed['data'];
        $rand = rand(0, count($jogja));

        return [
            'name' => 'Event ' . $this->faker->city(),
            'description' => $this->faker->text(),
            'content' => $this->faker->randomHtml(2, 3),

            'start_date' => date('Y-m-d H:i:s'),
            'end_date' => date('Y-m-d H:i:s', strtotime("+3 days")),
            'location' => \App\Models\PopularPlaces::inRandomOrder()->first()->lat,
            'photo' => $this->faker->imageUrl(800, 600, 'cats'),
            'lat' => \App\Models\PopularPlaces::inRandomOrder()->first()->lat,
            'lng' => \App\Models\PopularPlaces::inRandomOrder()->first()->lng,
            'link' => 'https://www.youtube.com/watch?v=1UeNcZWSc_I',
            'organizer_id' => \App\Models\Organizer::inRandomOrder()->first()->id,
            'category_id' => \App\Models\Category::inRandomOrder()->first()->id,
            'popular_place_id' => \App\Models\PopularPlaces::inRandomOrder()->first()->id,
        ];
    }
}
