{{-- resources/views/fleets/show.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4><i class="bi bi-truck"></i> Detail Armada: {{ $fleet->fleet_number }}</h4>
                <div>
                    <a href="{{ route('fleets.edit', $fleet) }}" class="btn btn-warning btn-sm">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    <form method="POST" action="{{ route('fleets.destroy', $fleet) }}" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" 
                                onclick="return confirm('Yakin ingin menghapus armada ini?')">
                            <i class="bi bi-trash"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Nomor Armada:</strong></td>
                                <td>{{ $fleet->fleet_number }}</td>
                            </tr>
                            <tr>
                                <td><strong>Jenis Kendaraan:</strong></td>
                                <td>{{ ucfirst($fleet->vehicle_type) }}</td>
                            </tr>
                            <tr>
                                <td><strong>Kapasitas:</strong></td>
                                <td>{{ $fleet->capacity }} ton</td>
                            </tr>
                            <tr>
                                <td><strong>Status:</strong></td>
                                <td>
                                    <span class="badge bg-{{ $fleet->availability == 'available' ? 'success' : 'danger' }}">
                                        {{ $fleet->availability == 'available' ? 'Tersedia' : 'Tidak Tersedia' }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6>Statistik</h6>
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Total Pengiriman:</strong></td>
                                <td>{{ $fleet->shipments->count() }}</td>
                            </tr>
                            <tr>
                                <td><strong>Pengiriman Aktif:</strong></td>
                                <td>{{ $fleet->shipments->whereIn('status', ['pending', 'in_transit'])->count() }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                @if($fleet->latestLocation)
                    <hr>
                    <h6>Lokasi Terakhir</h6>
                    <p><i class="bi bi-geo-alt"></i> {{ $fleet->latestLocation->location_name ?? 'Tidak diketahui' }}</p>
                    <p><small class="text-muted">Check-in: {{ $fleet->latestLocation->checked_in_at->format('d/m/Y H:i') }}</small></p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6>Pengiriman Terbaru</h6>
            </div>
            <div class="card-body">
                @if($fleet->shipments->count() > 0)
                    @foreach($fleet->shipments->take(5) as $shipment)
                        <div class="mb-2 pb-2 border-bottom">
                            <strong>{{ $shipment->tracking_number }}</strong><br>
                            <small class="text-muted">{{ $shipment->destination_location }}</small>
                            <span class="badge bg-{{ $shipment->status == 'delivered' ? 'success' : 'warning' }} float-end">
                                {{ ucfirst($shipment->status) }}
                            </span>
                        </div>
                    @endforeach
                    <a href="{{ route('shipments.index', ['fleet_id' => $fleet->id]) }}" class="btn btn-sm btn-outline-primary">
                        Lihat Semua
                    </a>
                @else
                    <p class="text-muted">Belum ada pengiriman.</p>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="mt-3">
    <a href="{{ route('fleets.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Kembali ke Daftar Armada
    </a>
</div>
@endsection