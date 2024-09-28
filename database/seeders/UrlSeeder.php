<?php

namespace Database\Seeders;

use App\Models\Url;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UrlSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create();

        for ($i = 0; $i < 20; $i++) {
            Url::create([
                'original_url' => $faker->url,
                'short_url' => url('/u/') . '/' . Str::random(6),
                'url_visit_count' => $faker->numberBetween(0, 100),
                'user_id' => $faker->numberBetween(1, 10), // Assuming you have user IDs from 1 to 10
            ]);
        }
    }
}
