<?php

namespace App\Services;
// Declare the interface 'iTemplate'

use App\Models\ParkingVenueQueue;
use App\Models\ParkingVenue;
use App\Models\User;

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

            if( isset($user) ){

                \Log::info("Notify USER = ".$user->first_name );

                \Mail::send('emails.notification', ['user' => $user], function ($m) use ($user) {

                    $m->from('no-reply@parkingapi.com', 'Parking Api - No Reply');

                    \Log::info("Email = ".$user->email);

                    $m->to($user->email, $user->first_name." ".$user->last_name )->subject('Parking Lot Available');

                });
            }
        }




    }

}
