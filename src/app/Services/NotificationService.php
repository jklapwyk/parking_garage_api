<?php

namespace App\Services;
// Declare the interface 'iTemplate'

use App\Models\ParkingVenueQueue;
use App\Models\ParkingVenue;
use App\Models\User;
use App\Mail\LotAvailable;

class NotificationService implements NotificationServiceInterface
{

    public function notifyUserFreeLot( $parkingVenueId )
    {

        $parkingVenue = ParkingVenue::find($parkingVenueId);
        if( !isset($parkingVenue) ){
            return $this->sendErrorResponse( 404, 9 );
        }

        $parkingVenueQueue = ParkingVenueQueue::first();

        if( isset( $parkingVenueQueue ) ){

            $user = User::find( $parkingVenueQueue->user_id );

            if( isset($user) && isset( $user->email ) ){

                $userName = $user->email;

                if( isset( $user->first_name ) ){
                    $userName = $user->first_name;
                }

                if( isset( $user->last_name ) ){
                    $userName .= " ".$user->last_name;
                }



                \Mail::to($user->email, $userName)->send(new LotAvailable($userName));



            }
        }




    }

}
