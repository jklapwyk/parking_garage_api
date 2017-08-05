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

        $parkingTicketCreationDate = $parkingTicket->created_at;

        $nowDate = Carbon::now();

        $diffInSeconds = $nowDate->diffInSeconds($parkingTicket->created_at);

        \Log::info("creation date = ".$parkingTicket->created_at."  now = ".$nowDate." diff inseconds = ".$diffInSeconds);

        $userParkingTicket = $parkingTicket->userParkingTicket;

        $parkingVenue = $userParkingTicket->parkingVenue;

        $priceTiers = $parkingVenue->priceTiers;

        \Log::info(" parking venue id = ".$userParkingTicket->parking_venue_id);

        $sortedPriceTiers = $priceTiers->sortByDesc('max_duration_seconds');

        $finalPrice = $this->calculatePrice( $sortedPriceTiers, $diffInSeconds );

        \Log::info( "Final price = ".$finalPrice);


        $currencyType = $sortedPriceTiers->get(0)->currencyType->iso_code;

        return $this->sendSuccessResponse( 200, [
            'id' => $parkingTicket->id,
            'type' => 'parking_ticket',
            'attributes' => [
              'price' => $finalPrice,
              'currency_type' => $currencyType
            ]
          ] );
    }

    public function calculatePrice( $priceTiers, $seconds ){

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

    public function payTicket( Request $request, $parkingTicketId )
    {

        return response()->json([
            'name' => 'test'
            ]);
    }

    public function acceptTicket( Request $request, $parkingTicketId )
    {

        return response()->json([
            'name' => 'test'
            ]);
    }

}
