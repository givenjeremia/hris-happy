@extends('layouts.base')
@section('title', 'BPJS')

@section('toolbar')
    @include('components.toolbar', ['title' => 'BPJS', 'subtitle' => 'BPJS'])
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">BPJS Data</h3>
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
            ajax: "{{ route('bpjs.index') }}",
            columns: [{
                    data: 'No',
                    name: 'No',
                    title: 'No',
                    className: 'px-5 text-nowrap'
                },
                {
                    data: 'Nominal',
                    name: 'Nominal',
                    title: 'Number (in Percent)',
                    className: 'text-nowrap'
                },
                {
                    data: 'Type',
                    name: 'Type',
                    title: 'Type',
                    className: 'text-nowrap'
                },

            ],

        });
    });
</script>

<script>
    function createData(){
        let url = "{{ route('allowance.create') }}"
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
        let url = "{{ route('allowance.edit', ':uuid') }}".replace(':uuid', uuid)
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
