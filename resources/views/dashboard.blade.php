{{-- resources/views/dashboard.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h1 class="display-4">Dashboard</h1>
        <p class="lead">Sistem Manajemen Pengiriman dan Armada</p>
    </div>
</div>

<div class="row mb-4">
    <!-- Statistik Cards -->
    <div class="col-md-3 mb-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>{{ \App\Models\Fleet::count() }}</h4>
                        <p class="mb-0">Total Armada</p>
                    </div>
                    <div>
                        <i class="bi bi-truck fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>{{ \App\Models\Fleet::where('availability', 'available')->count() }}</h4>
                        <p class="mb-0">Armada Tersedia</p>
                    </div>
                    <div>
                        <i class="bi bi-check-circle fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>{{ \App\Models\Shipment::where('status', 'in_transit')->count() }}</h4>
                        <p class="mb-0">Dalam Perjalanan</p>
                    </div>
                    <div>
                        <i class="bi bi-arrow-right fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>{{ \App\Models\Order::where('status', 'pending')->count() }}</h4>
                        <p class="mb-0">Pesanan Pending</p>
                    </div>
                    <div>
                        <i class="bi bi-clock fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Quick Actions -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5><i class="bi bi-lightning"></i> Aksi Cepat</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('shipments.track') }}" class="btn btn-outline-primary">
                        <i class="bi bi-search"></i> Lacak Pengiriman
                    </a>
                    <a href="{{ route('orders.create') }}" class="btn btn-outline-success">
                        <i class="bi bi-plus-circle"></i> Buat Pesanan Baru
                    </a>
                    <a href="{{ route('fleets.create') }}" class="btn btn-outline-info">
                        <i class="bi bi-truck"></i> Tambah Armada
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Shipments -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5><i class="bi bi-clock-history"></i> Pengiriman Terbaru</h5>
            </div>
            <div class="card-body">
                @php
                    $recentShipments = \App\Models\Shipment::latest()->take(5)->get();
                @endphp
                
                @if($recentShipments->count() > 0)
                    @foreach($recentShipments as $shipment)
                        <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                            <div>
                                <strong>{{ $shipment->tracking_number }}</strong><br>
                                <small class="text-muted">{{ $shipment->destination_location }}</small>
                            </div>
                            <span class="badge bg-{{ $shipment->status == 'delivered' ? 'success' : 'warning' }}">
                                {{ ucfirst($shipment->status) }}
                            </span>
                        </div>
                    @endforeach
                    <a href="{{ route('shipments.index') }}" class="btn btn-sm btn-outline-primary mt-2">
                        Lihat Semua
                    </a>
                @else
                    <p class="text-muted">Belum ada pengiriman.</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Tracking Form -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5><i class="bi bi-search"></i> Lacak Pengiriman Cepat</h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('shipments.track') }}" class="row g-3">
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="tracking_number" 
                               placeholder="Masukkan nomor pengiriman..." required>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search"></i> Lacak Sekarang
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection