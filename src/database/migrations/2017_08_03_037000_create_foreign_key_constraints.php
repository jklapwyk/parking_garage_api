<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateForeignKeyConstraints extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //

        Schema::table('user_parking_tickets', function (Blueprint $table) {
          $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
          $table->foreign('parking_ticket_id')->references('id')->on('parking_tickets')->onDelete('cascade');
          $table->foreign('parking_venue_id')->references('id')->on('parking_venues')->onDelete('cascade');
        });

        Schema::table('parking_venue_queue', function (Blueprint $table) {
          $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
          $table->foreign('parking_venue_id')->references('id')->on('parking_venues')->onDelete('cascade');
        });

        Schema::table('parking_venue_price_tiers', function (Blueprint $table) {
          $table->foreign('parking_venue_id')->references('id')->on('parking_venues')->onDelete('cascade');
          $table->foreign('price_tier_id')->references('id')->on('price_tiers')->onDelete('cascade');
        });

        Schema::table('price_tiers', function (Blueprint $table) {
          $table->foreign('currency_type_id')->references('id')->on('currency_types');
        });



    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_parking_tickets', function (Blueprint $table) {
          $table->dropForeign(['user_id']);
          $table->dropForeign(['parking_ticket_id']);
          $table->dropForeign(['parking_venue_id']);
        });

        Schema::table('parking_venue_queue', function (Blueprint $table) {
          $table->dropForeign(['user_id']);
          $table->dropForeign(['parking_venue_id']);
        });

        Schema::table('parking_venue_price_tiers', function (Blueprint $table) {
          $table->dropForeign(['parking_venue_id']);
          $table->dropForeign(['price_tier_id']);
        });

        Schema::table('price_tiers', function (Blueprint $table) {
          $table->dropForeign(['currency_type_id']);
        });
    }
}
