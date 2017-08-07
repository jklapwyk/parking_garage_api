<?php

namespace App\Repositories;


interface ParkingTicketRepositoryInterface
{
    /**
     * Create Parking Ticket with creation optional creation date. Setting the creation date is mainly for testing purposes.
     * @param Carbon date
     * @return ParkingTicket
     */
    public function createParkingTicket( $creationDate = null );
}
