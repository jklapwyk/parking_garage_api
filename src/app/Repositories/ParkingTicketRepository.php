<?php

namespace App\Repositories;

use App\Models\ParkingTicket;

class ParkingTicketRepository implements ParkingTicketRepositoryInterface
{

    /**
     * Create Parking Ticket with creation optional creation date. Setting the creation date is mainly for testing purposes.
     * @param Carbon date
     * @return ParkingTicket
     */
    public function createParkingTicket( $creationDate = null ){

        $parkingTicket = new ParkingTicket;

        if( isset( $creationDate ) ){
          $parkingTicket->created_at = $creationDate->format('Y-m-d H:i:s');

        }

        $parkingTicket->save();

        return $parkingTicket;

    }

}
