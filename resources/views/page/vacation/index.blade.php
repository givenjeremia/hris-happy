@extends('layouts.base')
@section('title', 'Vacation')

@section('toolbar')
    @include('components.toolbar', ['title' => 'Vacation', 'subtitle' => 'Vacation'])
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Vacation List</h3>
        <div class="card-tools">
            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-create">
                <i class="fa fa-plus"></i> Add Vacation
            </button>
        </div>
    </div>
    <div class="card-body">
        <table id="vacationTable" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Employee Name</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Subject</th>
                    <th>Information</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

@include('page.vacation.create')
@include('page.vacation.update')

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        const vacationTable = $('#vacationTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('vacation.tableData') }}",
            columns: [
                { data: 'No', name: 'No' },
                { data: 'Employee Name', name: 'Employee Name' },
                { data: 'Start Date', name: 'Start Date' },
                { data: 'End Date', name: 'End Date' },
                { data: 'Subject', name: 'Subject' },
                { data: 'Information', name: 'Information' },
                { data: 'Status', name: 'Status' },
                { data: 'Action', name: 'Action', orderable: false, searchable: false }
            ]
        });

        // Add Vacation
        $('#btn-save-create').click(function(e) {
            e.preventDefault();
            const formData = new FormData($('#form-create')[0]);
            $.ajax({
                url: "{{ route('vacation.store') }}",
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response.status === 'success') {
                        $('#modal-create').modal('hide');
                        Swal.fire('Success', response.msg, 'success');
                        vacationTable.ajax.reload();
                    } else {
                        Swal.fire('Error', response.msg, 'error');
                    }
                },
                error: function(xhr) {
                    Swal.fire('Error', 'An error occurred. Please try again.', 'error');
                }
            });
        });

        // Update Vacation
        $('#btn-save-update').click(function(e) {
            e.preventDefault();
            const uuid = $('#form-update input[name="uuid"]').val();
            const formData = new FormData($('#form-update')[0]);
            $.ajax({
                url: `/vacation/${uuid}`,
                method: 'PUT',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response.status === 'success') {
                        $('#modal-update').modal('hide');
                        Swal.fire('Success', response.msg, 'success');
                        vacationTable.ajax.reload();
                    } else {
                        Swal.fire('Error', response.msg, 'error');
                    }
                },
                error: function(xhr) {
                    Swal.fire('Error', 'An error occurred. Please try again.', 'error');
                }
            });
        });

        // Load Update Data
        window.loadUpdateForm = function(uuid) {
            $.ajax({
                url: `/vacation/${uuid}`,
                method: 'GET',
                success: function(data) {
                    $('#form-update input[name="uuid"]').val(data.uuid);
                    $('#form-update input[name="date"]').val(`${data.start_date} - ${data.end_date}`);
                    $('#form-update input[name="subject"]').val(data.subject);
                    $('#form-update textarea[name="information"]').val(data.information);
                    $('#modal-update').modal('show');
                },
                error: function(xhr) {
                    Swal.fire('Error', 'Failed to load data.', 'error');
                }
            });
        };
    });
</script>
@endsection
