<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CurrencyType extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'iso_code'
    ];

    public function priceTiers()
    {
        return $this->hasMany('App\Models\PriceTiers');
    }
}
