{{-- resources/views/location/checkin.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="display-5"><i class="bi bi-geo-alt-fill"></i> Check-In Lokasi</h1>
                <p class="text-muted">Lakukan check-in lokasi untuk armada</p>
            </div>
            <a href="{{ route('location.map') }}" class="btn btn-outline-primary">
                <i class="bi bi-map"></i> Lihat Peta
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-plus-circle"></i> Form Check-In</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('location.store') }}" method="POST" id="checkinForm">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="fleet_id" class="form-label">Pilih Armada</label>
                                <select class="form-select @error('fleet_id') is-invalid @enderror" 
                                        id="fleet_id" name="fleet_id" required>
                                    <option value="">-- Pilih Armada --</option>
                                    @foreach($fleets as $fleet)
                                        <option value="{{ $fleet->id }}" 
                                                {{ old('fleet_id') == $fleet->id ? 'selected' : '' }}>
                                            {{ $fleet->fleet_number }} - {{ ucfirst($fleet->vehicle_type) }}
                                            @if($fleet->availability == 'unavailable')
                                                (Tidak Tersedia)
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('fleet_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="location_name" class="form-label">Nama Lokasi</label>
                                <input type="text" class="form-control @error('location_name') is-invalid @enderror" 
                                       id="location_name" name="location_name" 
                                       placeholder="Contoh: Kantor Pusat Jakarta"
                                       value="{{ old('location_name') }}">
                                @error('location_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Opsional - nama lokasi untuk memudahkan identifikasi</div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="latitude" class="form-label">Latitude</label>
                                <input type="number" step="any" 
                                       class="form-control @error('latitude') is-invalid @enderror" 
                                       id="latitude" name="latitude" 
                                       placeholder="Contoh: -6.2088"
                                       value="{{ old('latitude') }}" required>
                                @error('latitude')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="longitude" class="form-label">Longitude</label>
                                <input type="number" step="any" 
                                       class="form-control @error('longitude') is-invalid @enderror" 
                                       id="longitude" name="longitude" 
                                       placeholder="Contoh: 106.8456"
                                       value="{{ old('longitude') }}" required>
                                @error('longitude')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <button type="button" class="btn btn-info" onclick="getCurrentLocation()">
                                    <i class="bi bi-geo-alt"></i> Gunakan Lokasi Saat Ini
                                </button>
                                <span id="locationStatus" class="ms-2 text-muted"></span>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('location.map') }}" class="btn btn-secondary me-md-2">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Check-In Sekarang
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Preview Map -->
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-map"></i> Preview Lokasi</h5>
            </div>
            <div class="card-body p-0">
                <div id="previewMap" style="height: 300px; width: 100%;"></div>
            </div>
            <div class="card-footer">
                <small class="text-muted">Peta akan menampilkan lokasi yang dipilih</small>
            </div>
        </div>

        <!-- Recent Check-ins -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-clock-history"></i> Check-In Terbaru</h5>
            </div>
            <div class="card-body">
                @php
                    $recentCheckIns = \App\Models\LocationCheckIn::with('fleet')
                        ->latest('checked_in_at')
                        ->take(5)
                        ->get();
                @endphp

                @if($recentCheckIns->count() > 0)
                    @foreach($recentCheckIns as $checkIn)
                        <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                            <div>
                                <strong>{{ $checkIn->fleet->fleet_number }}</strong><br>
                                <small class="text-muted">
                                    {{ $checkIn->location_name ?: 'Lokasi tidak diketahui' }}
                                </small><br>
                                <small class="text-info">
                                    {{ $checkIn->checked_in_at->diffForHumans() }}
                                </small>
                            </div>
                            <button class="btn btn-sm btn-outline-primary" 
                                    onclick="showLocation({{ $checkIn->latitude }}, {{ $checkIn->longitude }})">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted">Belum ada check-in terbaru.</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Loading Modal -->
<div class="modal fade" id="loadingModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center">
                <div class="spinner-border text-primary mb-3" role="status"></div>
                <p class="mb-0">Mendapatkan lokasi...</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />

<script>
let previewMap;
let previewMarker;

// Initialize preview map
function initPreviewMap() {
    previewMap = L.map('previewMap').setView([-6.2088, 106.8456], 10);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(previewMap);

    // Add click handler untuk set lokasi
    previewMap.on('click', function(e) {
        setLocationFromMap(e.latlng.lat, e.latlng.lng);
    });
}

// Set lokasi dari klik peta
function setLocationFromMap(lat, lng) {
    document.getElementById('latitude').value = lat.toFixed(8);
    document.getElementById('longitude').value = lng.toFixed(8);
    updatePreviewMarker(lat, lng);
    
    // Reverse geocoding untuk mendapatkan nama lokasi
    reverseGeocode(lat, lng);
}

// Update marker preview
function updatePreviewMarker(lat, lng) {
    if (previewMarker) {
        previewMap.removeLayer(previewMarker);
    }
    
    previewMarker = L.marker([lat, lng]).addTo(previewMap);
    previewMap.setView([lat, lng], 15);
}

// Show location pada preview map
function showLocation(lat, lng) {
    updatePreviewMarker(lat, lng);
}

// Get current location
function getCurrentLocation() {
    if (!navigator.geolocation) {
        alert('Geolocation tidak didukung oleh browser ini.');
        return;
    }

    const statusElement = document.getElementById('locationStatus');
    statusElement.innerHTML = '<i class="bi bi-hourglass-split"></i> Mengambil lokasi...';

    // Show loading modal
    const loadingModal = new bootstrap.Modal(document.getElementById('loadingModal'));
    loadingModal.show();

    navigator.geolocation.getCurrentPosition(
        function(position) {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;
            
            document.getElementById('latitude').value = lat.toFixed(8);
            document.getElementById('longitude').value = lng.toFixed(8);
            
            updatePreviewMarker(lat, lng);
            reverseGeocode(lat, lng);
            
            statusElement.innerHTML = '<i class="bi bi-check-circle text-success"></i> Lokasi berhasil didapat';
            loadingModal.hide();
        },
        function(error) {
            let errorMessage = 'Gagal mendapatkan lokasi: ';
            switch(error.code) {
                case error.PERMISSION_DENIED:
                    errorMessage += 'Akses lokasi ditolak.';
                    break;
                case error.POSITION_UNAVAILABLE:
                    errorMessage += 'Informasi lokasi tidak tersedia.';
                    break;
                case error.TIMEOUT:
                    errorMessage += 'Timeout saat mengambil lokasi.';
                    break;
                default:
                    errorMessage += 'Terjadi kesalahan tidak dikenal.';
                    break;
            }
            
            statusElement.innerHTML = '<i class="bi bi-exclamation-circle text-danger"></i> ' + errorMessage;
            loadingModal.hide();
        },
        {
            enableHighAccuracy: true,
            timeout: 10000,
            maximumAge: 60000
        }
    );
}

// Reverse geocoding untuk mendapatkan nama lokasi
function reverseGeocode(lat, lng) {
    const url = `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`;
    
    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data && data.display_name) {
                // Ambil bagian yang relevan dari alamat
                let locationName = '';
                if (data.address) {
                    const parts = [];
                    if (data.address.road) parts.push(data.address.road);
                    if (data.address.suburb) parts.push(data.address.suburb);
                    if (data.address.city || data.address.town) parts.push(data.address.city || data.address.town);
                    locationName = parts.join(', ');
                } else {
                    locationName = data.display_name.split(',').slice(0, 3).join(', ');
                }
                
                if (locationName) {
                    document.getElementById('location_name').value = locationName;
                }
            }
        })
        .catch(error => {
            console.log('Reverse geocoding failed:', error);
        });
}

