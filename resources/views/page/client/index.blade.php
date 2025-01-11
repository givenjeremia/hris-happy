@extends('layouts.base')
@section('title', 'Client')

@section('toolbar')
    @include('components.toolbar', ['title' => 'Client', 'subtitle' => 'Client'])
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Client Data</h3>
        <div class="card-tools">
            <a href="{{ route('clients.create') }}" type="button" class="btn btn-primary">Tambah</a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="example1" class="table table-bordered table-striped">
            </table>
        </div>
    </div>
</div>
<div id="modal-div"></div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        var datatable = $('#example1').DataTable({
            "columnDefs": [{
                "defaultContent": "-",
                "targets": "_all"
            }],
            processing: true,
            serverSide: true,
            ajax: "{{ route('clients.table') }}",
            columns: [{
                    data: 'No',
                    name: 'No',
                    title: 'No',
                    className: 'px-5 text-nowrap'
                },
                {
                    data: 'Name',
                    name: 'Name',
                    title: 'Name',
                    className: 'text-nowrap'
                },
                {
                    data: 'Address',
                    name: 'Address',
                    title: 'Address',
                },
                {
                    data: 'Email',
                    name: 'Email',
                    title: 'Email',
                },
                {
                    data: 'Latitude',
                    name: 'Latitude',
                    title: 'Latitude',
                    className: 'text-nowrap'
                },
                {
                    data: 'Longitude',
                    name: 'Longitude',
                    title: 'Longitude',
                    className: 'text-nowrap'
                },
                {
                    data: 'Action',
                    name: 'Action',
                    title: 'Action',
                    className: 'text-nowrap px-5'
                },

            ],

        });
    });
</script>

<script>
    function deleteData(data) {
        Swal.fire({
            title: 'Are You Sure ?',
            text: "Delete Data",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya',
            cancelButtonText: 'Tidak'
        }).then((result) => {
            if (result.isConfirmed) {
                let url = "{{ route('clients.destroy', ':uuid') }}".replace(':uuid', data)
                $.ajax({
                    url: url,
                    method: "DELETE",
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
                                $('#example1').DataTable().ajax.reload();
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
