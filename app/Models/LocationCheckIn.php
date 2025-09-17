<?php
// app/Models/LocationCheckIn.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocationCheckIn extends Model
{
    use HasFactory;

    protected $fillable = [
        'fleet_id',
        'latitude',
        'longitude',
        'location_name',
        'checked_in_at'
    ];

    protected $casts = [
        'checked_in_at' => 'datetime',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8'
    ];

    public function fleet()
    {
        return $this->belongsTo(Fleet::class);
    }
}