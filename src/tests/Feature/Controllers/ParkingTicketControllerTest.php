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
use App\Services\ParkingTicketServiceInterface;

class ParkingTicketControllerTest extends TestCase
{
    use DatabaseMigrations;

    /*
    protected $parkingTicketService;

    public function __construct( ParkingTicketServiceInterface $parkingTicketService )
    {
        $this->parkingTicketService = $parkingTicketService;
    }
    */

    public function testCreateTicket()
    {

        $response = $this->json('POST', '/api/createParkingTicket', ['data'=>['parking_venue_id'=>'1']] );

        $this->assertParkingTicketSuccessResponse( $response );


    }

    public function assertParkingTicketSuccessResponse( $response )
    {
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

        for( $i = 0; $i < 5; $i++ ){
          $parkingTicketService->createParkingTicket( 1 );
        }


        $user = factory(User::class)->make();

        $response = $this->json('POST', '/api/createParkingTicket', ['data'=>['parking_venue_id'=>'1', 'user_id'=>$user->id]] );

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

        $parkingTicketId = $parkingTicketService->createParkingTicket( 1, $ticketCreationTime, $ticketCreationTime );



        $url = '/api/requestPriceForTicket/'.(string)$parkingTicketId;

        \Log::info("PARKING TICKET url = ".$url."  parking ticket id ".$parkingTicketId);

        $response = $this->json('GET', $url );

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

        $this->assertErrorJSONResponse( $response, 404, 3 );

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

        $this->assertErrorJSONResponse( $response , 402, 2 );

        $response->assertFragmentJson([
                 'price' => 35.50,
                 'total_payment' => 5.50,
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

        $this->assertErrorJSONResponse( $response, 402, 2 );

        $response->assertFragmentJson([
                'price' => 35.50,
                'total_payment' => 0.00,
                'currency_type' => 'CAD'
            ]);

    }


    public function testAcceptTicketTicketDoesNotExist()
    {

        $response = $this->json('PATCH', '/api/acceptTicket/555555' );

        $response->assertStatus(404);

        $this->assertErrorJSONResponse( $response, 404, 3 );

    }



}
