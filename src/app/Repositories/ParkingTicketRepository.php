<?php

namespace App\Repositories;

use App\Models\ParkingTicket;


class ParkingTicketRepository implements ParkingTicketRepositoryInterface
{
    public function createParkingTicket(){

        $parkingTicket = new ParkingTicket;

        $parkingTicket->save();

        return $parkingTicket;

    }

}
