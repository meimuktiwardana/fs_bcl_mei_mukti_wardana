{{-- resources/views/location/map.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="display-5"><i class="bi bi-geo-alt"></i> Peta Lokasi Armada</h1>
                <p class="text-muted">Monitor lokasi real-time semua armada</p>
            </div>
            <div>
                <a href="{{ route('location.checkin') }}" class="btn btn-primary me-2">
                    <i class="bi bi-plus-circle"></i> Check-In Lokasi
                </a>
                <a href="{{ route('location.report') }}" class="btn btn-outline-info">
                    <i class="bi bi-graph-up"></i> Laporan
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Statistik Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>{{ $fleets->count() }}</h4>
                        <p class="mb-0">Total Armada</p>
                    </div>
                    <i class="bi bi-truck fs-1"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>{{ $activeFleets }}</h4>
                        <p class="mb-0">Armada Aktif</p>
                    </div>
                    <i class="bi bi-check-circle fs-1"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>{{ $todayCheckIns }}</h4>
                        <p class="mb-0">Check-In Hari Ini</p>
                    </div>
                    <i class="bi bi-clock fs-1"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>{{ $fleets->where('availability', 'available')->count() }}</h4>
                        <p class="mb-0">Tersedia</p>
                    </div>
                    <i class="bi bi-truck-flatbed fs-1"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Map Container -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-map"></i> Peta Lokasi Real-time</h5>
                    <div>
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="refreshMap()">
                            <i class="bi bi-arrow-clockwise"></i> Refresh
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="centerMap()">
                            <i class="bi bi-crosshair"></i> Center
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div id="map" style="height: 500px; width: 100%;"></div>
            </div>
        </div>
    </div>

    <!-- Fleet List -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-list-ul"></i> Daftar Armada</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <input type="text" class="form-control" id="fleetSearch" placeholder="Cari armada...">
                </div>
                
                <div id="fleetList" style="max-height: 400px; overflow-y: auto;">
                    @forelse($fleets as $fleet)
                        <div class="fleet-item border rounded p-3 mb-2" data-fleet-id="{{ $fleet->id }}">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">
                                        <i class="bi bi-truck"></i> {{ $fleet->fleet_number }}
                                    </h6>
                                    <small class="text-muted">
                                        {{ ucfirst($fleet->vehicle_type) }} - 
                                        <span class="badge bg-{{ $fleet->availability == 'available' ? 'success' : 'secondary' }}">
                                            {{ ucfirst($fleet->availability) }}
                                        </span>
                                    </small>
                                    
                                    @if($fleet->latestLocation)
                                        <div class="mt-2">
                                            <small class="text-info">
                                                <i class="bi bi-geo-alt"></i>
                                                {{ $fleet->latestLocation->location_name ?? 'Lokasi tidak diketahui' }}
                                            </small><br>
                                            <small class="text-muted">
                                                <i class="bi bi-clock"></i>
                                                {{ $fleet->latestLocation->checked_in_at->diffForHumans() }}
                                            </small>
                                        </div>
                                    @else
                                        <div class="mt-2">
                                            <small class="text-danger">
                                                <i class="bi bi-exclamation-circle"></i> Belum ada lokasi
                                            </small>
                                        </div>
                                    @endif

                                    @if($fleet->shipments->count() > 0)
                                        <div class="mt-1">
                                            <small class="text-warning">
                                                <i class="bi bi-box"></i> {{ $fleet->shipments->count() }} pengiriman aktif
                                            </small>
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="btn-group-vertical btn-group-sm">
                                    @if($fleet->latestLocation)
                                        <button type="button" class="btn btn-outline-primary btn-sm" 
                                                onclick="focusFleet({{ $fleet->id }})">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    @endif
                                    <a href="{{ route('location.history', $fleet) }}" 
                                       class="btn btn-outline-info btn-sm">
                                        <i class="bi bi-clock-history"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-4">
                            <i class="bi bi-truck fs-1"></i>
                            <p>Belum ada data armada</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Legend -->
<div class="row mt-3">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h6><i class="bi bi-info-circle"></i> Keterangan:</h6>
                <div class="row">
                    <div class="col-md-3">
                        <span class="badge bg-success me-1">●</span> Tersedia
                    </div>
                    <div class="col-md-3">
                        <span class="badge bg-warning me-1">●</span> Dalam Perjalanan
                    </div>
                    <div class="col-md-3">
                        <span class="badge bg-secondary me-1">●</span> Tidak Tersedia
                    </div>
                    <div class="col-md-3">
                        <span class="badge bg-danger me-1">●</span> Tidak Aktif (>24h)
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />

<script>
let map;
let markers = [];
let fleetData = @json($fleets);

// Inisialisasi peta
function initMap() {
    // Center di Indonesia (Jakarta)
    map = L.map('map').setView([-6.2088, 106.8456], 10);
    
    // Tambah tile layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);
    
    // Load markers
    loadMarkers();
}

// Load markers dari data armada
function loadMarkers() {
    // Clear existing markers
    markers.forEach(marker => {
        map.removeLayer(marker);
    });
    markers = [];
    
    fleetData.forEach(fleet => {
        if (fleet.latest_location) {
            let marker = createFleetMarker(fleet);
            markers.push(marker);
            marker.addTo(map);
        }
    });
    
    // Fit map ke semua markers jika ada
    if (markers.length > 0) {
        let group = new L.featureGroup(markers);
        map.fitBounds(group.getBounds().pad(0.1));
    }
}

// Buat marker untuk armada
function createFleetMarker(fleet) {
    let lat = parseFloat(fleet.latest_location.latitude);
    let lng = parseFloat(fleet.latest_location.longitude);
    
    // Tentukan warna berdasarkan status
    let color = 'gray';
    let status = 'Tidak Aktif';
    
    if (fleet.availability === 'available') {
        color = 'green';
        status = 'Tersedia';
    } else if (fleet.shipments_count > 0) {
        color = 'orange';
        status = 'Dalam Perjalanan';
    } else if (fleet.availability === 'unavailable') {
        color = 'red';
        status = 'Tidak Tersedia';
    }
    
    // Cek apakah masih aktif (dalam 24 jam)
    let lastCheckIn = new Date(fleet.latest_location.checked_in_at);
    let now = new Date();
    let hoursDiff = (now - lastCheckIn) / (1000 * 60 * 60);
    
    if (hoursDiff > 24) {
        color = 'red';
        status = 'Tidak Aktif';
    }
    
    // Custom icon
    let icon = L.divIcon({
        html: `<div style="background-color: ${color}; width: 20px; height: 20px; border-radius: 50%; border: 2px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.3);"></div>`,
        iconSize: [20, 20],
        iconAnchor: [10, 10]
    });
    
    let marker = L.marker([lat, lng], { icon: icon });
    
    // Popup content
    let popupContent = `
        <div class="p-2">
            <h6><i class="bi bi-truck"></i> ${fleet.fleet_number}</h6>
            <p class="mb-1"><strong>Jenis:</strong> ${fleet.vehicle_type.charAt(0).toUpperCase() + fleet.vehicle_type.slice(1)}</p>
            <p class="mb-1"><strong>Status:</strong> <span class="badge bg-secondary">${status}</span></p>
            <p class="mb-1"><strong>Lokasi:</strong> ${fleet.latest_location.location_name || 'Tidak diketahui'}</p>
            <p class="mb-1"><strong>Check-in:</strong> ${new Date(fleet.latest_location.checked_in_at).toLocaleString('id-ID')}</p>
            ${fleet.shipments_count > 0 ? `<p class="mb-1"><strong>Pengiriman Aktif:</strong> ${fleet.shipments_count}</p>` : ''}
            <div class="mt-2">
                <a href="/location/fleet/${fleet.id}/history" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-clock-history"></i> Riwayat
                </a>
            </div>
        </div>
    `;
    
    marker.bindPopup(popupContent);
    marker.fleetId = fleet.id;
    
    return marker;
}

// Focus ke armada tertentu
function focusFleet(fleetId) {
    let marker = markers.find(m => m.fleetId === fleetId);
    if (marker) {
        map.setView(marker.getLatLng(), 15);
        marker.openPopup();
    }
}

// Refresh peta
function refreshMap() {
    fetch('/api/locations')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                fleetData = data.data;
                loadMarkers();
                showToast('Peta berhasil diperbarui', 'success');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Gagal memperbarui peta', 'error');
        });
}

