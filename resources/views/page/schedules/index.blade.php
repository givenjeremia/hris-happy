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
            <button id="createScheduleBtn" type="button" class="btn btn-primary">Add Schedule</button>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="schedulesTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>ID</th>
                        <th>Employee</th>
                        <th>Shift</th>
                        <th>Date</th>
                        <th>Description</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<div id="modal-div"></div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Inisialisasi DataTable
        var datatable = $('#schedulesTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('schedules.table') }}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', title: 'No', className: 'text-center', orderable: false, searchable: false },
                { data: 'id', name: 'id', title: 'UUID' },
                { data: 'employee_name', name: 'employee_name', title: 'Employee' },
                { data: 'shift_name', name: 'shift_name', title: 'Shift' },
                { data: 'date', name: 'date', title: 'Date' },
                { data: 'desc', name: 'desc', title: 'Description' },
                { data: 'created_at', name: 'created_at', title: 'Created At' },
                { data: 'updated_at', name: 'updated_at', title: 'Updated At' },
                {
                    data: 'action', 
                    name: 'action', 
                    title: 'Actions', 
                    orderable: false, 
                    searchable: false, 
                    className: 'text-center'
                }
            ],
        });

        // Event untuk tombol Add Schedule
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

        // Event handler untuk Edit
        $('#schedulesTable').on('click', '.edit-btn', function() {
            let uuid = $(this).data('uuid');
            $.ajax({
                url: "{{ url('schedules') }}/" + uuid + "/edit",
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
                    alert('Failed to load edit form: ' + xhr.responseJSON.msg);
                }
            });
        });

        // Event handler untuk Delete
        $('#schedulesTable').on('click', '.delete-btn', function() {
            let uuid = $(this).data('uuid');
            if (confirm("Are you sure you want to delete this schedule?")) {
                $.ajax({
                    url: "{{ url('schedules') }}/" + uuid,
                    method: "DELETE",
                    data: { _token: "{{ csrf_token() }}" },
                    success: function(response) {
                        if (response.status === 'success') {
                            alert(response.msg);
                            datatable.ajax.reload(); // Refresh DataTable setelah delete
                        } else {
                            alert('Error: ' + response.msg);
                        }
                    },
                    error: function(xhr) {
                        alert('Failed to delete schedule: ' + xhr.responseJSON.msg);
                    }
                });
            }
        });
    });
</script>
@endsection
