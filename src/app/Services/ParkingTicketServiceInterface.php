<?php

namespace App\Services;

interface ParkingTicketServiceInterface
{
    public function createParkingTicket( $parkingVenueId, $userId );
    public function acceptParkingTicket( $parkingVenueId, $parkingTicketId );
    public function getPriceFromParkingTicket( $parkingTicket );
}
