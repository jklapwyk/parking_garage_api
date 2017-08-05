<?php

namespace App\Repositories;


interface ParkingVenueRepositoryInterface
{
    public function createParkingVenueQueue( $userId, $parkingVenueId );
}
