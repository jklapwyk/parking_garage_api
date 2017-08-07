<?php

namespace App\Services;
// Declare the interface 'iTemplate'

use App\Models\ParkingVenueQueue;
use App\Models\ParkingVenue;
use App\Models\User;
use App\Mail\LotAvailable;
use Illuminate\Support\Facades\DB;

class NotificationService implements NotificationServiceInterface
{

    /**
     * Notify a user that a free lot is available via email
     * @param ParkingVenueId
     */

    public function notifyUserFreeLot( $parkingVenueId )
    {

        try {

            DB::beginTransaction();

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

                $parkingVenueQueue->delete();
            }

            DB::commit();

        } catch (\Exception $e) {
            // An error occured; cancel the transaction...
            DB::rollback();

            // and throw the error again.
            return null;
        }

    }

}
