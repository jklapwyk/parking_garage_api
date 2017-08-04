<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class ParkingTicketController extends BaseController
{

    public function createParkingTicket(Request $request )
    {

        \Log::info("parkingVenueId = ".$parkingVenueId."    userId = ".$userId);

        return response()->json([
            'name' => 'test'
            ]);
    }

    public function requestPriceForTicket( Request $request, $parkingTicketId )
    {

        \Log::info("parkingVenueId = ".$parkingVenueId."    userId = ".$userId);

        return response()->json([
            'name' => 'test'
            ]);
    }

    public function payTicket( Request $request, $parkingTicketId )
    {

        \Log::info("parkingVenueId = ".$parkingVenueId."    userId = ".$userId);

        return response()->json([
            'name' => 'test'
            ]);
    }

    public function acceptTicket( Request $request, $parkingTicketId )
    {

        \Log::info("parkingVenueId = ".$parkingVenueId."    userId = ".$userId);

        return response()->json([
            'name' => 'test'
            ]);
    }

}
