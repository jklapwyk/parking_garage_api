<?php

namespace App\Traits;

use Webpatser\Uuid\Uuid;

trait Uuids
{

    protected static function boot()
    {
        parent::boot();

        //this creates a UUID for the primary key for a Model
        static::creating(function ($model) {
            $model->{$model->getKeyName()} = Uuid::generate()->string;
        });
    }
}
