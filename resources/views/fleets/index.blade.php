{{-- resources/views/fleets/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Manajemen Armada</h2>
    <a href="{{ route('fleets.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Tambah Armada
    </a>
</div>

<!-- Filter Form -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Jenis Kendaraan</label>
                <select name="vehicle_type" class="form-select">
                    <option value="">Semua</option>
                    <option value="truck" {{ request('vehicle_type') == 'truck' ? 'selected' : '' }}>Truck</option>
                    <option value="van" {{ request('vehicle_type') == 'van' ? 'selected' : '' }}>Van</option>
                    <option value="motorcycle" {{ request('vehicle_type') == 'motorcycle' ? 'selected' : '' }}>Motor</option>
                    <option value="car" {{ request('vehicle_type') == 'car' ? 'selected' : '' }}>Mobil</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Status</label>
                <select name="availability" class="form-select">
                    <option value="">Semua</option>
                    <option value="available" {{ request('availability') == 'available' ? 'selected' : '' }}>Tersedia</option>
                    <option value="unavailable" {{ request('availability') == 'unavailable' ? 'selected' : '' }}>Tidak Tersedia</option>
                </select>
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-outline-primary me-2">Filter</button>
                <a href="{{ route('fleets.index') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<!-- Fleet Table -->
<div class="card">
    <div class="card-body">
        @if(isset($fleets) && $fleets->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nomor Armada</th>
                            <th>Jenis Kendaraan</th>
                            <th>Kapasitas</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($fleets as $fleet)
                            <tr>
                                <td><strong>{{ $fleet->fleet_number }}</strong></td>
                                <td>{{ ucfirst($fleet->vehicle_type) }}</td>
                                <td>{{ $fleet->capacity }} ton</td>
                                <td>
                                    <span class="badge bg-{{ $fleet->availability == 'available' ? 'success' : 'danger' }}">
                                        {{ $fleet->availability == 'available' ? 'Tersedia' : 'Tidak Tersedia' }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('fleets.show', $fleet) }}" class="btn btn-sm btn-outline-info">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('fleets.edit', $fleet) }}" class="btn btn-sm btn-outline-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST" action="{{ route('fleets.destroy', $fleet) }}" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                onclick="return confirm('Yakin ingin menghapus?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $fleets->links() }}
        @else
            <div class="text-center py-4">
                <i class="bi bi-truck display-1 text-muted"></i>
                <p class="mt-2">Belum ada data armada.</p>
                <a href="{{ route('fleets.create') }}" class="btn btn-primary">Tambah Armada Pertama</a>
            </div>
        @endif
    </div>
</div>
@endsection