// Monitor perubahan input koordinat
function setupCoordinateMonitoring() {
    const latInput = document.getElementById('latitude');
    const lngInput = document.getElementById('longitude');
    
    function updateFromInputs() {
        const lat = parseFloat(latInput.value);
        const lng = parseFloat(lngInput.value);
        
        if (!isNaN(lat) && !isNaN(lng) && lat >= -90 && lat <= 90 && lng >= -180 && lng <= 180) {
            updatePreviewMarker(lat, lng);
        }
    }
    
    latInput.addEventListener('input', updateFromInputs);
    lngInput.addEventListener('input', updateFromInputs);
}

// Form submission handling
document.getElementById('checkinForm').addEventListener('submit', function(e) {
    const lat = parseFloat(document.getElementById('latitude').value);
    const lng = parseFloat(document.getElementById('longitude').value);
    
    if (isNaN(lat) || isNaN(lng)) {
        e.preventDefault();
        alert('Mohon masukkan koordinat yang valid.');
        return;
    }
    
    if (lat < -90 || lat > 90) {
        e.preventDefault();
        alert('Latitude harus antara -90 dan 90.');
        return;
    }
    
    if (lng < -180 || lng > 180) {
        e.preventDefault();
        alert('Longitude harus antara -180 dan 180.');
        return;
    }
    
    // Show loading state
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Processing...';
    
    // Reset button setelah beberapa detik jika masih di halaman yang sama
    setTimeout(() => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    }, 5000);
});

// Initialize saat halaman dimuat
document.addEventListener('DOMContentLoaded', function() {
    initPreviewMap();
    setupCoordinateMonitoring();
});
</script>
@endpush