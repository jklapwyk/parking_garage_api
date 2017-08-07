<?php

namespace App\Repositories;


interface ParkingVenueRepositoryInterface
{
    /**
     * Create Parking Venu Queue Model
     * @param User Id
     * @param Parking Venue Id
     * @return ParkingVenueQueue
     */
    public function createParkingVenueQueue( $userId, $parkingVenueId );
}
