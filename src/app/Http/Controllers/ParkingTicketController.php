<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use App\Services\ParkingTicketServiceInterface;

class ParkingTicketController extends BaseController
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
        $parkingVenueId = $data['parkingVenueId'];

        $userId = null;

        if( isset($data['userId'])){
          $userId = $data['userId'];
        }

        $this->parkingTicketService->createParkingTicket( $parkingVenueId, $userId );


        return response()->json([
            'name' => 'test'
            ]);
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
