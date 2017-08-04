<?php

namespace App\Services;

use App\Repositories\UserRepositoryInterface;


class ParkingTicketService implements ParkingTicketServiceInterface
{

    protected $userRepository;

    public function __construct( UserRepositoryInterface $userRepository ){
        $this->userRepository = $userRepository;
    }

    public function createParkingTicket( $parkingVenueId, $userId = null ){

        \Log::info("CREATE PARKING TICKET>>>>");
        if( $userId == null ){

            $this->userRepository->createUser();

        }

        

    }


    public function acceptParkingTicket( $parkingVenueId, $parkingTicketId ){



    }

}
