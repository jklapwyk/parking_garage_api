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

        return response()->json([
            'name' => 'test'
            ]);
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
