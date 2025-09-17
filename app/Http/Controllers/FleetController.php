<?php
// app/Http/Controllers/FleetController.php

namespace App\Http\Controllers;

use App\Models\Fleet;
use Illuminate\Http\Request;

class FleetController extends Controller
{
    public function index(Request $request)
    {
        $query = Fleet::query();
        
        // Filter berdasarkan jenis kendaraan
        if ($request->has('vehicle_type') && $request->vehicle_type) {
            $query->where('vehicle_type', $request->vehicle_type);
        }
        
        // Filter berdasarkan ketersediaan
        if ($request->has('availability') && $request->availability) {
            $query->where('availability', $request->availability);
        }
        
        $fleets = $query->paginate(10);
        
        return view('fleets.index', compact('fleets'));
    }

    public function create()
    {
        return view('fleets.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'fleet_number' => 'required|unique:fleets|max:255',
            'vehicle_type' => 'required|in:truck,van,motorcycle,car',
            'capacity' => 'required|numeric|min:0|max:999.99',
            'availability' => 'required|in:available,unavailable'
        ], [
            'fleet_number.required' => 'Nomor armada harus diisi',
            'fleet_number.unique' => 'Nomor armada sudah digunakan',
            'vehicle_type.required' => 'Jenis kendaraan harus dipilih',
            'capacity.required' => 'Kapasitas harus diisi',
            'capacity.numeric' => 'Kapasitas harus berupa angka',
            'capacity.min' => 'Kapasitas minimal 0',
        ]);

        Fleet::create($validated);
        
        return redirect()->route('fleets.index')
                        ->with('success', 'Armada berhasil ditambahkan');
    }

    public function show(Fleet $fleet)
    {
        $fleet->load(['shipments' => function($query) {
            $query->latest()->take(5);
        }, 'latestLocation']);
        
        return view('fleets.show', compact('fleet'));
    }

    public function edit(Fleet $fleet)
    {
        return view('fleets.edit', compact('fleet'));
    }

    public function update(Request $request, Fleet $fleet)
    {
        $validated = $request->validate([
            'fleet_number' => 'required|unique:fleets,fleet_number,' . $fleet->id . '|max:255',
            'vehicle_type' => 'required|in:truck,van,motorcycle,car',
            'capacity' => 'required|numeric|min:0|max:999.99',
            'availability' => 'required|in:available,unavailable'
        ], [
            'fleet_number.required' => 'Nomor armada harus diisi',
            'fleet_number.unique' => 'Nomor armada sudah digunakan',
            'vehicle_type.required' => 'Jenis kendaraan harus dipilih',
            'capacity.required' => 'Kapasitas harus diisi',
            'capacity.numeric' => 'Kapasitas harus berupa angka',
            'capacity.min' => 'Kapasitas minimal 0',
        ]);

        $fleet->update($validated);
        
        return redirect()->route('fleets.index')
                        ->with('success', 'Armada berhasil diperbarui');
    }

    public function destroy(Fleet $fleet)
    {
        // Cek apakah armada sedang digunakan
        if ($fleet->shipments()->whereIn('status', ['pending', 'in_transit'])->exists()) {
            return redirect()->route('fleets.index')
                            ->with('error', 'Armada tidak dapat dihapus karena sedang digunakan');
        }

        $fleet->delete();
        
        return redirect()->route('fleets.index')
                        ->with('success', 'Armada berhasil dihapus');
    }
}