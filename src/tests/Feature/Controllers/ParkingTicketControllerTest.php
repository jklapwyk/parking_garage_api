<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Response;
use App\Models\User;
use App\Models\ParkingTicket;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Models\ParkingVenue;
use App\Services\ParkingTicketServiceInterface;

class ParkingTicketControllerTest extends TestCase
{
    use DatabaseMigrations;

    /**
    * Test create parking ticket
    */
    public function testCreateTicket()
    {

        //Create parking ticket
        $response = $this->json('POST', '/api/createParkingTicket', ['data'=>['parking_venue_id'=>'1']] );

        //Check for successful response
        $this->assertParkingTicketSuccessResponse( $response );


    }

    /**
    * generic assert for parking ticket JSON structure and response code
    */
    public function assertParkingTicketSuccessResponse( $response )
    {
        //check for status of 201 and the json is correct
        $response->assertStatus(201);
        $this->assertSuccessJSONResponse( $response );
        $response->assertJsonFragment([
                 'type' => 'parking_ticket'
             ]);

    }

    /**
    * Test create parking ticket with registered user
    */
    public function testCreateTicketWithUser()
    {
        $user = factory(User::class)->make();

        $response = $this->json('POST', '/api/createParkingTicket', ['data'=>['parking_venue_id'=>'1', 'user_id'=>$user->id]] );

        $this->assertParkingTicketSuccessResponse( $response );

        $response->assertJsonFragment([
                 'type' => 'parking_ticket'
             ]);
    }

    /**
    * Test create parking ticket when parking venue is full
    */
    public function testCreateTicketWithUserParkingVenueFull()
    {

        $parkingTicketService = resolve('App\Services\ParkingTicketServiceInterface');

        $parkingVenueId = 1;

        $parkingVenue = ParkingVenue::find($parkingVenueId);

        for( $i = 0; $i < $parkingVenue->total_lots; $i++ ){
          $parkingTicketService->createParkingTicket( $parkingVenueId );
        }


        $user = factory(User::class)->make();

        $response = $this->json('POST', '/api/createParkingTicket', ['data'=>['parking_venue_id'=>$parkingVenueId, 'user_id'=>$user->id]] );

        $response->assertStatus(403);

        $this->assertErrorJSONResponse( $response, 403, 1 );



    }

    /**
    * Test create parking ticket when user does not exist
    */
    public function testCreateTicketWithUserDoesNotExists()
    {

        $response = $this->json('POST', '/api/createParkingTicket', ['data'=>['parking_venue_id'=>'1', 'user_id'=>'999999999']] );

        $response->assertStatus(404);

        $this->assertErrorJSONResponse( $response, 404, 5 );

    }


    /**
    * Test get price for parking ticket for various parking ticket creation dates
    */
    public function testRequestPriceForTicket()
    {

        $this->assertRequestPriceForTicketAtTime( Carbon::now(), 0.00 );
        $this->assertRequestPriceForTicketAtTime( Carbon::now()->subHour(1), 3.00 );
        $this->assertRequestPriceForTicketAtTime( Carbon::now()->subHour(3), 4.50 );
        $this->assertRequestPriceForTicketAtTime( Carbon::now()->subHour(6), 6.75 );
        $this->assertRequestPriceForTicketAtTime( Carbon::now()->subHour(24), 10.13 );
        $this->assertRequestPriceForTicketAtTime( Carbon::now()->subHour(25), 13.13 );
        $this->assertRequestPriceForTicketAtTime( Carbon::now()->subHour(27), 14.63 );
        $this->assertRequestPriceForTicketAtTime( Carbon::now()->subHour(30), 16.88 );
        $this->assertRequestPriceForTicketAtTime( Carbon::now()->subHour(48), 20.26 );


    }

    /**
    * An assertions function that creates a parking ticket with the creation date defined. and then an assertion
    * that check that the predicted price matches the actual.
    */
    public function assertRequestPriceForTicketAtTime( $ticketCreationTime, $finalPrice )
    {
        $parkingTicketService = resolve('App\Services\ParkingTicketServiceInterface');

        $parkingTicketId = $parkingTicketService->createParkingTicket( 1, null, $ticketCreationTime );

        $url = '/api/requestPriceForTicket/'.(string)$parkingTicketId;

        $response = $this->json('GET', $url );

        $this->assertSuccessJSONResponse( $response );

        $response->assertJsonFragment([
                 'type' => 'parking_ticket',
                 'price' => $finalPrice
             ]);

    }

