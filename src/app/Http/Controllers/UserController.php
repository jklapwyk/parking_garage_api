<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class UserController extends BaseController
{

    public function createUser(Request $request )
    {

        \Log::info("parkingVenueId = ".$parkingVenueId."    userId = ".$userId);

        return response()->json([
            'name' => 'test'
            ]);
    }

    public function addUserToParkingVendorQueue(Request $request )
    {

        \Log::info("parkingVenueId = ".$parkingVenueId."    userId = ".$userId);

        return response()->json([
            'name' => 'test'
            ]);
    }
}