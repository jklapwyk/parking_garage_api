<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParkingVenue extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'total_lots'
    ];

    public function parkingVenueQueue()
    {
        return $this->hasMany('App\Models\ParkingVenueQueue');
    }

    public function userParkingTickets()
    {
        return $this->hasMany('App\Models\UserParkingTicket');
    }

    public function parkingTickets()
    {
        return $this->hasManyThrough(
            'App\Models\UserParkingTicket', 'App\Models\ParkingTicket', 'parking_venue_id', 'parking_ticket_id', 'id'
        );
    }

    public function priceTiers()
    {
        return $this->belongsToMany('App\Models\PriceTiers', 'parking_venue_price_tiers', 'parking_venue_id', 'price_tier_id');
    }
}
