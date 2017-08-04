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

class ParkingTicketControllerTest extends TestCase
{
    public function testCreateTicket()
    {
        $response = $this->json('POST', '/api/createParkingTicket', ['data'=>['parkingVenueId'=>'1']] );

        $this->assertParkingTicketSuccessResponse( $response );

    }

    public function assertParkingTicketSuccessResponse( $response )
    {
        $response->assertStatus(201);

        $this->assertSuccessJSONResponse( $response );

        $response->assertJson([
                 'type' => 'parking_ticket',
                 'id' => '*'
             ]);
    }

    public function testCreateTicketWithUser()
    {
        $user = factory(User::class)->make();

        $response = $this->json('POST', '/api/createParkingTicket', ['data'=>['parkingVenueId'=>'1', 'userId'=>$user->id]] );

        $this->assertParkingTicketSuccessResponse( $response );
    }

    public function testCreateTicketWithUserParkingVenueFull()
    {

        $user = factory(User::class)->make();

        $response = $this->json('POST', '/api/createParkingTicket', ['data'=>['parkingVenueId'=>'1', 'userId'=>$user->id]] );

        $response->assertStatus(403);

        $this->assertErrorJSONResponse( $response );

        $response->assertJson([
                 'status' => '403',
                 'code' => '1',
                 'title' => 'Parking Venue Full'
             ]);
    }

    public function testCreateTicketWithUserDoesNotExists()
    {

        $response = $this->json('POST', '/api/createParkingTicket', ['data'=>['parkingVenueId'=>'1', 'userId'=>'229']] );

        $response->assertStatus(404);

        $this->assertErrorJSONResponse( $response );

        $response->assertJson([
                 'status' => '404',
                 'code' => '5',
                 'title' => 'User Not Found'
             ]);
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

        $user = factory(User::class)->make();

        $parkingTicket = factory(ParkingTicket::class)->make();

        \Log::info("PARKING TICKET ID = ".$parkingTicket->id);

        $response = $this->json('GET', '/api/requestPriceForTicket/'.$parkingTicket->id );

        $this->assertSuccessJSONResponse( $response );

        $response->assertJson([
                 'type' => 'parking_ticket',
                 'price' => $finalPrice
             ]);

    }

    public function testRequestPriceForTicketDoesNotExist()
    {

        $response = $this->json('GET', '/api/requestPriceForTicket/555555' );

        $response->assertStatus(404);

        $this->assertErrorJSONResponse( $response );

        $response->assertJson([
                 'status' => '404',
                 'code' => '3',
                 'title' => 'Ticket Not Found'
             ]);

    }


    public function testPayTicket()
    {

        $parkingTicketId = "5555";

        $response = $this->json('PATCH', '/api/payTicket/'.$parkingTicketId, ['data'=>['id'=>$parkingTicketId, 'type'=>'parking_ticket', 'payment_amount'=>35.50, 'currency_type' => 'CAD']] );

        $response->assertStatus(200);

        $this->assertSuccessJSONResponse( $response );

        $response->assertJson([
                 'type' => 'parking_ticket',
                 'is_paid' => true
             ]);

    }


    public function testPayTicketFurtherPaymentRequired()
    {

        $parkingTicketId = "5555";

        $response = $this->json('PATCH', '/api/payTicket/'.$parkingTicketId, ['data'=>['id'=>$parkingTicketId, 'type'=>'parking_ticket', 'payment_amount'=>5.50, 'currency_type' => 'CAD']] );

        $response->assertStatus(402);

        $this->assertErrorJSONResponse( $response );

        $response->assertJson([
                 'status' => '402',
                 'code' => '2',
                 'title' => 'Ticket Has Not Been Paid In Full',
                 'price' => 35.50,
                 'total_payment' => 5.50,
                 'currency_type' => 'CAD'
             ]);

    }


    public function testPayTicketTicketDoesNotExist()
    {

        $response = $this->json('PATCH', '/api/payTicket/555555' );

        $response->assertStatus(404);

        $this->assertErrorJSONResponse( $response );

        $response->assertJson([
                 'status' => '404',
                 'code' => '3',
                 'title' => 'Ticket Not Found'
             ]);

    }





    public function testAcceptTicket()
    {

        $parkingTicketId = "5555";

        $response = $this->json('PATCH', '/api/acceptTicket/'.$parkingTicketId, ['data'=>['id'=>$parkingTicketId, 'type'=>'parking_ticket', 'parking_venue'=>'1']] );

        $response->assertStatus(200);

        $this->assertSuccessJSONResponse( $response );

        $response->assertJson([
                 'type' => 'parking_ticket',
                 'accepted' => true
             ]);

    }

    public function testAcceptTicketFurtherPaymentRequired()
    {

        $parkingTicketId = "5555";

        $response = $this->json('PATCH', '/api/acceptTicket/'.$parkingTicketId, ['data'=>['id'=>$parkingTicketId, 'type'=>'parking_ticket', 'parking_venue'=>'1']] );

        $response->assertStatus(402);

        $this->assertErrorJSONResponse( $response );

        $response->assertJson([
                 'status' => '402',
                 'code' => '2',
                 'title' => 'Ticket Has Not Been Paid In Full',
                 'price' => 35.50,
                 'total_payment' => 0.00,
                 'currency_type' => 'CAD'
             ]);

    }


    public function testAcceptTicketTicketDoesNotExist()
    {

        $response = $this->json('PATCH', '/api/acceptTicket/555555' );

        $response->assertStatus(404);

        $this->assertErrorJSONResponse( $response );

        $response->assertJson([
                 'status' => '404',
                 'code' => '3',
                 'title' => 'Ticket Not Found'
             ]);

    }



}
