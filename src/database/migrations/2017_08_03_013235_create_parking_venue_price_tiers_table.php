<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Carbon\Carbon;

class CreateParkingVenuePriceTiersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parking_venue_price_tiers', function (Blueprint $table) {
            $table->integer('parking_venue_id')->unsigned();
            $table->integer('price_tier_id')->unsigned();
            $table->primary(['parking_venue_id', 'price_tier_id']);
            $table->softDeletes();
            $table->timestamps();
        });

        $parking_venue_price_tiers = [
            ['parking_venue_id' => 1, 'price_tier_id' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['parking_venue_id' => 1, 'price_tier_id' => 2, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['parking_venue_id' => 1, 'price_tier_id' => 3, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['parking_venue_id' => 1, 'price_tier_id' => 4, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
          ];

        DB::table('parking_venue_price_tiers')->insert($parking_venue_price_tiers);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('parking_venue_price_tiers');
    }
}
