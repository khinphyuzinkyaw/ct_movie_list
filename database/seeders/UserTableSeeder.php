<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = [
            [
                'name' => 'testUserOne',
                'email' => 'testUserOne@gmail.com',
                'password' => Hash::make('testUserOne'),
                'created_at'=> Carbon::now(),
                'updated_at'=> Carbon::now()
            ],
            [
                'name' => 'testUserTwo',
                'email' => 'testUserTwo@gmail.com',
                'password' => Hash::make('testUserTwo'),
                'created_at'=> Carbon::now(),
                'updated_at'=> Carbon::now()
            ],
            [
                'name' => 'testUserThree',
                'email' => 'testUserThree@gmail.com',
                'password' => Hash::make('testUserThree'),
                'created_at'=> Carbon::now(),
                'updated_at'=> Carbon::now()
            ],
        ];
        User::insert($user);
    }
}
