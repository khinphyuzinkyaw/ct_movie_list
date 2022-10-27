<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Rating;
use Carbon\Carbon;

class RatingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         $ratings = [
            [
                'rating' => 1,
                'user_id' => 1,
                'movie_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'rating' => 4,
                'user_id' => 1,
                'movie_id' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'rating' => 4,
                'user_id' => 1,
                'movie_id' => 3,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'rating' => 4,
                'user_id' => 1,
                'movie_id' => 4,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'rating' => 5,
                'user_id' => 1,
                'movie_id' => 5,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'rating' => 4,
                'user_id' => 1,
                'movie_id' => 5,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ];

        Rating::insert($ratings);
    }
}
