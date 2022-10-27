<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tag;
use Carbon\Carbon;

class TagTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tags = [
            [
                'name' => 'Tag 1',
                'created_at'=> Carbon::now(),
                'updated_at'=> Carbon::now() 
            ],
            [
                'name' => 'Tag 2',
                'created_at'=> Carbon::now(),
                'updated_at'=> Carbon::now() 
            ],
            [
                'name' => 'Tag 3',
                'created_at'=> Carbon::now(),
                'updated_at'=> Carbon::now() 
            ]
        ];

        Tag::insert($tags);      
    }
}
