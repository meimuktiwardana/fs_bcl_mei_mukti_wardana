<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// app/Models/Fleet.php
class Fleet extends Model
{
    protected $fillable = [
        'fleet_number', 'vehicle_type', 'availability', 'capacity'
    ];

    public function shipments()
    {
        return $this->hasMany(Shipment::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function locationCheckIns()
    {
        return $this->hasMany(LocationCheckIn::class);
    }

    public function latestLocation()
    {
        return $this->hasOne(LocationCheckIn::class)->latestOfMany();
    }
}
