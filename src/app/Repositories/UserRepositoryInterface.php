<?php

namespace App\Repositories;


interface UserRepositoryInterface
{
    /**
     * Create User
     * @param First Name
     * @param Last Name
     * @param Email
     * @param Password
     * @return UserParkingTicket
     */
    public function createUser( $firstName = null, $lastName = null, $email = null, $password = null );
}
