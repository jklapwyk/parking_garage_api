<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ParkingVenue extends Model
{

    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'total_lots'
    ];

    /**
     * Parking Venue's queue
     * @return collection of ParkingVenueQueue
     */
    public function parkingVenueQueue()
    {
        return $this->hasMany('App\Models\ParkingVenueQueue');
    }

    /**
     * Parking Venue's User Parking Tickets
     * @return collection of UserParkingTicket
     */
    public function userParkingTickets()
    {
        return $this->hasMany('App\Models\UserParkingTicket');
    }

    /**
     * Parking Venue's Parking Tickets
     *
     * @return collection of ParkingTicket
     */
    public function parkingTickets()
    {
        return $this->hasManyThrough(
            'App\Models\UserParkingTicket', 'App\Models\ParkingTicket', 'parking_venue_id', 'parking_ticket_id', 'id'
        );
    }

    /**
     * Parking Venue's Price Tiers
     *
     * @return collection of PriceTiers
     */
    public function priceTiers()
    {
        return $this->belongsToMany('App\Models\PriceTier', 'parking_venue_price_tiers', 'parking_venue_id', 'price_tier_id');
    }
}
