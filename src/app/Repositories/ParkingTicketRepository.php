<?php

namespace App\Repositories;

use App\Models\ParkingTicket;


class ParkingTicketRepository implements ParkingTicketRepositoryInterface
{
    public function createParkingTicket( $creationDate = null){

        $parkingTicket = new ParkingTicket;

        if( isset( $creationDate ) ){
          $parkingTicket->created_at = $creationDate->format('Y-m-d H:i:s');

          \Log::info("CREATE PARKING TICKET>>>> ".$creationDate->format('Y-m-d H:i:s'));

        }

        $parkingTicket->save();

        return $parkingTicket;

    }

}
