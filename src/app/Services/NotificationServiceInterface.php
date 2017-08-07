<?php

namespace App\Services;
// Declare the interface 'iTemplate'
interface NotificationServiceInterface
{

    /**
     * Notify a user that a free lot is available via email
     * @param ParkingVenueId
     */
    public function notifyUserFreeLot( $parkingVenueId );

}
