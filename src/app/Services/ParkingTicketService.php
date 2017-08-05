<?php

namespace App\Services;

use App\Repositories\UserRepositoryInterface;
use App\Repositories\ParkingTicketRepositoryInterface;
use App\Repositories\UserParkingTicketRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Model\ParkingTicket;

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

        

        $parkingTicket->delete();

    }


    public function getPriceFromParkingTicket( $parkingTicket ){

      $parkingTicketCreationDate = $parkingTicket->created_at;

      $nowDate = Carbon::now();

      $diffInSeconds = $nowDate->diffInSeconds($parkingTicket->created_at);

      \Log::info("creation date = ".$parkingTicket->created_at."  now = ".$nowDate." diff inseconds = ".$diffInSeconds);

      $userParkingTicket = $parkingTicket->userParkingTicket;

      $parkingVenue = $userParkingTicket->parkingVenue;

      $priceTiers = $parkingVenue->priceTiers;

      \Log::info(" parking venue id = ".$userParkingTicket->parking_venue_id);

      $sortedPriceTiers = $priceTiers->sortByDesc('max_duration_seconds');

      $price = $this->calculatePrice( $sortedPriceTiers, $diffInSeconds );

      \Log::info( "Final price = ".$price);

      $currencyType = $sortedPriceTiers->get(0)->currencyType->iso_code;

      $finalPrice = (object)array();

      $finalPrice->price = $price;
      $finalPrice->currencyType = $currencyType;

      return $finalPrice;

    }

    protected function calculatePrice( $priceTiers, $seconds ){

      $accumulatedPrice = 0;

      for( $i=$priceTiers->count() - 1; $i >= 0; $i-- ){

          $priceTier = $priceTiers->get($i);

          \Log::info( ">>> price  = ".$priceTier->price." max duration in seconds ".$priceTier->max_duration_seconds);


          if( $seconds >= $priceTier->max_duration_seconds ){

              \Log::info( "IN");

              $accumulatedPrice += $priceTier->price;

              if( $i == $priceTiers->count() - 1 ){
                  \Log::info("RECURSIVE");
                  $accumulatedPrice += $this->calculatePrice( $priceTiers, $seconds - $priceTier->max_duration_seconds );
              }

              break;
          }


      }

      return $accumulatedPrice;

    }

}
