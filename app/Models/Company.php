<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    public function reservations()
    {
        return $this->hasManyThrough(Reservation::class, User::class, 'company_id', 'user_id', 'id', 'id');
    }
}
