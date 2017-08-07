<?php

namespace App\Repositories;


interface UserParkingTicketRepositoryInterface
{

    /**
     * Create User Parking Ticket
     * @param User Id
     * @param Parking Ticket Id
     * @param Parking Venue Id
     * @return UserParkingTicket
     */
    public function createUserParkingTicket( $userId, $parkingTicketId, $parkingVenueId );
}
