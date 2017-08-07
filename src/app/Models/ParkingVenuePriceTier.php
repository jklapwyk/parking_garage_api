<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Pivot Table for ParkingVenue and PriceTier
 */
class ParkingVenuePriceTier extends Model
{

    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'parking_venue_id',
        'price_tier_id'
    ];

    /**
     * ParkingVenue
     * @return ParkingVenue
     */
    public function parkingVenue()
    {
        return $this->belongsTo('App\Models\ParkingVenue');
    }

    /**
     * PriceTier
     * @return PriceTier
     */
    public function priceTier()
    {
        return $this->belongsTo('App\Models\PriceTier');
    }
}
