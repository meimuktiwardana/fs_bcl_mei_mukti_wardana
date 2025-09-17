<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LocationCheckInController extends Controller
{
    public function store(Request $request, Fleet $fleet)
    {
        $validated = $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'location_name' => 'nullable|string|max:255'
        ]);

        $fleet->locationCheckIns()->create([
            ...$validated,
            'checked_in_at' => now()
        ]);

        return response()->json(['message' => 'Check-in berhasil']);
    }

    public function map()
    {
        $fleets = Fleet::with('latestLocation')->get();
        return view('location.map', compact('fleets'));
    }
}
