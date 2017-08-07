<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserParkingTicket extends Model
{

    use SoftDeletes;

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

    //Because of a composite primary key there's no need to have it incrementing
    public $incrementing = false;

    //had to define one of the primary keys as Eloquent fails to update this Model without a "primarykey" defined.
    //As of Laravel 5.4 composite keys are not fully supported using eloquent.
    public $primaryKey  = 'parking_ticket_id';

    /**
     * User Parking Ticket's User
     * @return User
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * User Parking Ticket's Parking Ticket
     * @return ParkingTicket
     */
    public function parkingTicket()
    {
        return $this->belongsTo('App\Models\ParkingTicket');
    }

    /**
     * User Parking Ticket's Parking Venue
     * @return ParkingVenue
     */
    public function parkingVenue()
    {
        return $this->belongsTo('App\Models\ParkingVenue');
    }
}
