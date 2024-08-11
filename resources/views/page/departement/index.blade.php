@extends('layouts.base')
@section('title', 'Departement')

@section('toolbar')
    @include('components.toolbar', ['title' => 'Departement', 'subtitle' => 'Departement'])
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Tabel Data</h3>
        <div class="card-tools">
            <a href="#" onclick="createData()" type="button" class="btn btn-primary">Create</a>
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
            ajax: "{{ route('departements.table') }}",
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
    function createData(){
        let url = "{{ route('departements.create') }}"
        $.ajax({
            url: url,
            method: "GET",
            success: function(response) {
                $('#modal-div').html("");
                if (response.status == 'success') {
                    $('#modal-div').html(response.msg);
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
                    title: 'Failed, Error Server',
                    icon: 'error',
                    confirmButtonText: "Oke"
                })
            }
        });
    }
</script>

<script>
    function updateData(uuid){
        let url = "{{ route('departements.edit', ':uuid') }}".replace(':uuid', uuid)
        $.ajax({
            url: url,
            method: "GET",
            success: function(response) {
                $('#modal-div').html("");
                if (response.status == 'success') {
                    $('#modal-div').html(response.msg);
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
                    title: 'Failed, Error Server',
                    icon: 'error',
                    confirmButtonText: "Oke"
                })
            }
        });
    }
</script>

@endsection
