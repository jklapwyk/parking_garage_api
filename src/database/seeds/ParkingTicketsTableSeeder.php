<?php

use App\Models\ParkingTicket;
use Illuminate\Database\Seeder;

class ParkingTicketsTableSeeder extends Seeder {

    /**
    * Create a number of random parking tickets
    */
    public function run()
    {
        factory(ParkingTicket::class, 200)->create();
    }

}
