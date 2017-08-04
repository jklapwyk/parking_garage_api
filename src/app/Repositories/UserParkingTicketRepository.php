<?php

namespace App\Repositories;

use App\Models\UserParkingTicket;


class UserParkingTicketRepository implements UserParkingTicketRepositoryInterface
{
    public function createUserParkingTicket( $userId, $parkingTicketId, $parkingVenueId ){

        $userParkingTicket = new UserParkingTicket;

        $userParkingTicket->user_id = $userId;
        $userParkingTicket->parking_ticket_id = $parkingTicketId;
        $userParkingTicket->parking_venue_id = $parkingVenueId;

        $userParkingTicket->save();
        

        return $userParkingTicket;

    }

}
