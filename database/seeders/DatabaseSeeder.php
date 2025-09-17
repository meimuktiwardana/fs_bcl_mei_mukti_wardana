<?php
// database/seeders/DatabaseSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Fleet;
use App\Models\Shipment;
use App\Models\Order;
use App\Models\LocationCheckIn;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Seed Fleets
        Fleet::create([
            'fleet_number' => 'TRK001',
            'vehicle_type' => 'truck',
            'availability' => 'available',
            'capacity' => 10.5
        ]);

        Fleet::create([
            'fleet_number' => 'VAN001',
            'vehicle_type' => 'van',
            'availability' => 'available',
            'capacity' => 2.5
        ]);

        Fleet::create([
            'fleet_number' => 'MTR001',
            'vehicle_type' => 'motorcycle',
            'availability' => 'unavailable',
            'capacity' => 0.5
        ]);

        Fleet::create([
            'fleet_number' => 'CAR001',
            'vehicle_type' => 'car',
            'availability' => 'available',
            'capacity' => 1.0
        ]);

        Fleet::create([
            'fleet_number' => 'TRK002',
            'vehicle_type' => 'truck',
            'availability' => 'unavailable',
            'capacity' => 15.0
        ]);

        // Seed Shipments
        Shipment::create([
            'tracking_number' => 'TRK-ABC123',
            'shipping_date' => now(),
            'origin_location' => 'Jakarta',
            'destination_location' => 'Surabaya',
            'status' => 'in_transit',
            'item_details' => 'Elektronik: TV LED 32 inch, Sound System',
            'fleet_id' => 1
        ]);

        Shipment::create([
            'tracking_number' => 'TRK-DEF456',
            'shipping_date' => now()->subDays(1),
            'origin_location' => 'Bandung',
            'destination_location' => 'Yogyakarta',
            'status' => 'delivered',
            'item_details' => 'Pakaian: 50 pcs kaos, 30 pcs kemeja',
            'fleet_id' => 2
        ]);

        Shipment::create([
            'tracking_number' => 'TRK-GHI789',
            'shipping_date' => now()->addDays(1),
            'origin_location' => 'Medan',
            'destination_location' => 'Palembang',
            'status' => 'pending',
            'item_details' => 'Makanan: Kopi specialty 100kg',
            'fleet_id' => null
        ]);

        Shipment::create([
            'tracking_number' => 'TRK-JKL012',
            'shipping_date' => now(),
            'origin_location' => 'Semarang',
            'destination_location' => 'Solo',
            'status' => 'in_transit',
            'item_details' => 'Furniture: Meja kerja, kursi kantor',
            'fleet_id' => 3
        ]);

        // Seed Orders
        Order::create([
            'customer_name' => 'Ahmad Rizki',
            'customer_phone' => '081234567890',
            'vehicle_type' => 'truck',
            'order_date' => now(),
            'item_details' => 'Mesin industri berat 5 ton',
            'fleet_id' => 5,
            'status' => 'confirmed'
        ]);

        Order::create([
            'customer_name' => 'Siti Nurhaliza',
            'customer_phone' => '082345678901',
            'vehicle_type' => 'van',
            'order_date' => now()->addDays(2),
            'item_details' => 'Perlengkapan pameran: booth, banner, brosur',
            'fleet_id' => null,
            'status' => 'pending'
        ]);

        Order::create([
            'customer_name' => 'Budi Santoso',
            'customer_phone' => '083456789012',
            'vehicle_type' => 'motorcycle',
            'order_date' => now()->subDays(1),
            'item_details' => 'Dokumen penting dan surat kontrak',
            'fleet_id' => 3,
            'status' => 'completed'
        ]);

        // Seed Location Check-ins
        LocationCheckIn::create([
            'fleet_id' => 1,
            'latitude' => -6.200000,
            'longitude' => 106.816666,
            'location_name' => 'Jakarta Pusat',
            'checked_in_at' => now()->subHours(2)
        ]);

        LocationCheckIn::create([
            'fleet_id' => 2,
            'latitude' => -7.797068,
            'longitude' => 110.370529,
            'location_name' => 'Yogyakarta',
            'checked_in_at' => now()->subHours(1)
        ]);

        LocationCheckIn::create([
            'fleet_id' => 3,
            'latitude' => -7.005145,
            'longitude' => 110.438125,
            'location_name' => 'Semarang',
            'checked_in_at' => now()->subMinutes(30)
        ]);

        LocationCheckIn::create([
            'fleet_id' => 1,
            'latitude' => -6.914744,
            'longitude' => 107.609810,
            'location_name' => 'Bandung',
            'checked_in_at' => now()->subHours(5)
        ]);
    }
}