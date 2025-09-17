{{-- resources/views/fleets/edit.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4><i class="bi bi-pencil"></i> Edit Armada: {{ $fleet->fleet_number }}</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('fleets.update', $fleet) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label">Nomor Armada *</label>
                        <input type="text" name="fleet_number" class="form-control @error('fleet_number') is-invalid @enderror" 
                               value="{{ old('fleet_number', $fleet->fleet_number) }}">
                        @error('fleet_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jenis Kendaraan *</label>
                        <select name="vehicle_type" class="form-select @error('vehicle_type') is-invalid @enderror">
                            <option value="">Pilih Jenis Kendaraan</option>
                            <option value="truck" {{ old('vehicle_type', $fleet->vehicle_type) == 'truck' ? 'selected' : '' }}>Truck</option>
                            <option value="van" {{ old('vehicle_type', $fleet->vehicle_type) == 'van' ? 'selected' : '' }}>Van</option>
                            <option value="motorcycle" {{ old('vehicle_type', $fleet->vehicle_type) == 'motorcycle' ? 'selected' : '' }}>Motor</option>
                            <option value="car" {{ old('vehicle_type', $fleet->vehicle_type) == 'car' ? 'selected' : '' }}>Mobil</option>
                        </select>
                        @error('vehicle_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kapasitas (ton) *</label>
                        <input type="number" name="capacity" step="0.01" min="0" max="999.99"
                               class="form-control @error('capacity') is-invalid @enderror" 
                               value="{{ old('capacity', $fleet->capacity) }}">
                        @error('capacity')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status Ketersediaan</label>
                        <select name="availability" class="form-select @error('availability') is-invalid @enderror">
                            <option value="available" {{ old('availability', $fleet->availability) == 'available' ? 'selected' : '' }}>Tersedia</option>
                            <option value="unavailable" {{ old('availability', $fleet->availability) == 'unavailable' ? 'selected' : '' }}>Tidak Tersedia</option>
                        </select>
                        @error('availability')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Update Armada
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