<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use App\Services\ParkingTicketServiceInterface;
use App\Models\User;
use App\Models\ParkingVenue;
use App\Models\ParkingTicket;
use App\Models\PriceTier;
use Carbon\Carbon;

class ParkingTicketController extends ApiController
{
    protected $parkingTicketService;

    public function __construct( ParkingTicketServiceInterface $parkingTicketService )
    {
        $this->parkingTicketService = $parkingTicketService;
    }

    public function createParkingTicket( Request $request )
    {

        //\Log::info("parkingVenueId = ".$parkingVenueId."    userId = ".$userId);
        $jsonData = $request->json()->all();

        $data = $jsonData['data'];
        $parkingVenueId = $data['parking_venue_id'];

        $userId = null;

        if( isset($data['user_id'])){
          $userId = $data['user_id'];
          $user = User::find($userId);

          if( !isset($user) ){
              return $this->sendErrorResponse( 404, 5 );
          }
        }

        $parkingVenue = ParkingVenue::find($parkingVenueId);

        $currentParkingTickets = $parkingVenue->userParkingTickets;

        if( $currentParkingTickets->count() >= $parkingVenue->total_lots ){
            return $this->sendErrorResponse( 403, 1 );
        }

        \Log::info("PARKING TICKETs COUNT =>  ".$currentParkingTickets->count());

        $parkingTicketId = $this->parkingTicketService->createParkingTicket( $parkingVenueId, $userId );

        \Log::info("PARKING TICKET id =>  ".$parkingTicketId);

        return $this->sendSuccessResponse( 201, [
            'type' => 'parking_ticket',
            'id' => $parkingTicketId
          ] );


    }

    public function requestPriceForTicket( Request $request, $parkingTicketId )
    {
        \Log::info("requestPriceForTicket = ".$parkingTicketId);

        $parkingTicket = ParkingTicket::find( $parkingTicketId );

        if( !isset($parkingTicket) ){
            return $this->sendErrorResponse( 404, 3 );
        }

        $finalPrice = $this->parkingTicketService->getPriceFromParkingTicket( $parkingTicket );



        return $this->sendSuccessResponse( 200, [
            'id' => $parkingTicket->id,
            'type' => 'parking_ticket',
            'attributes' => [
              'price' => $finalPrice->price,
              'currency_type' => $finalPrice->currencyType
            ]
          ] );
    }



    public function payTicket( Request $request, $parkingTicketId )
    {

        $parkingTicket = ParkingTicket::find( $parkingTicketId );

        if( !isset($parkingTicket) ){
            return $this->sendErrorResponse( 404, 3 );
        }

        $userParkingTicket = $parkingTicket->userParkingTicket;
        if( $userParkingTicket->is_paid ){
           return $this->sendErrorResponse( 400, 7 );
        }

        $jsonData = $request->json()->all();
        $data = $jsonData['data'];
        $paymentAmount = $data['payment_amount'];



        $parkingTicketPrice = $this->parkingTicketService->getPriceFromParkingTicket( $parkingTicket );

        \Log::info(" >> paymentAmount = ".$paymentAmount."  userParkingTicket ".$userParkingTicket);

        if( ( $userParkingTicket->total_payment + $paymentAmount ) >= $parkingTicketPrice->price ){



            $userParkingTicket->is_paid = 1;
            $userParkingTicket->total_payment = $parkingTicketPrice->price;
            $userParkingTicket->save();

            return $this->sendSuccessResponse( 200, [
                'id' => $parkingTicket->id,
                'type' => 'parking_ticket',
                'attributes' => [
                  'total_payment' => $parkingTicketPrice->price,
                  'is_paid' => $userParkingTicket->is_paid
                ]
              ] );

        } else {

          $userParkingTicket->total_payment = $userParkingTicket->total_payment + $paymentAmount;

          $meta = (object)array();
          $meta->price = $parkingTicketPrice->price;
          $meta->total_payment = $userParkingTicket->total_payment;
          $meta->currency_type = $parkingTicketPrice->currencyType;

          $userParkingTicket->save();

          return $this->sendErrorResponse( 402, 2, $meta );


        }


    }

    public function acceptTicket( Request $request, $parkingTicketId )
    {

        return response()->json([
            'name' => 'test'
            ]);
    }

}
