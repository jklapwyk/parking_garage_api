<?php

namespace App\Repositories;


interface UserRepositoryInterface
{
    public function createUser( $firstName = null, $lastName = null, $email = null, $password = null );
}
