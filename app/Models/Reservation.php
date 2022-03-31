<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    public function rooms()
    {
        return $this->belongsToMany(Room::class)->withPivot('status');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
