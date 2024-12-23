@extends('layouts.base')
@section('title', 'Income')

@section('toolbar')
    @include('components.toolbar', ['title' => 'Income', 'subtitle' => 'Income'])
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Salary Data</h3>
        <div class="card-tools">
            <a href="#" onclick="generateGaji()" type="button" class="btn btn-primary">Generate Salary</a>
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
            ajax: "{{ route('income.index') }}",
            columns: [{
                    data: 'No',
                    name: 'No',
                    title: 'No',
                    className: 'px-5 text-nowrap'
                },
                {
                    data: 'Name Employee',
                    name: 'Name Employee',
                    title: 'Name Employee',
                    className: 'text-nowrap'
                },
                {
                    data: 'Nominal',
                    name: 'Nominal',
                    title: 'Nominal',
                    className: 'text-nowrap'
                },

                {
                    data: 'Period',
                    name: 'Period',
                    title: 'Period',
                    className: 'text-nowrap'
                },

                {
                    data: 'Status',
                    name: 'Status',
                    title: 'Status',
                    className: 'text-nowrap'
                },

                {
                    data: 'Action',
                    name: 'Action',
                    title: 'Action',
                    className: 'text-nowrap'
                },

            ],

        });
    });
</script>

<script>
    function generateGaji(){
        let url = "{{ route('income.generate.salary') }}"
        // Add Loading
        $.ajax({
            url: url,
            method: "GET",
            success: function(response) {
                $('#example1').DataTable().ajax.reload();
                Swal.fire({
                    title: response.msg,
                    icon: 'success',
                    confirmButtonText: "Oke"
                })
            },
            error: function(xhr, status, error) {
                console.log(response.msg)
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
    function detailDataData(uuid){
        let url = "{{ route('income.show', ':uuid') }}".replace(':uuid', uuid)
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
