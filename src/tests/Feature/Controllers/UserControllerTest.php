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

    /**
    * Test create user with information
    */
    public function testCreateUser()
    {

        //Create User with data
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

        //Check if response is successful
        $response->assertStatus(201);
        $this->assertSuccessJSONResponse( $response );
        $response->assertJsonFragment([
                 'type' => 'user',
                 'first_name' => 'jamie',
                 'last_name' => 'klapwyk',
                 'email' => 'jklapwyk@gmail.com'
             ]);

    }

    /**
    * Test create user with email that already exists.
    */
    public function testCreateUserWithDuplicateEmail()
    {

        //create user with data
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

        //Attempt to create user with same data
        $response = $this->json('POST', '/api/createUser', $data );

        //check if response is unsuccessful as email should be unique
        $response->assertStatus(400);
        $this->assertErrorJSONResponse( $response, 400, 4 );

    }




}
