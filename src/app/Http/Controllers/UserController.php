<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class UserController extends ApiController
{

    public function createUser(Request $request )
    {

        return response()->json([
            'name' => 'test'
            ]);
    }


    public function addUserToParkingVendorQueue(Request $request )
    {

        return response()->json([
            'name' => 'test'
            ]);
    }
}
