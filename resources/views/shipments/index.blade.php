{{-- resources/views/shipments/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Data Pengiriman</h2>
    <a href="{{ route('shipments.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Tambah Pengiriman
    </a>
</div>

<!-- Search Form -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-5">
                <label class="form-label">Nomor Tracking</label>
                <input type="text" name="tracking_number" class="form-control" 
                       value="{{ request('tracking_number') }}" placeholder="Cari nomor tracking...">
            </div>
            <div class="col-md-5">
                <label class="form-label">Lokasi Tujuan</label>
                <input type="text" name="destination" class="form-control" 
                       value="{{ request('destination') }}" placeholder="Cari tujuan...">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-outline-primary w-100">
                    <i class="bi bi-search"></i> Cari
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Shipments Table -->
<div class="card">
    <div class="card-body">
        @if(isset($shipments) && $shipments->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Tracking Number</th>
                            <th>Tanggal</th>
                            <th>Asal - Tujuan</th>
                            <th>Status</th>
                            <th>Armada</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($shipments as $shipment)
                            <tr>
                                <td><strong>{{ $shipment->tracking_number }}</strong></td>
                                <td>{{ $shipment->shipping_date->format('d/m/Y') }}</td>
                                <td>
                                    <small class="text-muted">{{ $shipment->origin_location }}</small><br>
                                    <i class="bi bi-arrow-down"></i><br>
                                    <strong>{{ $shipment->destination_location }}</strong>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $shipment->status == 'delivered' ? 'success' : ($shipment->status == 'in_transit' ? 'warning' : 'secondary') }}">
                                        {{ ucfirst(str_replace('_', ' ', $shipment->status)) }}
                                    </span>
                                </td>
                                <td>
                                    @if($shipment->fleet)
                                        {{ $shipment->fleet->fleet_number }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('shipments.show', $shipment) }}" class="btn btn-sm btn-outline-info">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('shipments.edit', $shipment) }}" class="btn btn-sm btn-outline-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $shipments->links() }}
        @else
            <div class="text-center py-4">
                <i class="bi bi-box display-1 text-muted"></i>
                <p class="mt-2">Belum ada data pengiriman.</p>
            </div>
        @endif
    </div>
</div>
@endsection