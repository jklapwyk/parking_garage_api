<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParkingVenuePriceTier extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'parking_venue_id',
        'price_tier_id'
    ];

    public function parkingVenue()
    {
        return $this->belongsTo('App\Models\ParkingVenue');
    }

    public function priceTier()
    {
        return $this->belongsTo('App\Models\PriceTier');
    }
}
