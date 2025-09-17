<?php

namespace App\Http\Controllers;

use App\Models\Fleet;
use App\Models\LocationCheckIn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LocationCheckInController extends Controller
{
    /**
     * Tampilkan peta lokasi armada
     */
    public function map()
    {
        $fleets = Fleet::with(['latestLocation', 'shipments' => function($query) {
            $query->where('status', 'in_transit');
        }])->get();

        // Statistik check-in hari ini
        $todayCheckIns = LocationCheckIn::whereDate('checked_in_at', today())->count();
        
        // Armada yang aktif (sudah check-in dalam 24 jam terakhir)
        $activeFleets = Fleet::whereHas('locationCheckIns', function($query) {
            $query->where('checked_in_at', '>=', now()->subHours(24));
        })->count();

        return view('location.map', compact('fleets', 'todayCheckIns', 'activeFleets'));
    }

    /**
     * Tampilkan form check-in untuk armada
     */
    public function checkInForm()
    {
        $fleets = Fleet::all();
        return view('location.checkin', compact('fleets'));
    }

    /**
     * Proses check-in lokasi armada
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'fleet_id' => 'required|exists:fleets,id',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'location_name' => 'nullable|string|max:255'
        ]);

        $fleet = Fleet::findOrFail($validated['fleet_id']);

        // Buat check-in baru
        $checkIn = $fleet->locationCheckIns()->create([
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'location_name' => $validated['location_name'],
            'checked_in_at' => now()
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Check-in berhasil',
                'data' => $checkIn
            ]);
        }

        return redirect()->route('location.map')
            ->with('success', "Check-in berhasil untuk armada {$fleet->fleet_number}");
    }

    /**
     * Tampilkan riwayat lokasi armada tertentu
     */
    public function show(Fleet $fleet)
    {
        $locations = $fleet->locationCheckIns()
            ->orderBy('checked_in_at', 'desc')
            ->paginate(20);

        return view('location.history', compact('fleet', 'locations'));
    }

    /**
     * API: Ambil lokasi terbaru armada
     */
    public function getFleetLocation(Fleet $fleet)
    {
        $location = $fleet->latestLocation;

        if (!$location) {
            return response()->json([
                'success' => false,
                'message' => 'Lokasi tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'fleet_number' => $fleet->fleet_number,
                'vehicle_type' => $fleet->vehicle_type,
                'latitude' => $location->latitude,
                'longitude' => $location->longitude,
                'location_name' => $location->location_name,
                'checked_in_at' => $location->checked_in_at->format('Y-m-d H:i:s')
            ]
        ]);
    }

    /**
     * API: Ambil semua lokasi armada untuk peta
     */
    public function getAllLocations()
    {
        $fleets = Fleet::with(['latestLocation', 'shipments' => function($query) {
            $query->where('status', 'in_transit');
        }])->get();

        $locations = $fleets->map(function($fleet) {
            if (!$fleet->latestLocation) {
                return null;
            }

            return [
                'fleet_id' => $fleet->id,
                'fleet_number' => $fleet->fleet_number,
                'vehicle_type' => $fleet->vehicle_type,
                'availability' => $fleet->availability,
                'latitude' => $fleet->latestLocation->latitude,
                'longitude' => $fleet->latestLocation->longitude,
                'location_name' => $fleet->latestLocation->location_name,
                'checked_in_at' => $fleet->latestLocation->checked_in_at->format('Y-m-d H:i:s'),
                'active_shipments' => $fleet->shipments->count()
            ];
        })->filter();

        return response()->json([
            'success' => true,
            'data' => $locations->values()
        ]);
    }

    /**
     * Laporan aktivitas check-in
     */
    public function report()
    {
        // Statistik check-in per hari (7 hari terakhir)
        $dailyStats = DB::table('location_check_ins')
            ->select(DB::raw('DATE(checked_in_at) as date'), DB::raw('COUNT(*) as count'))
            ->where('checked_in_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Top 5 armada paling aktif
        $topActiveFleets = Fleet::withCount(['locationCheckIns' => function($query) {
            $query->where('checked_in_at', '>=', now()->subDays(30));
        }])
        ->orderBy('location_check_ins_count', 'desc')
        ->take(5)
        ->get();

        // Armada yang belum check-in dalam 24 jam
        $inactiveFleets = Fleet::whereDoesntHave('locationCheckIns', function($query) {
            $query->where('checked_in_at', '>=', now()->subHours(24));
        })->get();

        return view('location.report', compact('dailyStats', 'topActiveFleets', 'inactiveFleets'));
    }
}