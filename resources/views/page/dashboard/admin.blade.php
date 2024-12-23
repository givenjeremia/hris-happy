@extends('layouts.base')
@section('title', 'Dashboard Admin')

@section('toolbar')
    @include('components.toolbar', ['title' => 'Dashboard Admin', 'subtitle' => 'Admin'])
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
<div class="card shadow-lg">
    <div class="card-body">
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>Vacation</h3>
                        <p>Manage Vacations</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-plane"></i>
                    </div>
                    <a href="{{ route('vacations.index') }}" class="small-box-footer">
                        More info <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>Presences</h3>
                        <p>Track Presences</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <a href="{{ route('presences.index') }}" class="small-box-footer">
                        More info <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>Employee</h3>
                        <p>View Employees</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-users"></i>
                    </div>
                    @if (auth()->user()->hasRole('admin'))
                        <a href="{{ route('employee.index') }}" class="small-box-footer">
                            More info <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    @endif
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>Overtime</h3>
                        <p>Manage Overtime</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <a href="{{ route('overtimes.index') }}" class="small-box-footer">
                        More info <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-lg">
    <div class="card-body">
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
