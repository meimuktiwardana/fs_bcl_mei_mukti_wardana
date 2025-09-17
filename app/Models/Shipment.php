<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// app/Models/Shipment.php
class Shipment extends Model
{
    protected $fillable = [
        'tracking_number', 'shipping_date', 'origin_location', 
        'destination_location', 'status', 'item_details', 'fleet_id'
    ];

    protected $casts = [
        'shipping_date' => 'date'
    ];

    public function fleet()
    {
        return $this->belongsTo(Fleet::class);
    }
}
