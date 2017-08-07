<?php

namespace App\Traits;

use Webpatser\Uuid\Uuid;

trait Uuids
{

    /**
     * Overrides Model's boot function to create UUIDs for Model's primary key
     */
    protected static function boot()
    {
        parent::boot();

        //this creates a UUID for the primary key for a Model
        static::creating(function ($model) {
            $model->{$model->getKeyName()} = Uuid::generate()->string;
        });
    }
}
