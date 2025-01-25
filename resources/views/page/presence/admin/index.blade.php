@extends('layouts.base')
@section('title', 'Presence')

@section('toolbar')
    @include('components.toolbar', ['title' => 'Presence', 'subtitle' => 'Presence'])
@endsection

@section('content')
<ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
    <li class="nav-item">
      <a class="nav-link active" onclick="clickRiwayatTable()" id="custom-tabs-four-home-tab" data-toggle="pill" href="#custom-tabs-four-home" role="tab" aria-controls="custom-tabs-four-home" aria-selected="true">Riwayat</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" onclick="clickPegawaiTable()" id="custom-tabs-four-profile-tab" data-toggle="pill" href="#custom-tabs-four-profile" role="tab" aria-controls="custom-tabs-four-profile" aria-selected="false">Impersonate Pegawai Absen</a>
    </li>
</ul>


<div class="tab-content my-2" id="custom-tabs-four-tabContent">
    <div class="tab-pane fade active show" id="custom-tabs-four-home" role="tabpanel" aria-labelledby="custom-tabs-four-home-tab">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Tabel Data Riwayat</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="tableDataRiwayat" class="table table-bordered table-striped">
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="tab-pane fade" id="custom-tabs-four-profile" role="tabpanel" aria-labelledby="custom-tabs-four-profile-tab">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Tabel Data Impersonate Absensi Pegawai Hari Ini</h3>
               
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="tablePegawaiAbsen" class="table table-bordered table-striped">
                    </table>
                </div>
            </div>
        </div>
    </div>

  </div>

<div id="modal-div"></div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/dompurify/2.3.8/purify.min.js"></script>  
<script>
    function riwayatTable() {
        if ($.fn.DataTable.isDataTable('#tableDataRiwayat')) {  
            $('#tableDataRiwayat').DataTable().destroy();  
        }  
        var datatableRw = $('#tableDataRiwayat').DataTable({
            "columnDefs": [{
                "defaultContent": "-",
                "targets": "_all"
            }],
            processing: true,
            serverSide: true,
            ajax: "{{ route('presences.table') }}",
            columns: [{
                    data: 'No',
                    name: 'No',
                    title: 'No',
                    className: 'px-5 text-nowrap'
                },
                {
                    data: 'Employee',
                    name: 'Employee',
                    title: 'Employee',
                    className: 'text-nowrap'
                },
                {
                    data: 'Date',
                    name: 'Date',
                    title: 'Date',
                    className: 'text-nowrap'
                },
                {
                    data: 'Lat In',
                    name: 'Lat In',
                    title: 'Lat In',
                    className: 'text-nowrap'
                },
                {
                    data: 'Long In',
                    name: 'Long In',
                    title: 'Long In',
                    className: 'text-nowrap'
                },
                {
                    data: 'Time In',
                    name: 'Time In',
                    title: 'Time In',
                    className: 'text-nowrap'
                },
                {
                    data: 'Lat Out',
                    name: 'Lat Out',
                    title: 'Lat Out',
                    className: 'text-nowrap'
                },
                {
                    data: 'Long Out',
                    name: 'Long Out',
                    title: 'Long Out',
                    className: 'text-nowrap'
                },
                {
                    data: 'Time Out',
                    name: 'Time Out',
                    title: 'Time Out',
                    className: 'text-nowrap'
                },
                {
                    data: 'Status',
                    name: 'Status',
                    title: 'Status',
                    className: 'text-nowrap'
                },
                {
                    data: 'Information',
                    name: 'Information',
                    title: 'Information',
                    className: 'text-nowrap'
                },

            ],

        });
    }
    riwayatTable()
    

    function pegawaiTable() {
        if ($.fn.DataTable.isDataTable('#tablePegawaiAbsen')) {  
            $('#tablePegawaiAbsen').DataTable().destroy();  
        }  
        var datatablePegawai = $('#tablePegawaiAbsen').DataTable({
            "columnDefs": [{
                "defaultContent": "-",
                "targets": "_all"
            }],
            processing: true,
            serverSide: true,
            ajax: "{{ route('presences.table.pegawai.absen') }}",
            columns: [{
                    data: 'No',
                    name: 'No',
                    title: 'No',
                    className: 'px-5 text-nowrap'
                },
                {
                    data: 'Employee',
                    name: 'Employee',
                    title: 'Employee',
                    className: 'text-nowrap'
                },
                {
                    data: 'Date',
                    name: 'Date',
                    title: 'Date',
                    className: 'text-nowrap'
                },
                {
                    data: 'Shift',
                    name: 'Shift',
                    title: 'Shift',
                    className: 'text-nowrap'
                },
                {
                    data: 'Time In',
                    name: 'Time In',
                    title: 'Time In',
                    className: 'text-nowrap'
                },
                {
                    data: 'Time Out',
                    name: 'Time Out',
                    title: 'Time Out',
                    className: 'text-nowrap'
                },
                {
                    data: 'Status Absen',
                    name: 'Status Absen',
                    title: 'Status Absen',
                    className: 'text-nowrap',
                
                },
                {
                    data: 'Action',
                    name: 'Action',
                    title: 'Action',
                    className: 'text-nowrap'
                },
                
 

            ],

        });
    }

    function clickRiwayatTable(){
        riwayatTable()
    }

    function clickPegawaiTable(){
        pegawaiTable()
    }

</script>
<script>
    function updateAbsen(data, textData) {
        Swal.fire({
            title: 'Are You Sure ?',
            text: textData,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya',
            cancelButtonText: 'Tidak'
        }).then((result) => {
            if (result.isConfirmed) {
                let url = "{{ route('presences.pegawai.absen.post', ':uuid') }}".replace(':uuid', data)
                $.ajax({
                    url: url,
                    method: "POST",
                    data: {
                        "_token": "{{ csrf_token() }}",
                    },
                    success: function(response) {

                        if (response.status == 'success') {
                            Swal.fire({
                                title: response.msg,
                                icon: 'success',
                                confirmButtonText: "Oke"
                            }).then(function(result) {
                                $('#tablePegawaiAbsen').DataTable().ajax.reload();
                            });

                        } else {
                            Swal.fire({
                                title: response.msg,
                                icon: 'error',
                                confirmButtonText: "Oke"
                            })
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            title: 'Failed, Server Error',
                            icon: 'error',
                            confirmButtonText: "Oke"
                        })
                    }
                });
            }
        })
    }
</script>


@endsection
