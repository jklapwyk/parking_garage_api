<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use App\Repositories\UserRepositoryInterface;
use App\Repositories\ParkingVenueRepositoryInterface;
use App\Services\NotificationServiceInterface;
use App\Models\User;
use App\Models\ParkingVenueQueue;
use App\Models\ParkingVenue;

class ParkingVenueController extends ApiController
{
    protected $userRepository;
    protected $parkingVenueRepository;
    protected $notificationService;

    /**
     * Request various repositories and services
     *
     * @param UserRepositoryInterface
     * @param ParkingVenueRepositoryInterface
     * @param NotificationServiceInterface
     */
    public function __construct( UserRepositoryInterface $userRepository, ParkingVenueRepositoryInterface $parkingVenueRepository, NotificationServiceInterface $notificationService )
    {
        $this->userRepository = $userRepository;
        $this->parkingVenueRepository = $parkingVenueRepository;
        $this->notificationService = $notificationService;
    }

    /**
     * Add User to Parking Ticket
     *
     * @param Request
     * @return Response
     */
    public function addUserToParkingVendorQueue(Request $request )
    {

        $jsonData = $request->json()->all();
        $data = $jsonData['data'];
        $attributes = $data['attributes'];
        $userId = $attributes['user_id'];
        $parkingVenueId = $attributes['parking_venue_id'];

        $parkingVenue = ParkingVenue::find($parkingVenueId);
        if( !isset($parkingVenue) ){
            return $this->sendErrorResponse( 404, 9 );
        }

        $user = User::find($userId);

        if( !isset($user) ){
            return $this->sendErrorResponse( 404, 5 );
        }

        $parkingVenueQueue = ParkingVenueQueue::where('user_id', $userId)->where('parking_venue_id', $parkingVenueId)->first();

        if( isset($parkingVenueQueue) ){
            return $this->sendErrorResponse( 400, 6 );
        }

        $parkingVenueQueue = $this->parkingVenueRepository->createParkingVenueQueue( $userId, $parkingVenueId );

        $currentParkingTickets = $parkingVenue->userParkingTickets;

        if( $currentParkingTickets->count() < $parkingVenue->total_lots ){
            $this->notificationService->notifyUserFreeLot( $parkingVenueId );
        }

        return $this->sendSuccessResponse( 201, [
            'type' => 'parking_vendor_queue',
            'id' => $parkingVenueQueue->id,
            'attributes' => [
              'user_id' => $parkingVenueQueue->user_id,
              'parking_vendor_id' => $parkingVenueQueue->parking_venue_id,
            ]
          ] );
    }
}
