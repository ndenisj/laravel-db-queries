<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class CityRoom extends Pivot
{
    public $incrementing = true; // only if this pivot model has an auto-incrementing primary key

    protected static function booted()
    {
        // to listen to model events
        static::created(function($cityroom){
            dump($cityroom, 'Custom pivot model');
        });
    }
}
