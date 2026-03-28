<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cinema extends Model
{
    protected $fillable = ['type', 'hall', 'seat_capacity', 'branch', 'seat_id', 'price', 'description'];

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}
