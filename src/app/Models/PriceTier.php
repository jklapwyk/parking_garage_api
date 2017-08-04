<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PriceTier extends Model
{
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

    public function parkingVenues()
    {
        return $this->belongsToMany('App\Models\ParkingVenues', 'parking_venue_price_tiers', 'price_tier_id', 'parking_venue_id');
    }

    public function currencyType()
    {
        return $this->belongsTo('App\Models\CurrencyTypeId');
    }
}
