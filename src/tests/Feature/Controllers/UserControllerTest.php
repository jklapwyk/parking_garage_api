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

        $response->assertJson([
                 'type' => 'user',
                 'first_name' => 'jamie',
                 'last_name' => 'klapwyk',
                 'email' => 'jklapwyk@gmail.com'
             ]);

    }

    public function testCreateUserWithDuplicateEmail()
    {

        $user = factory(User::class)->make();

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

        $response->assertStatus(400);

        $this->assertErrorJSONResponse( $response );

        $response->assertJson([
                 'status' => '400',
                 'code' => '4',
                 'title' => 'Email Already Exists'
             ]);
    }



    public function testAddUserToParkingVenueQueue()
    {
        $user = factory(User::class)->make();

        $data = [
                  'data'=>[
                    'type'=>'parking_vendor_queue',
                    'attributes'=>[
                      'user_id'=>$user->id,
                      'parking_vendor_id'=>'1'
                    ]
                  ]
                ];

        $response = $this->json('POST', '/api/addUserToParkingVendorQueue', $data );

        $response->assertStatus(201);

        $this->assertSuccessJSONResponse( $response );

        $response->assertJson([
                 'type' => 'parking_vendor_queue',
                 'user_id' => $user->id,
                 'parking_vendor_id' => '1'
             ]);

    }

    public function testAddUserToParkingVenueQueueUserAlreadyAdded()
    {
        $user = factory(User::class)->make();

        $data = [
                  'data'=>[
                    'type'=>'parking_vendor_queue',
                    'attributes'=>[
                      'user_id'=>$user->id,
                      'parking_vendor_id'=>'1'
                    ]
                  ]
                ];

        $response = $this->json('POST', '/api/addUserToParkingVendorQueue', $data );

        $response->assertStatus(400);

        $this->assertErrorJSONResponse( $response );

        $response->assertJson([
                 'status' => '400',
                 'code' => '6',
                 'title' => 'User Already Added To Queue'
             ]);

    }
}
