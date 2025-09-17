<?php
// app/Models/Fleet.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fleet extends Model
{
    use HasFactory;

    protected $fillable = [
        'fleet_number',
        'vehicle_type',
        'availability',
        'capacity'
    ];

    protected $casts = [
        'capacity' => 'decimal:2'
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
        return $this->hasOne(LocationCheckIn::class)->latestOfMany('checked_in_at');
    }

    // Scope untuk armada yang tersedia
    public function scopeAvailable($query)
    {
        return $query->where('availability', 'available');
    }

    // Scope untuk armada berdasarkan jenis
    public function scopeOfType($query, $type)
    {
        return $query->where('vehicle_type', $type);
    }
}