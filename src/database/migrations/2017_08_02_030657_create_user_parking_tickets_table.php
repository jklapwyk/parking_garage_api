<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserParkingTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_parking_tickets', function (Blueprint $table) {
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('ticket_id')->unsigned();
            $table->primary(['user_id', 'ticket_id']);
            $table->integer('parking_venue_id')->unsigned();
            $table->boolean('is_paid')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_parking_tickets');
    }
}