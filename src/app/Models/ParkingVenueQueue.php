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

    //custom table name with no "s"
    protected $table = 'parking_venue_queue';


    /**
     * Parking Venue Queue's User
     * @return User
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * Parking Venue Queue's ParkingVenue
     * @return ParkingVenue
     */
    public function parkingVenue()
    {
        return $this->belongsTo('App\Models\ParkingVenue');
    }
}
