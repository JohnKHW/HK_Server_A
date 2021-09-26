<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = [
        'name',
        'latitude',
        'longitude',
        'x',
        'y',
        'image',
        'description'
    ];

    public function fromSchedules()
    {
        return $this->hasMany(Schedule::class, 'from_place_id');
    }

    public function toScheules()
    {
        return $this->hasMany(Schedule::class, 'to_place_id');
    }
}
