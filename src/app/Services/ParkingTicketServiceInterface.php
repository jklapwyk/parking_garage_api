<?php

namespace App\Services;

interface ParkingTicketServiceInterface
{

    /**
     * Create Parking Ticket
     * @param ParkingVenueId
     * @param User Id
     * @param Creation Date
     * @return Parking Ticket Id
     */
    public function createParkingTicket( $parkingVenueId, $userId );

    /**
     * Final act of accepting the ticket is to delete the parking ticket to prevent users to using the same ticket
     * again.
     * @param ParkingVenueId
     * @param ParkingTicketId
     */
    public function acceptParkingTicket( $parkingVenueId, $parkingTicketId );

    /**
     * Get Price From Parking Ticket
     * @param ParkingTicket
     * @return Float price
     */
    public function getPriceFromParkingTicket( $parkingTicket );
}
