<!-- resources/views/location/map.blade.php -->
@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <h4>Lokasi Armada</h4>
        <div id="map" style="height: 500px;"></div>
    </div>
</div>

<script>
// Implementasi dengan Leaflet.js atau Google Maps
// Contoh dengan Leaflet
const map = L.map('map').setView([-7.7956, 110.3695], 10); // Yogyakarta

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

@foreach($fleets as $fleet)
    @if($fleet->latestLocation)
        L.marker([{{ $fleet->latestLocation->latitude }}, {{ $fleet->latestLocation->longitude }}])
         .addTo(map)
         .bindPopup('{{ $fleet->fleet_number }}<br>{{ $fleet->latestLocation->location_name }}');
    @endif
@endforeach
</script>
@endsection