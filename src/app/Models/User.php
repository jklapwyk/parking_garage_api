<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{

    use Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'remember_token',
        'user_hash',
        'is_registered',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * User's Parking Tickets meta information
     * @return UserParkingTicket
     */
    public function userParkingTickets()
    {
        return $this->hasMany('App\Models\UserParkingTicket');
    }

    /**
     * User's Parking Tickets
     * @return ParkingTicket
     */
    public function parkingTickets()
    {
      return $this->hasManyThrough(
          'App\Models\UserParkingTicket', 'App\Models\ParkingTicket', 'user_id', 'parking_ticket_id', 'id'
      );
    }

    /**
     * User's Parking Venue Queue
     * @return ParkingVenueQueue
     */
    public function parkingVenueQueue()
    {
        return $this->hasMany('App\Models\ParkingVenueQueue');
    }
}
