<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MovieSchedule extends Model
{
    protected $fillable = ['movie_id', 'schedule_id'];

    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }
}
