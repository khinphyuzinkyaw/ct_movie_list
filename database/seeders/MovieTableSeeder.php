<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Movie;
use Carbon\Carbon;

class MovieTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $movies = [
            [
                'title' => 'Movie 1',
                'summary' => 'Movie 1',
                'cover_image' => null,
                'user_id' => 1,
                'genre_id' => 1,
                'author_id' => 1,
                'created_at'=> Carbon::now(),
                'updated_at'=> Carbon::now()
            ],
            [
                'title' => 'Movie 2',
                'summary' => 'Movie 2',
                'cover_image' => null,
                'user_id' => 2,
                'genre_id' => 2,
                'author_id' => 2,
                'created_at'=> Carbon::now(),
                'updated_at'=> Carbon::now()
            ],
            [
                'title' => 'Movie 3',
                'summary' => 'Movie 3',
                'cover_image' => null,
                'user_id' => 3,
                'genre_id' => 3,
                'author_id' => 3,
                'created_at'=> Carbon::now(),
                'updated_at'=> Carbon::now()
            ],
            [
                'title' => 'Movie 4',
                'summary' => 'Movie 4',
                'cover_image' => null,
                'user_id' => 2,
                'genre_id' => 4,
                'author_id' => 4,
                'created_at'=> Carbon::now(),
                'updated_at'=> Carbon::now()
            ],
            [
                'title' => 'Movie 5',
                'summary' => 'Movie 5',
                'cover_image' => null,
                'user_id' => 3,
                'genre_id' => 5,
                'author_id' => 5,
                'created_at'=> Carbon::now(),
                'updated_at'=> Carbon::now()
            ],
            [
                'title' => 'Movie 6',
                'summary' => 'Movie 6',
                'cover_image' => null,
                'user_id' => 2,
                'genre_id' => 6,
                'author_id' => 6,
                'created_at'=> Carbon::now(),
                'updated_at'=> Carbon::now()
            ],
            [
                'title' => 'Movie 7',
                'summary' => 'Movie 7',
                'cover_image' => null,
                'user_id' => 3,
                'genre_id' => 7,
                'author_id' => 7,
                'created_at'=> Carbon::now(),
                'updated_at'=> Carbon::now()
            ],
            [
                'title' => 'Movie 8',
                'summary' => 'Movie 8',
                'cover_image' => null,
                'user_id' => 1,
                'genre_id' => 1,
                'author_id' => 1,
                'created_at'=> Carbon::now(),
                'updated_at'=> Carbon::now()
            ],
            [
                'title' => 'Movie 9',
                'summary' => 'Movie 9',
                'cover_image' => null,
                'user_id' => 1,
                'genre_id' => 1,
                'author_id' => 1,
                'created_at'=> Carbon::now(),
                'updated_at'=> Carbon::now()
            ],
            [
                'title' => 'Movie 10',
                'summary' => 'Movie 10',
                'cover_image' => null,
                'user_id' => 1,
                'genre_id' => 1,
                'author_id' => 1,
                'created_at'=> Carbon::now(),
                'updated_at'=> Carbon::now()
            ],
            [
                'title' => 'Movie 11',
                'summary' => 'Movie 11',
                'cover_image' => null,
                'user_id' => 1,
                'genre_id' => 1,
                'author_id' => 1,
                'created_at'=> Carbon::now(),
                'updated_at'=> Carbon::now()
            ],
            [
                'title' => 'Movie 12',
                'summary' => 'Movie 12',
                'cover_image' => null,
                'user_id' => 1,
                'genre_id' => 1,
                'author_id' => 1,
                'created_at'=> Carbon::now(),
                'updated_at'=> Carbon::now()
            ],
        ];
        Movie::insert($movies);
    }
}
