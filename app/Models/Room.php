<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    public function type()
    {
        return $this->belongsTo(RoomType::class, 'room_type_id', 'id');
    }

    public function reservations()
    {
        return $this->belongsToMany(Reservation::class)->withPivot('status');
    }

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }
}
