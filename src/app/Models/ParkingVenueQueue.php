<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ParkingVenueQueue extends Model
{

    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'parking_venue_id'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function parkingVenue()
    {
        return $this->belongsTo('App\Models\ParkingVenue');
    }
}
