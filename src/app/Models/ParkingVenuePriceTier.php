<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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

    public function parkingVenue()
    {
        return $this->belongsTo('App\Models\ParkingVenue');
    }

    public function priceTier()
    {
        return $this->belongsTo('App\Models\PriceTier');
    }
}
