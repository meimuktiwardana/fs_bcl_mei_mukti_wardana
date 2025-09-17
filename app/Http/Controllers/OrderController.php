<?php
// app/Http/Controllers/OrderController.php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Fleet;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('fleet')->latest()->paginate(10);
        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        $availableFleets = Fleet::where('availability', 'available')
                                ->get()
                                ->groupBy('vehicle_type');
        
        return view('orders.create', compact('availableFleets'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'vehicle_type' => 'required|in:truck,van,motorcycle,car',
            'order_date' => 'required|date|after_or_equal:today',
            'item_details' => 'required|string'
        ], [
            'customer_name.required' => 'Nama pelanggan harus diisi',
            'customer_phone.required' => 'Nomor telepon harus diisi',
            'vehicle_type.required' => 'Jenis kendaraan harus dipilih',
            'order_date.required' => 'Tanggal pemesanan harus diisi',
            'order_date.after_or_equal' => 'Tanggal pemesanan tidak boleh masa lalu',
            'item_details.required' => 'Detail barang harus diisi'
        ]);

        // Cari armada yang tersedia sesuai jenis kendaraan
        $fleet = Fleet::where('vehicle_type', $validated['vehicle_type'])
                     ->where('availability', 'available')
                     ->first();

        if (!$fleet) {
            return back()->withInput()
                        ->withErrors(['vehicle_type' => 'Armada jenis ' . $validated['vehicle_type'] . ' tidak tersedia']);
        }

        // Buat pesanan
        $order = Order::create([
            ...$validated,
            'fleet_id' => $fleet->id,
            'status' => 'confirmed'
        ]);

        // Update status armada
        $fleet->update(['availability' => 'unavailable']);

        return redirect()->route('orders.show', $order)
                        ->with('success', 'Pemesanan berhasil dikonfirmasi dengan armada ' . $fleet->fleet_number);
    }

    public function show(Order $order)
    {
        $order->load('fleet');
        return view('orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        $fleets = Fleet::all();
        return view('orders.edit', compact('order', 'fleets'));
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'vehicle_type' => 'required|in:truck,van,motorcycle,car',
            'order_date' => 'required|date',
            'item_details' => 'required|string',
            'status' => 'required|in:pending,confirmed,completed'
        ]);

        // If order is completed, make fleet available
        if ($validated['status'] === 'completed' && $order->fleet_id) {
            Fleet::find($order->fleet_id)->update(['availability' => 'available']);
        }

        $order->update($validated);
        
        return redirect()->route('orders.show', $order)
                        ->with('success', 'Pesanan berhasil diperbarui');
    }

    public function destroy(Order $order)
    {
        // Release fleet if assigned
        if ($order->fleet_id) {
            Fleet::find($order->fleet_id)->update(['availability' => 'available']);
        }

        $order->delete();
        
        return redirect()->route('orders.index')
                        ->with('success', 'Pesanan berhasil dihapus');
    }
}