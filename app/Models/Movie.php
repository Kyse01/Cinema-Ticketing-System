<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    protected $fillable = ['title', 'genre', 'duration', 'rating', 'synopsis', 'poster'];

    public function movieSchedules()
    {
        return $this->hasMany(MovieSchedule::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