    /**
    * Test get price for parking ticket that does not exist
    */
    public function testRequestPriceForTicketDoesNotExist()
    {

        $response = $this->json('GET', '/api/requestPriceForTicket/555555' );

        $response->assertStatus(404);

        $this->assertErrorJSONResponse( $response, 404, 3 );

    }

    /**
    * Test paying for the ticket
    */
    public function testPayTicket()
    {
        $parkingTicketService = resolve('App\Services\ParkingTicketServiceInterface');

        $parkingTicketId = $parkingTicketService->createParkingTicket( 1, null, Carbon::now()->subHour(6) );

        $response = $this->json('PATCH', '/api/payTicket/'.$parkingTicketId, ['data'=>['payment_amount'=>6.75, 'currency_type' => 'CAD']] );

        $response->assertStatus(200);

        $this->assertSuccessJSONResponse( $response );

        $response->assertJsonFragment([
                 'type' => 'parking_ticket',
                 'total_payment' => 6.75,
                 'is_paid' => 1
             ]);

    }

    /**
    * Test paying for the ticket but with an amount lower than needed to pay the ticket in full.
    */
    public function testPayTicketFurtherPaymentRequired()
    {

        $parkingTicketService = resolve('App\Services\ParkingTicketServiceInterface');

        $parkingTicketId = $parkingTicketService->createParkingTicket( 1, null, Carbon::now()->subHour(6) );

        $response = $this->json('PATCH', '/api/payTicket/'.$parkingTicketId, ['data'=>['payment_amount'=>3.75, 'currency_type' => 'CAD']] );

        $response->assertStatus(402);

        $this->assertErrorJSONResponse( $response , 402, 2 );

        $response->assertJsonFragment([
                 'price' => 6.75,
                 'total_payment' => 3.75,
                 'currency_type' => 'CAD'
             ]);

    }


    /**
    * Test paying for the ticket if the ticket does not exist.
    */
    public function testPayTicketTicketDoesNotExist()
    {

        $response = $this->json('PATCH', '/api/payTicket/555555' );

        $response->assertStatus(404);

        $this->assertErrorJSONResponse( $response, 404, 3 );


    }




    /**
    * Test accepting the ticket
    */
    public function testAcceptTicket()
    {

        $parkingTicketService = resolve('App\Services\ParkingTicketServiceInterface');

        $parkingTicketId = $parkingTicketService->createParkingTicket( 1, null );


        $response = $this->json('PATCH', '/api/acceptTicket/'.$parkingTicketId, ['data'=>['parking_venue_id'=>'1']] );

        $response->assertStatus(200);

        $this->assertSuccessJSONResponse( $response );

        $response->assertJsonFragment([
                 'type' => 'parking_ticket',
                 'accepted' => 1
             ]);

    }

    /**
    * Test accepting the ticket that has not been paid in full.
    */
    public function testAcceptTicketFurtherPaymentRequired()
    {

        $parkingTicketService = resolve('App\Services\ParkingTicketServiceInterface');

        $parkingTicketId = $parkingTicketService->createParkingTicket( 1, null, Carbon::now()->subHour(6) );


        $response = $this->json('PATCH', '/api/acceptTicket/'.$parkingTicketId, ['data'=>['parking_venue_id'=>'1']] );

        $response->assertStatus(402);

        $this->assertErrorJSONResponse( $response, 402, 2 );

        $response->assertJsonFragment([
                'price' => 6.75,
                'total_payment' => "0.00",
                'currency_type' => 'CAD'
            ]);

    }


    /**
    * Test accepting the ticket that does not exist.
    */
    public function testAcceptTicketTicketDoesNotExist()
    {

        $response = $this->json('PATCH', '/api/acceptTicket/555555', ['data'=>['parking_venue_id'=>'1']] );

        $response->assertStatus(404);

        $this->assertErrorJSONResponse( $response, 404, 3 );

    }



}
