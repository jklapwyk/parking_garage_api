<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Response;
use App\Models\User;
use App\Models\ParkingTicket;

class UserControllerTest extends TestCase
{

    use DatabaseMigrations;

    public function testCreateUser()
    {

        $data = [
                  'data'=>[
                    'type'=>'user',
                    'attributes'=>[
                      'first_name'=>'jamie',
                      'last_name'=>'klapwyk',
                      'email'=>'jklapwyk@gmail.com',
                      'password'=>'some_password'
                    ]
                  ]
                ];

        $response = $this->json('POST', '/api/createUser', $data );

        $response->assertStatus(201);

        $this->assertSuccessJSONResponse( $response );

        $response->assertJsonFragment([
                 'type' => 'user',
                 'first_name' => 'jamie',
                 'last_name' => 'klapwyk',
                 'email' => 'jklapwyk@gmail.com'
             ]);

    }

    public function testCreateUserWithDuplicateEmail()
    {

        $data = [
                  'data'=>[
                    'type'=>'user',
                    'attributes'=>[
                      'first_name'=>'jamie',
                      'last_name'=>'klapwyk',
                      'email'=>'jklapwyk@gmail.com',
                      'password'=>'some_password'
                    ]
                  ]
                ];

        $this->json('POST', '/api/createUser', $data );

        $response = $this->json('POST', '/api/createUser', $data );

        $response->assertStatus(400);

        $this->assertErrorJSONResponse( $response, 400, 4 );

    }



    public function testAddUserToParkingVenueQueue()
    {
        $userRepository = resolve('App\Repositories\UserRepositoryInterface');
        $user = $userRepository->createUser();


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

        $response->assertStatus(201);

        $this->assertSuccessJSONResponse( $response );

        $response->assertJsonFragment([
                 'type' => 'parking_vendor_queue',
                 'user_id' => $user->id,
                 'parking_vendor_id' => '1'
             ]);

    }

    public function testAddUserToParkingVenueQueueUserAlreadyAdded()
    {
        $userRepository = resolve('App\Repositories\UserRepositoryInterface');
        $user = $userRepository->createUser();

        $parkingVenueRepository = resolve('App\Repositories\ParkingVenueRepositoryInterface');
        $parkingVenueRepository->createParkingVenueQueue( $user->id, 1 );

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

        $response->assertStatus(400);

        $this->assertErrorJSONResponse( $response, 400, 6 );

    }
}
