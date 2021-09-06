<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    use HasFactory;

    protected $fillable = ['from_place_id', 'to_place_id', 'departure_time'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_routes', 'user_id', 'route_id');
    }
}
