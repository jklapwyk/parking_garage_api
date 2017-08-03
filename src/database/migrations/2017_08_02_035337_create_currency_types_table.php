<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Carbon\Carbon;

class CreateCurrencyTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('currency_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('iso_code');
            $table->timestamps();
        });

        $currency_types = [
            ['id' => 1, 'name' => 'Canadian Dollar', 'iso_code' => 'CAD', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 2, 'name' => 'US Dollar', 'iso_code' => 'USD', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ];

        DB::table('currency_types')->insert($currency_types);

        $price_tiers = [
            ['id' => 1, 'name' => 'Tier 1', 'price' => 3.00, 'currency_type_id' => 1, 'max_duration_seconds' => (1 * 60 * 60), 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 2, 'name' => 'Tier 2', 'price' => 4.50, 'currency_type_id' => 1, 'max_duration_seconds' => (3 * 60 * 60), 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 3, 'name' => 'Tier 3', 'price' => 6.75, 'currency_type_id' => 1, 'max_duration_seconds' => (6 * 60 * 60), 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 4, 'name' => 'Tier 4', 'price' => 10.13, 'currency_type_id' => 1, 'max_duration_seconds' => (24 * 60 * 60), 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ];

        DB::table('price_tiers')->insert($price_tiers);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('currency_types');
    }
}
