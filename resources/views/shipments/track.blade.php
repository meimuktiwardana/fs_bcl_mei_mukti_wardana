{{-- resources/views/shipments/track.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4><i class="bi bi-search"></i> Pelacakan Pengiriman</h4>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('shipments.track') }}">
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="bi bi-box"></i></span>
                        <input type="text" class="form-control" name="tracking_number" 
                               placeholder="Masukkan nomor pengiriman" 
                               value="{{ request('tracking_number') }}" required>
                        <button class="btn btn-primary" type="submit">
                            <i class="bi bi-search"></i> Lacak
                        </button>
                    </div>
                </form>

                @if(isset($shipment) && $shipment)
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5><i class="bi bi-info-circle"></i> Detail Pengiriman</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Informasi Pengiriman</h6>
                                    <table class="table table-borderless table-sm">
                                        <tr>
                                            <td><strong>Nomor Tracking:</strong></td>
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
                                    <table class="table table-borderless table-sm">
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
                                                <td>{{ $shipment->fleet->fleet_number }} ({{ ucfirst($shipment->fleet->vehicle_type) }})</td>
                                            </tr>
                                        @endif
                                    </table>
                                </div>
                            </div>
                            
                            <hr>
                            
                            <h6>Detail Barang</h6>
                            <p class="mb-0">{{ $shipment->item_details }}</p>
                            
                            <!-- Status Timeline -->
                            <div class="mt-4">
                                <h6>Status Timeline</h6>
                                <div class="d-flex justify-content-between">
                                    <div class="text-center">
                                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <i class="bi bi-box"></i>
                                        </div>
                                        <p class="small mt-1">Tertunda</p>
                                    </div>
                                    <div class="text-center">
                                        <div class="bg-{{ $shipment->status == 'in_transit' || $shipment->status == 'delivered' ? 'primary' : 'secondary' }} text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <i class="bi bi-truck"></i>
                                        </div>
                                        <p class="small mt-1">Dalam Perjalanan</p>
                                    </div>
                                    <div class="text-center">
                                        <div class="bg-{{ $shipment->status == 'delivered' ? 'success' : 'secondary' }} text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <i class="bi bi-check"></i>
                                        </div>
                                        <p class="small mt-1">Tiba</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif(request('tracking_number'))
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle"></i> 
                        Nomor pengiriman <strong>{{ request('tracking_number') }}</strong> tidak ditemukan.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection