@extends('layouts.base')
@section('title', 'Schedules')

@section('toolbar')
    @include('components.toolbar', ['title' => 'Schedules', 'subtitle' => 'Manage Schedules'])
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Schedule List</h3>
        <div class="card-tools">
            <button onclick="generateForm()" type="button" class="btn btn-secondary mx-3">Generate Schedule</button>
            <button id="createScheduleBtn" type="button" class="btn btn-primary">Add Schedule</button>
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
            ajax: "{{ route('schedules.table') }}",
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
                    data: 'Shift',
                    name: 'Shift',
                    title: 'Shift',
                    className: 'text-nowrap'
                },
                {
                    data: 'Date',
                    name: 'Date',
                    title: 'Date',
                    className: 'text-nowrap'
                },
                {
                    data: 'Desc',
                    name: 'Desc',
                    title: 'Desc',
                    className: 'text-nowrap'
                },
                {
                    data: 'Created',
                    name: 'Created',
                    title: 'Created',
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
    $(document).ready(function() {

        $('#createScheduleBtn').on('click', function() {
            $.ajax({
                url: "{{ route('schedules.create') }}",
                method: "GET",
                success: function(response) {
                    if (response.status === 'success') {
                        $('#modal-div').html(response.msg);
                    } else {
                        alert('Error: ' + response.msg);
                    }
                },
                error: function(xhr) {
                    alert('Failed to load form: ' + xhr.responseJSON.msg);
                }
            });
        });


    });
</script>


<script>
    function generateForm(){
        $.ajax({
            url: "{{ route('schedules.generate.form') }}",
            method: "GET",
            success: function(response) {
                if (response.status === 'success') {
                    $('#modal-div').html(response.msg);
                } else {
                    alert('Error: ' + response.msg);
                }
            },
            error: function(xhr) {
                alert('Failed to load form: ' + xhr.responseJSON.msg);
            }
        });
    }
</script>

<script>
    function updateData(uuid){
        let url = "{{ route('schedules.edit', ':uuid') }}".replace(':uuid', uuid)
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
