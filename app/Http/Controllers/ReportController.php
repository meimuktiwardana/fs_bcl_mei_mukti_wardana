<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// app/Http/Controllers/ReportController.php
class ReportController extends Controller
{
    public function fleetShipmentStats()
    {
        $stats = Fleet::leftJoin('shipments', 'fleets.id', '=', 'shipments.fleet_id')
                     ->select(
                         'fleets.fleet_number',
                         'fleets.vehicle_type',
                         DB::raw('COUNT(CASE WHEN shipments.status = "in_transit" THEN 1 END) as in_transit_count'),
                         DB::raw('COUNT(shipments.id) as total_shipments')
                     )
                     ->groupBy('fleets.id', 'fleets.fleet_number', 'fleets.vehicle_type')
                     ->get();

        return view('reports.fleet-stats', compact('stats'));
    }
}
