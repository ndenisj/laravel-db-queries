<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    public function rooms()
    {
        return $this->belongsToMany(Room::class, 'city_room', 'city_id', 'room_id')->withPivot('created_at', 'updated_at')->using(CityRoom::class);
    }

    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }
}
