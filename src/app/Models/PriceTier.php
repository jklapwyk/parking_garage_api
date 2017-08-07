<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PriceTier extends Model
{

    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'price',
        'currency_type_id',
        'max_duration_seconds',
    ];

    /**
     * Price Tier's Parking Venue
     * @return ParkingVenue
     */
    public function parkingVenues()
    {
        return $this->belongsToMany('App\Models\ParkingVenues', 'parking_venue_price_tiers', 'price_tier_id', 'parking_venue_id');
    }

    /**
     * Parking Venue Queue's Currency Type
     * @return Currency Type
     */
    public function currencyType()
    {
        return $this->belongsTo('App\Models\CurrencyType');
    }
}
