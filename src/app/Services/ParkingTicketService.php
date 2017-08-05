<?php

namespace App\Services;

use App\Repositories\UserRepositoryInterface;
use App\Repositories\ParkingTicketRepositoryInterface;
use App\Repositories\UserParkingTicketRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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

    public function createParkingTicket( $parkingVenueId, $userId = null, $creationDate = null ){


        try {

          //DB::beginTransaction();


          if( !isset( $userId ) ){
              $user = $this->userRepository->createUser();
              $userId = $user->id;
          }

          $parkingTicket = $this->parkingTicketRepository->createParkingTicket( $creationDate );

          $userParkingTicket = $this->userParkingTicketRepository->createUserParkingTicket( $userId, $parkingTicket->id, $parkingVenueId );

          \Log::info("PARK>>>> ".$parkingTicket->id);

          //DB::commit();

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
