<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('createParkingTicket', 'ParkingTicketController@createParkingTicket');

Route::get('requestPriceForTicket/{parkingTicketId}', 'ParkingTicketController@requestPriceForTicket');

Route::patch('payTicket/{parkingTicketId}', 'ParkingTicketController@payTicket');

Route::patch('acceptTicket/{parkingTicketId}', 'ParkingTicketController@acceptTicket');

Route::post('createUser', 'UserController@createUser');

Route::post('addUserToParkingVendorQueue', 'ParkingVenueController@addUserToParkingVendorQueue');
