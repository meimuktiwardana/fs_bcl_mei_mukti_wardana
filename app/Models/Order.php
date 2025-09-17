<?php
// app/Models/Order.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_name',
        'customer_phone',
        'vehicle_type',
        'order_date',
        'item_details',
        'fleet_id',
        'status'
    ];

    protected $casts = [
        'order_date' => 'date'
    ];

    public function fleet()
    {
        return $this->belongsTo(Fleet::class);
    }
}