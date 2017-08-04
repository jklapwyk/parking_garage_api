<?php

namespace App\Services;
// Declare the interface 'iTemplate'
interface ParkingTicketServiceInterface
{

    public function createParkingTicket( $parkingVenueId, $userId );
    public function acceptParkingTicket( $parkingVenueId, $parkingTicketId );

}
