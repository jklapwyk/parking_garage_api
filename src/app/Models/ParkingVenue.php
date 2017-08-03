<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParkingVenue extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'total_lots'
    ];
}
