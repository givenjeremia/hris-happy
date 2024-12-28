<div class="container mt-4">
    <div class="row">
        <div class="col-md-6">
            <button 
                class="btn btn-outline-primary btn-block" 
                onclick="handlePresence('check-in')" 
                {{ $allClockedOut ? 'disabled' : ($presense ? 'disabled' : '') }}>
                <i class="fas fa-bell"></i> Check In
            </button>
        </div>
        <div class="col-md-6">
            <button 
                class="btn btn-outline-danger btn-block" 
                onclick="handlePresence('check-out')" 
                {{ $allClockedOut ? 'disabled' : (!$presense ? 'disabled' : '') }}>
                <i class="fas fa-sign-out-alt"></i> Check Out
            </button>
        </div>
    </div>
</div>



<script>
    function handlePresence(action) {
        getGeolocation(function(latitude, longitude) {
            const isCheckIn = action === 'check-in';

            // Set appropriate Swal messages
            const title = isCheckIn ? "Absensi In" : "Absensi Out";
            const confirmationMessage = "Are you sure?";
            const actionUrl = isCheckIn 
                ? '{{ route("presences.store") }}' 
                : '{{ route("presences.update", ":uuid") }}'.replace(':uuid', '{{ $presense->uuid ?? "" }}');

            const method = isCheckIn ? 'POST' : 'PUT';

            Swal.fire({
                title: title,
                text: confirmationMessage,
                icon: 'warning',
                target: document.getElementById('content'),
                reverseButtons: true,
                confirmButtonText: "Yes",
                showCancelButton: true,
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    let form_data = new FormData();
                    form_data.append('long', longitude);
                    form_data.append('lat', latitude);
                    form_data.append('_token', '{{ csrf_token() }}');
                    if (!isCheckIn) {
                        form_data.append('_method', 'put');
                    }

                    $.ajax({
                        url: actionUrl,
                        type: "POST",
                        data: form_data,
                        dataType: "json",
                        contentType: false,
                        processData: false,
                        success: function(data) {
                            if (data.status === "success") {
                                Swal.fire({
                                    title: data.msg,
                                    icon: 'success'
                                }).then(() => {
                                    updateCurrentLocation(latitude, longitude);
                                });
                            } else {
                                Swal.fire({
                                    title: data.msg,
                                    icon: 'error'
                                });
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            Swal.fire({
                                title: textStatus,
                                text: errorThrown,
                                icon: 'error'
                            });
                            console.error(textStatus, errorThrown);
                        }
                    });
                }
            });
        });
    }
</script>
