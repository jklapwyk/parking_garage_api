<?php

namespace App\Repositories;


interface UserParkingTicketRepositoryInterface
{
    public function createUserParkingTicket( $userId, $parkingTicketId, $parkingVenueId );
}
