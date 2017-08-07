<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Response;
use App\Models\User;
use App\Models\ParkingTicket;
use App\Models\ParkingVenue;
use App\Mail\LotAvailable;

class ParkingVenueControllerTest extends TestCase
{
    use DatabaseMigrations;

    /**
    * Test adding user to Parking venue queue
    */
    public function testAddUserToParkingVenueQueue()
    {
        //Create User
        $userRepository = resolve('App\Repositories\UserRepositoryInterface');
        $user = $userRepository->createUser();

        //Add User to Parking Venue Queue
        $data = [
                  'data'=>[
                    'type'=>'parking_vendor_queue',
                    'attributes'=>[
                      'user_id'=>$user->id,
                      'parking_venue_id'=>'1'
                    ]
                  ]
                ];
        $response = $this->json('POST', '/api/addUserToParkingVendorQueue', $data );

        //check for successful response
        $response->assertStatus(201);
        $this->assertSuccessJSONResponse( $response );
        $response->assertJsonFragment([
                 'type' => 'parking_vendor_queue',
                 'user_id' => $user->id,
                 'parking_vendor_id' => '1'
             ]);

    }


    /**
    * Test adding user to Parking venue queue when a parking venue queue object already exists for the user.
    */
    public function testAddUserToParkingVenueQueueUserAlreadyAdded()
    {
        //Create User
        $user = factory(User::class, 1)->create()[0];

        //Create Parking venue queue for user
        $parkingVenueRepository = resolve('App\Repositories\ParkingVenueRepositoryInterface');
        $parkingVenueRepository->createParkingVenueQueue( $user->id, 1 );

        //try to create another parking venue queue for the user
        $data = [
                  'data'=>[
                    'type'=>'parking_vendor_queue',
                    'attributes'=>[
                      'user_id'=>$user->id,
                      'parking_venue_id'=>1
                    ]
                  ]
                ];

        //check for failuer response
        $response = $this->json('POST', '/api/addUserToParkingVendorQueue', $data );
        $response->assertStatus(400);
        $this->assertErrorJSONResponse( $response, 400, 6 );

    }

    /**
    * Test adding user to Parking venue queue asserting a notification by email because the Parking Venue Lot is not full.
    */
    public function testAvailableLotNotificationImmediately()
    {
        //Check sending of mail
        \Mail::fake();

        //Create user
        $user = factory(User::class, 1)->create()[0];

        //Add user to parking venue queue
        $data = [
                  'data'=>[
                    'type'=>'parking_vendor_queue',
                    'attributes'=>[
                      'user_id'=>$user->id,
                      'parking_venue_id'=>1
                    ]
                  ]
                ];
        $response = $this->json('POST', '/api/addUserToParkingVendorQueue', $data );

        //since the parking venue should not be full notification email should have been sent.
        \Mail::assertSent(LotAvailable::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email);
        });


    }

    /**
    * Test adding user to Parking venue queue to a full Parking venue and asserting a notification by email after a Parking Ticket
    * by the same Parking venue has just been accepted thus freeing up one spot.
    */
    public function testAvailableLotNotificationAfterAcceptTicket()
    {
        //Check sending of mail
        \Mail::fake();


        //Get Current Parking Venue
        $parkingVenueId = 1;
        $parkingVenue = ParkingVenue::find($parkingVenueId);

        //Create the number of parking ticket to fill the parking venue
        $parkingTicketService = resolve('App\Services\ParkingTicketServiceInterface');
        for( $i = 0; $i < $parkingVenue->total_lots; $i++ ){
            $parkingTicketId = $parkingTicketService->createParkingTicket( $parkingVenueId );
        }

        //Create user
        $user = factory(User::class, 1)->create()[0];

        //Create parkingVenue Queue
        $parkingVenueRepository = resolve('App\Repositories\ParkingVenueRepositoryInterface');
        $parkingVenueRepository->createParkingVenueQueue( $user->id, 1 );

        //since the parking venue should be full no notification should be sent.
        \Mail::assertNotSent(LotAvailable::class);


        //Accept one of the tickets
        $response = $this->json('PATCH', '/api/acceptTicket/'.$parkingTicketId, ['data'=>['parking_venue_id'=>'1']] );


        //

        //A notification email should be sent.
        \Mail::assertSent(LotAvailable::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email);
        });


    }
}
