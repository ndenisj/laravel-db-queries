<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    // protected $table = 'rooms';
    // protected $primaryKey = 'key';
    // public $timestamps = false;
    // protected $connection = 'sqlite';

    public function cities()
    {
        return $this->belongsToMany(City::class, 'city_room', 'room_id', 'city_id')->withPivot('created_at', 'updated_at')->using(CityRoom::class);
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function likes()
    {
        return $this->morphToMany(User::class, 'likeable');
    }
}
