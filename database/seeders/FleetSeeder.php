<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FleetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    // database/seeders/FleetSeeder.php
    public function run()
    {
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
        
        // Tambah data lainnya...
    }
}
