<?php
// app/Models/Shipment.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'tracking_number',
        'shipping_date',
        'origin_location',
        'destination_location',
        'status',
        'item_details',
        'fleet_id'
    ];

    protected $casts = [
        'shipping_date' => 'date'
    ];

    public function fleet()
    {
        return $this->belongsTo(Fleet::class);
    }
}