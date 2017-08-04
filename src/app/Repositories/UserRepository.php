<?php

namespace App\Repositories;

use App\Models\User;


class UserRepository implements UserRepositoryInterface
{
    //bcrypt($data['password']),

    //'user_hash' => Uuid::generate()->string,
    //'is_registered' => 1,

    public function createUser( $firstName = null, $lastName = null, $email = null, $password = null ){

        $user = new

    }

}
