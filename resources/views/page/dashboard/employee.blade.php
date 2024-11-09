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
            Lokasi Anda <span id="lat-data">Loading...</span>, <span id="long-data">Loading...</span>
        </h3>
        {{-- Badget Diluar Lokasi Atau Tidak --}}
        <h4>
            <span id="location-client">Loading...</span>
        </h4> 
        <div id="map" class=" rounded"></div> 
    </div>
    <div class="col-lg-6">
        <h3>
            Jadwal Anda!
        </h3>
        <div id="button-check-in-out">
           
        </div>
        <div id="table-3-days-next" class="mt-2">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th scope="col">No</th>
                        <th scope="col">Date</th>
                        <th scope="col">Shift</th>
                      </tr>
                    </thead>
                    <tbody>
                        @foreach ($data_3_day_schedule as $index => $item)
                            <tr>
                            <th scope="row">{{ $index + 1 }}</th>
                            <td>{{ $item->date }}</td>
                            <td>{{ $item->shift->name }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>


@endsection 

@section('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>


<script>
    const map = L.map('map').setView([0, 0], 2);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    function updateCurrentLocation(lat,long){
        $.ajax({
            url: "{{ route('update.current.location') }}",
            method: "GET",
            data: {
                latitude: lat,
                longitude: long
            },
            dataType: "json",
            success: function(response) {
                if (response.status === 'success') {
                    $('#location-client').html(response.data)
                    $('#button-check-in-out').html(response.render_button)
                } else {
                    alert('Error: ' + response.msg);
                }
            },
            error: function(xhr) {
                alert('Failed to load form: ' + xhr.responseJSON.msg);
            }
        });
        

    }

    function getGeolocation(callback){
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition((position) => {
                let latitude = position.coords.latitude;
                let longitude = position.coords.longitude;
    
                map.setView([latitude, longitude], 13);
                const marker = L.marker([latitude, longitude]).addTo(map);
                marker.bindPopup('Lokasi Kamu Disini!').openPopup();
    
                $('#lat-data').html(latitude)
                $('#long-data').html(longitude)

                callback(latitude, longitude);
    
            }, (error) => {
                console.error('Error location:', error);
                alert('Perikasa Kembali.');
            });
        } else {
            alert("Geolocation tidak support pada browser anda.");
        }
    }
    
    getGeolocation(function(latitude, longitude) {
        updateCurrentLocation(latitude, longitude);
    });


</script>


@endsection
