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

    public function testCreateTicket()
    {

        //Create parking ticket
        $response = $this->json('POST', '/api/createParkingTicket', ['data'=>['parking_venue_id'=>'1']] );

        //Check for successful response
        $this->assertParkingTicketSuccessResponse( $response );


    }

    public function assertParkingTicketSuccessResponse( $response )
    {
        //check for status of 201 and the json is correct
        $response->assertStatus(201);
        $this->assertSuccessJSONResponse( $response );
        $response->assertJsonFragment([
                 'type' => 'parking_ticket'
             ]);

    }

    public function testCreateTicketWithUser()
    {
        $user = factory(User::class)->make();

        $response = $this->json('POST', '/api/createParkingTicket', ['data'=>['parking_venue_id'=>'1', 'user_id'=>$user->id]] );

        $this->assertParkingTicketSuccessResponse( $response );

        $response->assertJsonFragment([
                 'type' => 'parking_ticket'
             ]);
    }

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

    public function testCreateTicketWithUserDoesNotExists()
    {

        $response = $this->json('POST', '/api/createParkingTicket', ['data'=>['parking_venue_id'=>'1', 'user_id'=>'999999999']] );

        $response->assertStatus(404);

        $this->assertErrorJSONResponse( $response, 404, 5 );

    }



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

    public function assertRequestPriceForTicketAtTime( $ticketCreationTime, $finalPrice )
    {
        //TODO Need to fill in this area

        $parkingTicketService = resolve('App\Services\ParkingTicketServiceInterface');

        $parkingTicketId = $parkingTicketService->createParkingTicket( 1, null, $ticketCreationTime );



        $url = '/api/requestPriceForTicket/'.(string)$parkingTicketId;

        \Log::info("PARKING TICKET url = ".$url."  parking ticket id ".$parkingTicketId);

        $response = $this->json('GET', $url );

        $this->assertSuccessJSONResponse( $response );

        $response->assertJsonFragment([
                 'type' => 'parking_ticket',
                 'price' => $finalPrice
             ]);

    }

    public function testRequestPriceForTicketDoesNotExist()
    {

        $response = $this->json('GET', '/api/requestPriceForTicket/555555' );

        $response->assertStatus(404);

        $this->assertErrorJSONResponse( $response, 404, 3 );

    }


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


    public function testPayTicketTicketDoesNotExist()
    {

        $response = $this->json('PATCH', '/api/payTicket/555555' );

        $response->assertStatus(404);

        $this->assertErrorJSONResponse( $response, 404, 3 );


    }





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


    public function testAcceptTicketTicketDoesNotExist()
    {

        $response = $this->json('PATCH', '/api/acceptTicket/555555', ['data'=>['parking_venue_id'=>'1']] );

        $response->assertStatus(404);

        $this->assertErrorJSONResponse( $response, 404, 3 );

    }



}
