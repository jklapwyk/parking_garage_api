<?php

namespace App\Repositories;

use App\Models\User;
use Webpatser\Uuid\Uuid;


class UserRepository implements UserRepositoryInterface
{
    public function createUser( $firstName = null, $lastName = null, $email = null, $password = null ){

        $user = new User;

        $isRegistered = false;

        if( !empty( $firstName ) ){
          $user->first_name = $firstName;
        }

        if( !empty( $lastName ) ){
          $user->last_name = $lastName;
        }

        if( !empty( $email ) ){
          $user->email = $email;
          $isRegistered = true;
        }

        if( !empty( $password ) ){
          $user->password = bcrypt($password);
          $isRegistered = true;
        }

        $user->user_hash = Uuid::generate()->string;

        $user->is_registered = $isRegistered;

        $user->save();

        return $user;

    }

}
