<?php

namespace App\Services;

use App\Repositories\UserRepositoryInterface;
use App\Repositories\ParkingTicketRepositoryInterface;
use App\Repositories\UserParkingTicketRepositoryInterface;
use Illuminate\Support\Facades\DB;

class ParkingTicketService implements ParkingTicketServiceInterface
{

    protected $userRepository;
    protected $parkingTicketRepository;
    protected $userParkingTicketRepository;

    public function __construct( UserRepositoryInterface $userRepository, ParkingTicketRepositoryInterface $parkingTicketRepository,  UserParkingTicketRepositoryInterface $userParkingTicketRepository ){

        $this->userRepository = $userRepository;
        $this->parkingTicketRepository = $parkingTicketRepository;
        $this->userParkingTicketRepository = $userParkingTicketRepository;

    }

    public function createParkingTicket( $parkingVenueId, $userId = null ){


        try {

          DB::beginTransaction();

          \Log::info("CREATE PARKING TICKET>>>>");
          if( !isset( $userId ) ){
              $user = $this->userRepository->createUser();
              $userId = $user->id;
          }

          $parkingTicket = $this->parkingTicketRepository->createParkingTicket();

          $userParkingTicket = $this->userParkingTicketRepository->createUserParkingTicket( $userId, $parkingTicket->id, $parkingVenueId );

          DB::commit();

          return $parkingTicket->id;

        } catch (\Exception $e) {
          // An error occured; cancel the transaction...
          DB::rollback();

          // and throw the error again.
          return null;
        }





    }


    public function acceptParkingTicket( $parkingVenueId, $parkingTicketId ){



    }

}
