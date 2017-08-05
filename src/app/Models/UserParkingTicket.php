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

    public $incrementing = false;

    public $primaryKey  = 'parking_ticket_id';

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function parkingTicket()
    {
        return $this->belongsTo('App\Models\ParkingTicket');
    }

    public function parkingVenue()
    {
        return $this->belongsTo('App\Models\ParkingVenue');
    }
}
