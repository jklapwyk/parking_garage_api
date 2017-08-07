<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder {

    /**
    * Create a number of random users
    */
    public function run()
    {
        factory(User::class, 2)->create();
    }

}
