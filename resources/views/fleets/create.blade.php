{{-- resources/views/fleets/create.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4><i class="bi bi-truck"></i> Tambah Armada Baru</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('fleets.store') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label">Nomor Armada *</label>
                        <input type="text" name="fleet_number" class="form-control @error('fleet_number') is-invalid @enderror" 
                               value="{{ old('fleet_number') }}" placeholder="Contoh: TRK001">
                        @error('fleet_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jenis Kendaraan *</label>
                        <select name="vehicle_type" class="form-select @error('vehicle_type') is-invalid @enderror">
                            <option value="">Pilih Jenis Kendaraan</option>
                            <option value="truck" {{ old('vehicle_type') == 'truck' ? 'selected' : '' }}>Truck</option>
                            <option value="van" {{ old('vehicle_type') == 'van' ? 'selected' : '' }}>Van</option>
                            <option value="motorcycle" {{ old('vehicle_type') == 'motorcycle' ? 'selected' : '' }}>Motor</option>
                            <option value="car" {{ old('vehicle_type') == 'car' ? 'selected' : '' }}>Mobil</option>
                        </select>
                        @error('vehicle_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kapasitas (ton) *</label>
                        <input type="number" name="capacity" step="0.01" min="0" max="999.99"
                               class="form-control @error('capacity') is-invalid @enderror" 
                               value="{{ old('capacity') }}" placeholder="Contoh: 10.5">
                        @error('capacity')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status Ketersediaan</label>
                        <select name="availability" class="form-select @error('availability') is-invalid @enderror">
                            <option value="available" {{ old('availability') == 'available' ? 'selected' : '' }}>Tersedia</option>
                            <option value="unavailable" {{ old('availability') == 'unavailable' ? 'selected' : '' }}>Tidak Tersedia</option>
                        </select>
                        @error('availability')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Simpan Armada
                        </button>
                        <a href="{{ route('fleets.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection