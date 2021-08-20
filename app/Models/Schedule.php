<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'from_place_id',
        'to_place_id',
        'departure_time',
        'arrival_time',
        'distance',
        'speed'
    ];

    public function fromPlace() {
        return $this->belongsTo(Place::class);
    }

    public function toPlace() {
        return $this->belongsTo(Place::class);
    }
}
