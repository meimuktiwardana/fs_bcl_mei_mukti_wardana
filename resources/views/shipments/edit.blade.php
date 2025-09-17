{{-- resources/views/shipments/edit.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4><i class="bi bi-pencil"></i> Edit Pengiriman: {{ $shipment->tracking_number }}</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('shipments.update', $shipment) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label">Nomor Tracking</label>
                        <input type="text" class="form-control" value="{{ $shipment->tracking_number }}" readonly>
                        <small class="text-muted">Nomor tracking tidak dapat diubah</small>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Pengiriman *</label>
                            <input type="date" name="shipping_date" class="form-control @error('shipping_date') is-invalid @enderror" 
                                   value="{{ old('shipping_date', $shipment->shipping_date->format('Y-m-d')) }}">
                            @error('shipping_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status *</label>
                            <select name="status" class="form-select @error('status') is-invalid @enderror">
                                <option value="pending" {{ old('status', $shipment->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="in_transit" {{ old('status', $shipment->status) == 'in_transit' ? 'selected' : '' }}>In Transit</option>
                                <option value="delivered" {{ old('status', $shipment->status) == 'delivered' ? 'selected' : '' }}>Delivered</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Lokasi Asal *</label>
                            <input type="text" name="origin_location" class="form-control @error('origin_location') is-invalid @enderror" 
                                   value="{{ old('origin_location', $shipment->origin_location) }}" placeholder="Contoh: Jakarta">
                            @error('origin_location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Lokasi Tujuan *</label>
                            <input type="text" name="destination_location" class="form-control @error('destination_location') is-invalid @enderror" 
                                   value="{{ old('destination_location', $shipment->destination_location) }}" placeholder="Contoh: Surabaya">
                            @error('destination_location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Pilih Armada</label>
                        <select name="fleet_id" class="form-select @error('fleet_id') is-invalid @enderror">
                            <option value="">Tidak ada armada</option>
                            @foreach($fleets as $fleet)
                                <option value="{{ $fleet->id }}" 
                                    {{ old('fleet_id', $shipment->fleet_id) == $fleet->id ? 'selected' : '' }}
                                    @if($fleet->availability == 'unavailable' && $fleet->id != $shipment->fleet_id) disabled @endif>
                                    {{ $fleet->fleet_number }} - {{ ucfirst($fleet->vehicle_type) }} ({{ $fleet->capacity }} ton)
                                    @if($fleet->availability == 'unavailable' && $fleet->id != $shipment->fleet_id) - Tidak Tersedia @endif
                                </option>
                            @endforeach
                        </select>
                        @error('fleet_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        @if($shipment->fleet)
                            <small class="text-muted">Armada saat ini: {{ $shipment->fleet->fleet_number }}</small>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Detail Barang *</label>
                        <textarea name="item_details" rows="4" class="form-control @error('item_details') is-invalid @enderror" 
                                  placeholder="Deskripsikan barang yang akan dikirim...">{{ old('item_details', $shipment->item_details) }}</textarea>
                        @error('item_details')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Update Pengiriman
                        </button>
                        <a href="{{ route('shipments.show', $shipment) }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection