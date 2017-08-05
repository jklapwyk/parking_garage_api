<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuids;

class ParkingTicket extends Model
{

    use Uuids;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    ];

    public $incrementing = false;

    public function users()
    {
        return $this->hasManyThrough(
            'App\Models\UserParkingTicket', 'App\Models\User', 'parking_ticket_id', 'user_id', 'id'
        );
    }

    public function userParkingTicket()
    {
        return $this->hasOne('App\Models\UserParkingTicket');
    }
}
