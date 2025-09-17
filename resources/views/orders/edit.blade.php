{{-- resources/views/orders/edit.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4><i class="bi bi-pencil"></i> Edit Pesanan #{{ $order->id }}</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('orders.update', $order) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Pelanggan *</label>
                            <input type="text" name="customer_name" class="form-control @error('customer_name') is-invalid @enderror" 
                                   value="{{ old('customer_name', $order->customer_name) }}" placeholder="Nama lengkap pelanggan">
                            @error('customer_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nomor Telepon *</label>
                            <input type="text" name="customer_phone" class="form-control @error('customer_phone') is-invalid @enderror" 
                                   value="{{ old('customer_phone', $order->customer_phone) }}" placeholder="08xxxxxxxxxx">
                            @error('customer_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Jenis Kendaraan *</label>
                            <select name="vehicle_type" class="form-select @error('vehicle_type') is-invalid @enderror">
                                <option value="">Pilih Jenis Kendaraan</option>
                                <option value="truck" {{ old('vehicle_type', $order->vehicle_type) == 'truck' ? 'selected' : '' }}>Truck</option>
                                <option value="van" {{ old('vehicle_type', $order->vehicle_type) == 'van' ? 'selected' : '' }}>Van</option>
                                <option value="motorcycle" {{ old('vehicle_type', $order->vehicle_type) == 'motorcycle' ? 'selected' : '' }}>Motor</option>
                                <option value="car" {{ old('vehicle_type', $order->vehicle_type) == 'car' ? 'selected' : '' }}>Mobil</option>
                            </select>
                            @error('vehicle_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Tanggal Pemesanan *</label>
                            <input type="date" name="order_date" class="form-control @error('order_date') is-invalid @enderror" 
                                   value="{{ old('order_date', $order->order_date->format('Y-m-d')) }}">
                            @error('order_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select @error('status') is-invalid @enderror">
                                <option value="pending" {{ old('status', $order->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="confirmed" {{ old('status', $order->status) == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="completed" {{ old('status', $order->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Detail Barang *</label>
                        <textarea name="item_details" rows="4" class="form-control @error('item_details') is-invalid @enderror" 
                                  placeholder="Deskripsikan barang yang akan dikirim...">{{ old('item_details', $order->item_details) }}</textarea>
                        @error('item_details')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    @if($order->fleet)
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> 
                            Armada saat ini: <strong>{{ $order->fleet->fleet_number }}</strong> ({{ ucfirst($order->fleet->vehicle_type) }})
                        </div>
                    @endif

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Update Pesanan
                        </button>
                        <a href="{{ route('orders.show', $order) }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection