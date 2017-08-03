<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserParkingTicket extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'parking_ticket_id',
        'parking_venue_id',
        'total_payment',
        'is_paid'
    ];
}
