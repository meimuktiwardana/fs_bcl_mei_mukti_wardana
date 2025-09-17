<?php
// app/Http/Controllers/ShipmentController.php

namespace App\Http\Controllers;

use App\Models\Shipment;
use App\Models\Fleet;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ShipmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Shipment::with('fleet');
        
        // Pencarian berdasarkan tracking number
        if ($request->has('tracking_number') && $request->tracking_number) {
            $query->where('tracking_number', 'like', '%' . $request->tracking_number . '%');
        }
        
        // Pencarian berdasarkan tujuan
        if ($request->has('destination') && $request->destination) {
            $query->where('destination_location', 'like', '%' . $request->destination . '%');
        }
        
        $shipments = $query->latest()->paginate(10);
        
        return view('shipments.index', compact('shipments'));
    }

    public function create()
    {
        $fleets = Fleet::where('availability', 'available')->get();
        return view('shipments.create', compact('fleets'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'shipping_date' => 'required|date|after_or_equal:today',
            'origin_location' => 'required|string|max:255',
            'destination_location' => 'required|string|max:255',
            'item_details' => 'required|string',
            'fleet_id' => 'nullable|exists:fleets,id'
        ], [
            'shipping_date.required' => 'Tanggal pengiriman harus diisi',
            'shipping_date.after_or_equal' => 'Tanggal pengiriman tidak boleh masa lalu',
            'origin_location.required' => 'Lokasi asal harus diisi',
            'destination_location.required' => 'Lokasi tujuan harus diisi',
            'item_details.required' => 'Detail barang harus diisi',
        ]);

        // Generate tracking number
        $validated['tracking_number'] = 'TRK-' . strtoupper(Str::random(8));
        
        // Pastikan tracking number unik
        while (Shipment::where('tracking_number', $validated['tracking_number'])->exists()) {
            $validated['tracking_number'] = 'TRK-' . strtoupper(Str::random(8));
        }

        // Cek ketersediaan armada jika dipilih
        if ($validated['fleet_id']) {
            $fleet = Fleet::findOrFail($validated['fleet_id']);
            if ($fleet->availability !== 'available') {
                return back()->withErrors(['fleet_id' => 'Armada yang dipilih tidak tersedia']);
            }
            
            // Update status armada
            $fleet->update(['availability' => 'unavailable']);
        }

        $shipment = Shipment::create($validated);
        
        return redirect()->route('shipments.show', $shipment)
                        ->with('success', 'Pengiriman berhasil dibuat dengan nomor tracking: ' . $shipment->tracking_number);
    }

    public function show(Shipment $shipment)
    {
        $shipment->load('fleet');
        return view('shipments.show', compact('shipment'));
    }

    public function edit(Shipment $shipment)
    {
        $fleets = Fleet::all();
        return view('shipments.edit', compact('shipment', 'fleets'));
    }

    public function update(Request $request, Shipment $shipment)
    {
        $validated = $request->validate([
            'shipping_date' => 'required|date',
            'origin_location' => 'required|string|max:255',
            'destination_location' => 'required|string|max:255',
            'status' => 'required|in:pending,in_transit,delivered',
            'item_details' => 'required|string',
            'fleet_id' => 'nullable|exists:fleets,id'
        ]);

        // Handle fleet change
        $oldFleetId = $shipment->fleet_id;
        $newFleetId = $validated['fleet_id'];

        if ($oldFleetId !== $newFleetId) {
            // Release old fleet
            if ($oldFleetId) {
                Fleet::find($oldFleetId)->update(['availability' => 'available']);
            }
            
            // Assign new fleet
            if ($newFleetId) {
                $newFleet = Fleet::findOrFail($newFleetId);
                if ($newFleet->availability !== 'available') {
                    return back()->withErrors(['fleet_id' => 'Armada yang dipilih tidak tersedia']);
                }
                $newFleet->update(['availability' => 'unavailable']);
            }
        }

        // If shipment is delivered, make fleet available
        if ($validated['status'] === 'delivered' && $shipment->fleet_id) {
            Fleet::find($shipment->fleet_id)->update(['availability' => 'available']);
        }

        $shipment->update($validated);
        
        return redirect()->route('shipments.show', $shipment)
                        ->with('success', 'Pengiriman berhasil diperbarui');
    }

    public function destroy(Shipment $shipment)
    {
        // Release fleet if assigned
        if ($shipment->fleet_id) {
            Fleet::find($shipment->fleet_id)->update(['availability' => 'available']);
        }

        $shipment->delete();
        
        return redirect()->route('shipments.index')
                        ->with('success', 'Pengiriman berhasil dihapus');
    }

    public function track(Request $request)
    {
        $shipment = null;
        
        if ($request->has('tracking_number') && $request->tracking_number) {
            $shipment = Shipment::with('fleet')
                               ->where('tracking_number', $request->tracking_number)
                               ->first();
        }
        
        return view('shipments.track', compact('shipment'));
    }
}