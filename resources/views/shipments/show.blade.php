{{-- resources/views/shipments/show.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4><i class="bi bi-box"></i> Detail Pengiriman</h4>
                <div>
                    <a href="{{ route('shipments.edit', $shipment) }}" class="btn btn-warning btn-sm">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    <form method="POST" action="{{ route('shipments.destroy', $shipment) }}" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" 
                                onclick="return confirm('Yakin ingin menghapus pengiriman ini?')">
                            <i class="bi bi-trash"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Informasi Pengiriman</h6>
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Tracking Number:</strong></td>
                                <td>{{ $shipment->tracking_number }}</td>
                            </tr>
                            <tr>
                                <td><strong>Status:</strong></td>
                                <td>
                                    <span class="badge bg-{{ $shipment->status == 'delivered' ? 'success' : ($shipment->status == 'in_transit' ? 'warning' : 'secondary') }}">
                                        {{ ucfirst(str_replace('_', ' ', $shipment->status)) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Tanggal Kirim:</strong></td>
                                <td>{{ $shipment->shipping_date->format('d/m/Y') }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6>Lokasi</h6>
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Asal:</strong></td>
                                <td>{{ $shipment->origin_location }}</td>
                            </tr>
                            <tr>
                                <td><strong>Tujuan:</strong></td>
                                <td>{{ $shipment->destination_location }}</td>
                            </tr>
                            @if($shipment->fleet)
                                <tr>
                                    <td><strong>Armada:</strong></td>
                                    <td>
                                        <a href="{{ route('fleets.show', $shipment->fleet) }}">
                                            {{ $shipment->fleet->fleet_number }} ({{ ucfirst($shipment->fleet->vehicle_type) }})
                                        </a>
                                    </td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>
                
                <hr>
                
                <h6>Detail Barang</h6>
                <p>{{ $shipment->item_details }}</p>
            </div>
        </div>
    </div>
</div>

<div class="mt-3 text-center">
    <a href="{{ route('shipments.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Kembali ke Daftar Pengiriman
    </a>
</div>
@endsection