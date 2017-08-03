<?php

use App\Models\ParkingTicket;
use Illuminate\Database\Seeder;

class ParkingTicketsTableSeeder extends Seeder {

    public function run()
    {
        factory(ParkingTicket::class, 200)->create();
    }

}
