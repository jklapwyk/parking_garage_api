<?php

namespace App\Repositories;

use App\Models\UserParkingTicket;


class UserParkingTicketRepository implements UserParkingTicketRepositoryInterface
{

    /**
     * Create User Parking Ticket
     * @param User Id
     * @param Parking Ticket Id
     * @param Parking Venue Id
     * @return UserParkingTicket
     */
    public function createUserParkingTicket( $userId, $parkingTicketId, $parkingVenueId ){

        $userParkingTicket = new UserParkingTicket;

        $userParkingTicket->user_id = $userId;
        $userParkingTicket->parking_ticket_id = $parkingTicketId;
        $userParkingTicket->parking_venue_id = $parkingVenueId;

        $userParkingTicket->save();


        return $userParkingTicket;

    }

}
