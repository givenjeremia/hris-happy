@extends('layouts.base')
@section('title', 'Dashboard')

@section('toolbar')
    @include('components.toolbar', ['title' => 'Dashboard', 'subtitle' => 'Dashboard'])
@endsection

@section('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
    crossorigin=""/>

    <style>
        #map {
            height: 400px;
        }
    </style>
@endsection

@section('content')

<div class="row">
    <div class="col-lg-6">
        <h3>
            Lokasi Anda : 
        </h3>
        {{-- Badget Diluar Lokasi Atau Tidak --}}
        <div></div>
        <div id="map" class=" rounded"></div> 
    </div>
    <div class="col-lg-6">

    </div>
</div>


@endsection 

@section('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
crossorigin=""></script>


<script>
    const map = L.map('map').setView([0, 0], 2);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition((position) => {
            const latitude = position.coords.latitude;
            const longitude = position.coords.longitude;

            map.setView([latitude, longitude], 13);

            const marker = L.marker([latitude, longitude]).addTo(map);
            marker.bindPopup('You are here!').openPopup();
        }, (error) => {
            console.error('Error getting location:', error);
            alert('Unable to retrieve your location.');
        });
    } else {
        alert("Geolocation is not supported by this browser.");
    }

  
</script>

<script>
    function updateCurrentLocation(){

    }
</script>

@endsection
