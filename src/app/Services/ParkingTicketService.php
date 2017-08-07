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

    /**
     * Request various repositories and services
     *
     * @param UserRepositoryInterface
     * @param ParkingTicketRepositoryInterface
     * @param UserParkingTicketRepositoryInterface
     */
    public function __construct( UserRepositoryInterface $userRepository, ParkingTicketRepositoryInterface $parkingTicketRepository,  UserParkingTicketRepositoryInterface $userParkingTicketRepository ){

        $this->userRepository = $userRepository;
        $this->parkingTicketRepository = $parkingTicketRepository;
        $this->userParkingTicketRepository = $userParkingTicketRepository;

    }

    /**
     * Create Parking Ticket
     * @param ParkingVenueId
     * @param User Id
     * @param Creation Date
     * @return Parking Ticket Id
     */
    public function createParkingTicket( $parkingVenueId, $userId = null, $creationDate = null ){


        try {

            DB::beginTransaction();


            if( !isset( $userId ) ){
              $user = $this->userRepository->createUser();
              $userId = $user->id;
            }

            $parkingTicket = $this->parkingTicketRepository->createParkingTicket( $creationDate );

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

    /**
     * Final act of accepting the ticket is to delete the parking ticket to prevent users to using the same ticket
     * again.
     * @param ParkingVenueId
     * @param ParkingTicketId
     */
    public function acceptParkingTicket( $parkingVenueId, $parkingTicketId ){

        $parkingTicket->delete();

    }


    /**
     * Get Price From Parking Ticket
     * @param ParkingTicket
     * @return Float price
     */
    public function getPriceFromParkingTicket( $parkingTicket ){

        //Get parking ticket date
        $parkingTicketCreationDate = $parkingTicket->created_at;

        //Get now date
        $nowDate = Carbon::now();

        //get difference in seconds
        $diffInSeconds = $nowDate->diffInSeconds($parkingTicket->created_at);

        //Get price tiers for the parking venue
        $userParkingTicket = $parkingTicket->userParkingTicket;
        $parkingVenue = $userParkingTicket->parkingVenue;
        $priceTiers = $parkingVenue->priceTiers;

        //Sort price tiers descending by max duration in seconds value
        $sortedPriceTiers = $priceTiers->sortByDesc('max_duration_seconds');

        //Run recursive function to calculate the price.
        $price = $this->calculatePrice( $sortedPriceTiers, $diffInSeconds );

        //Get currency type CAD / USD based on the first price tier of the parking venue. This assumes that Price tiers per parking venue would not differ.
        $currencyType = $sortedPriceTiers->get(0)->currencyType->iso_code;

        //Return with object that has price and currency type.
        $finalPrice = (object)array();
        $finalPrice->price = $price;
        $finalPrice->currencyType = $currencyType;
        return $finalPrice;

    }

    /**
     * Recursive function - Calculate price based on the maximum seconds of the largest price tier.
     * If the duration in seconds is longer than the maximum Price Tier append the value and calculate the remainder
     * by calling this function again. Continue until no remainder of seconds is left.
     * @param ParkingTicket
     * @return Float price
     */
    protected function calculatePrice( $priceTiers, $seconds ){

        $accumulatedPrice = 0;

        for( $i=$priceTiers->count() - 1; $i >= 0; $i-- ){

            $priceTier = $priceTiers->get($i);

            if( $seconds >= $priceTier->max_duration_seconds ){

                $accumulatedPrice += $priceTier->price;
                if( $i == $priceTiers->count() - 1 ){
                    $accumulatedPrice += $this->calculatePrice( $priceTiers, $seconds - $priceTier->max_duration_seconds );
                }
                break;
            }
        }
        return $accumulatedPrice;

    }

}
