<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Carbon\Carbon;

class CreateParkingVenuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parking_venues', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('total_lots');
            $table->softDeletes();
            $table->timestamps();
        });

        $parking_venues = [
            ['id' => 1, 'name' => 'Parking Venue 1', 'total_lots' => 5, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
          ];

        DB::table('parking_venues')->insert($parking_venues);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('parking_venues');
    }
}
