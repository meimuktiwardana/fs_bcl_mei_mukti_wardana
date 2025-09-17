{{-- resources/views/shipments/create.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4><i class="bi bi-box"></i> Buat Pengiriman Baru</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('shipments.store') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label">Tanggal Pengiriman *</label>
                        <input type="date" name="shipping_date" class="form-control @error('shipping_date') is-invalid @enderror" 
                               value="{{ old('shipping_date', date('Y-m-d')) }}" min="{{ date('Y-m-d') }}">
                        @error('shipping_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Lokasi Asal *</label>
                            <input type="text" name="origin_location" class="form-control @error('origin_location') is-invalid @enderror" 
                                   value="{{ old('origin_location') }}" placeholder="Contoh: Jakarta">
                            @error('origin_location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Lokasi Tujuan *</label>
                            <input type="text" name="destination_location" class="form-control @error('destination_location') is-invalid @enderror" 
                                   value="{{ old('destination_location') }}" placeholder="Contoh: Surabaya">
                            @error('destination_location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Pilih Armada (Opsional)</label>
                        <select name="fleet_id" class="form-select @error('fleet_id') is-invalid @enderror">
                            <option value="">Pilih Armada (Akan diassign otomatis jika kosong)</option>
                            @foreach($fleets as $fleet)
                                <option value="{{ $fleet->id }}" {{ old('fleet_id') == $fleet->id ? 'selected' : '' }}>
                                    {{ $fleet->fleet_number }} - {{ ucfirst($fleet->vehicle_type) }} ({{ $fleet->capacity }} ton)
                                </option>
                            @endforeach
                        </select>
                        @error('fleet_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Detail Barang *</label>
                        <textarea name="item_details" rows="4" class="form-control @error('item_details') is-invalid @enderror" 
                                  placeholder="Deskripsikan barang yang akan dikirim...">{{ old('item_details') }}</textarea>
                        @error('item_details')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Buat Pengiriman
                        </button>
                        <a href="{{ route('shipments.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection