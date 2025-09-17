{{-- resources/views/orders/create.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4><i class="bi bi-cart"></i> Buat Pesanan Baru</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('orders.store') }}">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Pelanggan *</label>
                            <input type="text" name="customer_name" class="form-control @error('customer_name') is-invalid @enderror" 
                                   value="{{ old('customer_name') }}" placeholder="Nama lengkap pelanggan">
                            @error('customer_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nomor Telepon *</label>
                            <input type="text" name="customer_phone" class="form-control @error('customer_phone') is-invalid @enderror" 
                                   value="{{ old('customer_phone') }}" placeholder="08xxxxxxxxxx">
                            @error('customer_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jenis Kendaraan *</label>
                            <select name="vehicle_type" class="form-select @error('vehicle_type') is-invalid @enderror">
                                <option value="">Pilih Jenis Kendaraan</option>
                                <option value="truck" {{ old('vehicle_type') == 'truck' ? 'selected' : '' }}>
                                    Truck (Tersedia: {{ $availableFleets->get('truck', collect())->count() }})
                                </option>
                                <option value="van" {{ old('vehicle_type') == 'van' ? 'selected' : '' }}>
                                    Van (Tersedia: {{ $availableFleets->get('van', collect())->count() }})
                                </option>
                                <option value="motorcycle" {{ old('vehicle_type') == 'motorcycle' ? 'selected' : '' }}>
                                    Motor (Tersedia: {{ $availableFleets->get('motorcycle', collect())->count() }})
                                </option>
                                <option value="car" {{ old('vehicle_type') == 'car' ? 'selected' : '' }}>
                                    Mobil (Tersedia: {{ $availableFleets->get('car', collect())->count() }})
                                </option>
                            </select>
                            @error('vehicle_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Pemesanan *</label>
                            <input type="date" name="order_date" class="form-control @error('order_date') is-invalid @enderror" 
                                   value="{{ old('order_date', date('Y-m-d')) }}" min="{{ date('Y-m-d') }}">
                            @error('order_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
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
                            <i class="bi bi-save"></i> Buat Pesanan
                        </button>
                        <a href="{{ route('orders.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection