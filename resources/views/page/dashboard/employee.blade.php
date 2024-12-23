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
<div class="card shadow-lg">
    <div class="card-body">
        <div class="row">
            <div class="col-lg-6">
                <h3 class="mb-3">
                    Your Location : 
                    <span id="lat-data" class="font-weight-normal">Loading...</span>, 
                    <span id="long-data" class="font-weight-normal">Loading...</span>
                </h3>
                {{-- Badge untuk status lokasi --}}
                <h4 id="location-client" class="text-muted mb-3">Loading...</h4>
                <div id="map" class="rounded" style="height: 300px;"></div> 
            </div>
            <div class="col-lg-6">
                <h3 class="mb-3">Your Schedule !!</h3>
                <div id="button-check-in-out" class="mb-3">
                    <!-- Button atau aksi lainnya bisa diletakkan di sini -->
                </div>
                <div id="table-3-days-next" class="mt-2">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead class="thead-dark" align="center">
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Shift</th>
                                </tr>
                            </thead>
                            <tbody align="center">
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
                marker.bindPopup('Your Location!').openPopup();
    
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
