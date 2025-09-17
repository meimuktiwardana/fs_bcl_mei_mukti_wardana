{{-- resources/views/location/history.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="display-5">
                    <i class="bi bi-clock-history"></i> Riwayat Lokasi
                </h1>
                <p class="text-muted">
                    Riwayat check-in lokasi untuk armada <strong>{{ $fleet->fleet_number }}</strong>
                </p>
            </div>
            <div>
                <a href="{{ route('location.map') }}" class="btn btn-outline-primary me-2">
                    <i class="bi bi-map"></i> Lihat Peta
                </a>
                <a href="{{ route('location.checkin') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Check-In Baru
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Fleet Info Card -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h5 class="mb-1">
                            <i class="bi bi-truck"></i> {{ $fleet->fleet_number }}
                        </h5>
                        <p class="mb-0 text-muted">
                            <span class="badge bg-primary me-2">{{ ucfirst($fleet->vehicle_type) }}</span>
                            <span class="badge bg-{{ $fleet->availability == 'available' ? 'success' : 'secondary' }}">
                                {{ ucfirst($fleet->availability) }}
                            </span>
                            <span class="ms-2">Kapasitas: {{ $fleet->capacity }} ton</span>
                        </p>
                    </div>
                    <div class="col-md-4 text-md-end">
                        @if($fleet->latestLocation)
                            <small class="text-muted">
                                <i class="bi bi-geo-alt"></i> Lokasi Terakhir:<br>
                                <strong>{{ $fleet->latestLocation->location_name ?: 'Tidak diketahui' }}</strong><br>
                                {{ $fleet->latestLocation->checked_in_at->diffForHumans() }}
                            </small>
                        @else
                            <small class="text-danger">
                                <i class="bi bi-exclamation-circle"></i> Belum ada check-in
                            </small>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Timeline -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-clock-history"></i> Timeline Check-In</h5>
                    <small class="text-muted">
                        Total: {{ $locations->total() }} check-in
                    </small>
                </div>
            </div>
            <div class="card-body">
                @if($locations->count() > 0)
                    <div class="timeline">
                        @foreach($locations as $index => $location)
                            <div class="timeline-item {{ $index === 0 ? 'latest' : '' }}">
                                <div class="timeline-marker">
                                    @if($index === 0)
                                        <i class="bi bi-geo-alt-fill text-success"></i>
                                    @else
                                        <i class="bi bi-circle-fill text-secondary"></i>
                                    @endif
                                </div>
                                <div class="timeline-content">
                                    <div class="card {{ $index === 0 ? 'border-success' : '' }}">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <h6 class="mb-1">
                                                        {{ $location->location_name ?: 'Lokasi Tidak Diketahui' }}
                                                        @if($index === 0)
                                                            <span class="badge bg-success ms-2">Terbaru</span>
                                                        @endif
                                                    </h6>
                                                    <p class="text-muted mb-2">
                                                        <i class="bi bi-geo-alt"></i>
                                                        {{ $location->latitude }}, {{ $location->longitude }}
                                                    </p>
                                                    <small class="text-info">
                                                        <i class="bi bi-clock"></i>
                                                        {{ $location->checked_in_at->format('d M Y, H:i') }} WIB
                                                        ({{ $location->checked_in_at->diffForHumans() }})
                                                    </small>
                                                </div>
                                                <div class="col-md-4 text-md-end">
                                                    <button class="btn btn-sm btn-outline-primary" 
                                                            onclick="showOnMap({{ $location->latitude }}, {{ $location->longitude }}, '{{ addslashes($location->location_name ?: 'Lokasi Tidak Diketahui') }}')">
                                                        <i class="bi bi-eye"></i> Lihat di Peta
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $locations->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-clock-history text-muted" style="font-size: 3rem;"></i>
                        <h5 class="text-muted mt-3">Belum Ada Riwayat Check-In</h5>
                        <p class="text-muted">Armada ini belum melakukan check-in lokasi.</p>
                        <a href="{{ route('location.checkin') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Check-In Sekarang
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Map & Stats -->
    <div class="col-lg-4">
        <!-- Map -->
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-map"></i> Peta Lokasi</h5>
            </div>
            <div class="card-body p-0">
                <div id="historyMap" style="height: 300px; width: 100%;"></div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-graph-up"></i> Statistik</h5>
            </div>
            <div class="card-body">
                @php
                    $stats = [
                        'total' => $locations->total(),
                        'today' => $fleet->locationCheckIns()->whereDate('checked_in_at', today())->count(),
                        'week' => $fleet->locationCheckIns()->where('checked_in_at', '>=', now()->subWeek())->count(),
                        'month' => $fleet->locationCheckIns()->where('checked_in_at', '>=', now()->subMonth())->count()
                    ];
                @endphp

                <div class="row text-center">
                    <div class="col-6 mb-3">
                        <h4 class="text-primary">{{ $stats['total'] }}</h4>
                        <small class="text-muted">Total Check-In</small>
                    </div>
                    <div class="col-6 mb-3">
                        <h4 class="text-success">{{ $stats['today'] }}</h4>
                        <small class="text-muted">Hari Ini</small>
                    </div>
                    <div class="col-6">
                        <h4 class="text-info">{{ $stats['week'] }}</h4>
                        <small class="text-muted">Minggu Ini</small>
                    </div>
                    <div class="col-6">
                        <h4 class="text-warning">{{ $stats['month'] }}</h4>
                        <small class="text-muted">Bulan Ini</small>
                    </div>
                </div>

                @if($fleet->latestLocation)
                    <hr>
                    <div class="text-center">
                        <h6>Status Terakhir</h6>
                        @php
                            $lastCheckIn = $fleet->latestLocation->checked_in_at;
                            $hoursAgo = $lastCheckIn->diffInHours(now());
                        @endphp
                        
                        @if($hoursAgo <= 1)
                            <span class="badge bg-success">Sangat Aktif</span>
                        @elseif($hoursAgo <= 6)
                            <span class="badge bg-warning">Aktif</span>
                        @elseif($hoursAgo <= 24)
                            <span class="badge bg-info">Cukup Aktif</span>
                        @else
                            <span class="badge bg-danger">Tidak Aktif</span>
                        @endif
                        
                        <br>
                        <small class="text-muted mt-1">
                            Terakhir {{ $hoursAgo }} jam yang lalu
                        </small>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Map Modal -->
<div class="modal fade" id="mapModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-map"></i> Detail Lokasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <div id="detailMap" style="height: 500px; width: 100%;"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 10px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #dee2e6;
}

.timeline-item {
    position: relative;
    margin-bottom: 2rem;
}

.timeline-marker {
    position: absolute;
    left: -25px;
    top: 10px;
    width: 20px;
    height: 20px;
    background: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 10px;
}

.timeline-item.latest .timeline-marker {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { box-shadow: 0 0 0 0 rgba(40, 167, 69, 0.7); }
    70% { box-shadow: 0 0 0 10px rgba(40, 167, 69, 0); }
    100% { box-shadow: 0 0 0 0 rgba(40, 167, 69, 0); }
}

.timeline-content {
    margin-left: 0;
}
</style>

<script>
let historyMap;
let detailMap;
let locationData = @json($locations->items());

// Initialize history map
function initHistoryMap() {
    historyMap = L.map('historyMap');
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(historyMap);

    if (locationData.length > 0) {
        let bounds = [];
        
        locationData.forEach(function(location, index) {
            let lat = parseFloat(location.latitude);
            let lng = parseFloat(location.longitude);
            
            // Marker dengan warna berbeda untuk lokasi terbaru
            let color = index === 0 ? 'red' : 'blue';
            let icon = L.divIcon({
                html: `<div style="background-color: ${color}; width: 12px; height: 12px; border-radius: 50%; border: 2px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.3);"></div>`,
                iconSize: [12, 12],
                iconAnchor: [6, 6]
            });
            
            let marker = L.marker([lat, lng], { icon: icon }).addTo(historyMap);
            
            let popupContent = `
                <div>
                    <strong>${location.location_name || 'Lokasi Tidak Diketahui'}</strong><br>
                    <small>${new Date(location.checked_in_at).toLocaleString('id-ID')}</small>
                </div>
            `;
            
            marker.bindPopup(popupContent);
            bounds.push([lat, lng]);
        });
        
        // Fit map ke semua lokasi
        if (bounds.length > 0) {
            historyMap.fitBounds(bounds, { padding: [10, 10] });
        }
    } else {
        // Default view jika tidak ada data
        historyMap.setView([-6.2088, 106.8456], 10);
    }
}

// Show location on detail map
function showOnMap(lat, lng, locationName) {
    // Initialize detail map jika belum ada
    if (!detailMap) {
        setTimeout(() => {
            detailMap = L.map('detailMap').setView([lat, lng], 15);
            
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(detailMap);
            
            let marker = L.marker([lat, lng]).addTo(detailMap);
            marker.bindPopup(`<strong>${locationName}</strong>`).openPopup();
        }, 300);
    } else {
        detailMap.setView([lat, lng], 15);
        detailMap.eachLayer(function(layer) {
            if (layer instanceof L.Marker) {
                detailMap.removeLayer(layer);
            }
        });
        
        let marker = L.marker([lat, lng]).addTo(detailMap);
        marker.bindPopup(`<strong>${locationName}</strong>`).openPopup();
    }
    
    // Show modal
    let modal = new bootstrap.Modal(document.getElementById('mapModal'));
    modal.show();
    
    // Invalidate size setelah modal terbuka
    document.getElementById('mapModal').addEventListener('shown.bs.modal', function() {
        if (detailMap) {
            detailMap.invalidateSize();
        }
    });
}

// Initialize maps saat halaman dimuat
document.addEventListener('DOMContentLoaded', function() {
    initHistoryMap();
});
</script>
@endpush