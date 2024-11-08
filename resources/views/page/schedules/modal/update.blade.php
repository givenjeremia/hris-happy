@extends('layouts.base')
@section('title', 'Schedule')

@section('toolbar')
    @include('components.toolbar', ['title' => 'Schedule', 'subtitle' => 'Manage Schedules'])
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Tabel Jadwal</h3>
        <div class="card-tools">
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
            columns: [
                { data: 'No', name: 'No', title: 'No', className: 'px-5 text-nowrap' },
                { data: 'employee_name', name: 'employee_name', title: 'Employee', className: 'text-nowrap' },
                { data: 'shift_name', name: 'shift_name', title: 'Shift' },
                { data: 'date', name: 'date', title: 'Date' },
                { data: 'desc', name: 'desc', title: 'Description' },
                { data: 'action', name: 'action', title: 'Action', className: 'text-nowrap px-5' },
            ],
        });

        // Handle the create schedule button click
        $('#createScheduleBtn').on('click', function() {
            $.ajax({
                url: "{{ route('schedules.create') }}",
                method: "GET",
                success: function(response) {
                    if (response.status === 'success') {
                        $('#modal-div').html(response.msg);
                        $('#modal-div').modal('show');
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
@endsection
