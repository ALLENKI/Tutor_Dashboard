<?php

use Illuminate\Database\Seeder;

use Aham\Models\SQL\Topic;
use Aham\Models\SQL\User;

class UsersTableSeeder extends Seeder
{
    public function run()
    {

        $faker = Faker\Factory::create();

        for ($i=0; $i < 100; $i++) { 
            
            $data = [
                'name' => $faker->name,
                'email' => $faker->safeEmail,
                'interested_in' => 'user',
                'password' => 'password'
            ];

            Sentinel::register($data);
        }

    }
}
