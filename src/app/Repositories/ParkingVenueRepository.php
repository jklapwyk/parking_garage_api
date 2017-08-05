<?php

namespace App\Repositories;

use App\Models\User;
use Webpatser\Uuid\Uuid;
use App\Models\ParkingVenueQueue;


class ParkingVenueRepository implements ParkingVenueRepositoryInterface
{

    public function createParkingVenueQueue( $userId, $parkingVenueId )
    {
        $parkingVenueQueue = new ParkingVenueQueue;
        $parkingVenueQueue->user_id = $userId;
        $parkingVenueQueue->parking_venue_id = $parkingVenueId;
        $parkingVenueQueue->save();

        return $parkingVenueQueue;
    }

}