// Center peta
function centerMap() {
    if (markers.length > 0) {
        let group = new L.featureGroup(markers);
        map.fitBounds(group.getBounds().pad(0.1));
    } else {
        map.setView([-6.2088, 106.8456], 10);
    }
}

// Search armada
document.getElementById('fleetSearch').addEventListener('input', function() {
    let searchTerm = this.value.toLowerCase();
    let fleetItems = document.querySelectorAll('.fleet-item');
    
    fleetItems.forEach(item => {
        let fleetNumber = item.querySelector('h6').textContent.toLowerCase();
        if (fleetNumber.includes(searchTerm)) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
});

// Toast notification
function showToast(message, type = 'info') {
    let toastHtml = `
        <div class="toast align-items-center text-white bg-${type === 'success' ? 'success' : 'danger'} border-0" role="alert">
            <div class="d-flex">
                <div class="toast-body">${message}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    `;
    
    let toastContainer = document.getElementById('toast-container') || createToastContainer();
    toastContainer.innerHTML = toastHtml;
    
    let toast = new bootstrap.Toast(toastContainer.querySelector('.toast'));
    toast.show();
}

function createToastContainer() {
    let container = document.createElement('div');
    container.id = 'toast-container';
    container.className = 'toast-container position-fixed top-0 end-0 p-3';
    container.style.zIndex = '11';
    document.body.appendChild(container);
    return container;
}

// Initialize map saat halaman dimuat
document.addEventListener('DOMContentLoaded', function() {
    initMap();
    
    // Auto refresh setiap 5 menit
    setInterval(refreshMap, 300000);
});
</script>
@endpush