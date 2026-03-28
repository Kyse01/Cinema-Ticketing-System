<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = ['start', 'end', 'cinema_id'];

    public function cinema()
    {
        return $this->belongsTo(Cinema::class);
    }

    public function movieSchedules()
    {
        return $this->hasMany(MovieSchedule::class);
    }
}
