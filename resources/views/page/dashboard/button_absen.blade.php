<div class="container mt-4">
    <div class="row">
        <div class="col-md-6">
            <button class="btn btn-outline-primary btn-block" onclick="absenMasuk()" {{ $presense ? 'disabled' : '' }}>
                <i class="fas fa-bell"></i> Check In
            </button>
        </div>
        <div class="col-md-6">
            <button class="btn btn-outline-danger btn-block" onclick="absenKeluar()" {{ !$presense ? 'disabled' : '' }}>
                <i class="fas fa-sign-out-alt"></i> Check Out
            </button>
        </div>
    </div>
</div>

@if (!$presense)
<script>
    function absenMasuk(){
        getGeolocation(function(latitude, longitude) {
            Swal.fire({
                title: "Absensi In",
                text: "Are you sure?"
                , icon: 'warning'
                , target: document.getElementById('content')
                , reverseButtons: true
                , confirmButtonText: "Yes"
                , showCancelButton: true
                , cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    let act = '{{ route("presences.store") }}'
                    let form_data = new FormData();
                    form_data.append('long',longitude)
                    form_data.append('lat',latitude)
                    form_data.append('_token', '{{ csrf_token() }}')
                    $.ajax({
                        url: act
                        , type: "POST"
                        , data: form_data
                        , dataType: "json"
                        , contentType: false
                        , processData: false
                        , success: function(data) {

                            if (data.status == "success") {
                                Swal.fire({
                                    title: data.msg
                                    , icon:'success'
                                }).then(function(result) {

                                    updateCurrentLocation(latitude, longitude);
                                    
                                });

                            } else {
                                Swal.fire({
                                    title:  data.msg,
                                    icon:'error'
                                })
                            
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                
                            Swal.fire({
                                title:  textStatus,
                                text: errorThrown,
                                icon:'error', 
                            })
                            console.log(textStatus, errorThrown);
                        }

                    })

                }
            })
        });
    }
</script>
@endif


@if ($presense)
<script>
    function absenKeluar(){
        getGeolocation(function(latitude, longitude) {
            Swal.fire({
                title: "Absensi Out",
                text: "Are you sure?"
                , icon: 'warning'
                , target: document.getElementById('content')
                , reverseButtons: true
                , confirmButtonText: "Yes"
                , showCancelButton: true
                , cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    let act = '{{ route("presences.update", ":uuid") }}'.replace(':uuid','{{ $presense->uuid }}')
                    let form_data = new FormData();
                    form_data.append('long',longitude)
                    form_data.append('lat',latitude)
                    form_data.append('_method', 'put')
                    form_data.append('_token', '{{ csrf_token() }}')
                    $.ajax({
                        url: act
                        , type: "POST"
                        , data: form_data
                        , dataType: "json"
                        , contentType: false
                        , processData: false
                        , success: function(data) {

                            if (data.status == "success") {
                                Swal.fire({
                                    title: data.msg
                                    , icon:'success'
                                }).then(function(result) {

                                    updateCurrentLocation(latitude, longitude);
                                    
                                });

                            } else {
                                Swal.fire({
                                    title:  data.msg,
                                    icon:'error'
                                })
                            
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                
                            Swal.fire({
                                title:  textStatus,
                                text: errorThrown,
                                icon:'error', 
                            })
                            console.log(textStatus, errorThrown);
                        }

                    })

                }
            })
        });
    }
</script>
    
@endif