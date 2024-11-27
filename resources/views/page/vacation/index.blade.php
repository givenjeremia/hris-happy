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
                @if (auth()->user()->hasRole('employee'))
                    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-create">
                        <i class="fa fa-plus"></i> Add Vacation
                    </button>
                @endif
            </div>
        </div>
        <div class="card-body">
            <table id="vacationTable" class="table table-bordered table-striped">
            </table>
        </div>
    </div>
    @if (auth()->user()->hasRole('employee'))
        @include('page.vacation.create')
    @endif
    {{-- @include('page.vacation.update') --}}

@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            const vacationTable = $('#vacationTable').DataTable({
                "columnDefs": [{
                    "defaultContent": "-",
                    "targets": "_all"
                }],
                processing: true,
                serverSide: true,
                ajax: "{{ route('vacations.tableData') }}",
                columns: [{
                        data: 'No',
                        name: 'No',
                        title: 'No',
                        className: 'text-nowrap'
                    },
                    ...(isAdmin === 'true' ? [{
                        data: 'Employee Name',
                        name: 'Employee Name',
                        title: 'Employee Name',
                        className: 'text-nowrap'
                    }] : []),
                    {
                        data: 'Start Date',
                        name: 'Start Date',
                        title: 'Start Date',
                        className: 'px-5 text-nowrap'
                    },
                    {
                        data: 'End Date',
                        name: 'End Date',
                        title: 'End Date',
                        className: 'px-5 text-nowrap'
                    },
                    {
                        data: 'Subject',
                        name: 'Subject',
                        title: 'Subject',
                        className: 'text-nowrap'
                    },
                    {
                        data: 'Information',
                        name: 'Information',
                        title: 'Information',
                        className: 'px-5 text-nowrap'
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
                    }
                ]
            });
        });

        function updateStatusVacation(uuid, status) {
            console.log(uuid, status);
            Swal.fire({
                title: "Update Status Vacation",
                text: "Are you sure?",
                icon: 'warning',
                target: document.getElementById('content'),
                reverseButtons: true,
                confirmButtonText: "Yes",
                showCancelButton: true,
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    let act = '{{ route('vacations.update.status', ':uuid') }}'.replace(':uuid', uuid)
                    let form_data = new FormData();
                    form_data.append('status', status)
                    form_data.append('_token', '{{ csrf_token() }}')
                    form_data.append('_method', 'PUT')
                    $.ajax({
                        url: act,
                        type: "POST",
                        data: form_data,
                        dataType: "json",
                        contentType: false,
                        processData: false,
                        success: function(data) {

                            if (data.status == "success") {
                                Swal.fire({
                                    title: data.msg,
                                    icon: 'success'
                                }).then(function(result) {
                                    $('#vacationTable').DataTable().ajax.reload();
                                });

                            } else {
                                var msg = '';
                                if (data.valid['name']) {
                                    msg += '<strong>Nama Wajib Diisi!</strong><br>';
                                }
                                Swal.fire({
                                    title: data.msg,
                                    html: msg,
                                    icon: 'error'
                                })

                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {

                            Swal.fire({
                                title: textStatus,
                                text: errorThrown,
                                icon: 'error',
                            })
                            console.log(textStatus, errorThrown);
                        }

                    })

                }
            })
        }
    </script>

    {{-- Admin --}}
    @if (auth()->user()->hasRole('admin'))
        <script>
            // Add Vacation
            $('#btn-save-create').click(function(e) {
                e.preventDefault();
                const formData = new FormData($('#form-create')[0]);
                $.ajax({
                    url: "{{ route('vacations.store') }}",
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
                    url: `{{ route('vacations.update', ':uuid') }}`.replace(':uuid', uuid),
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
            $('#date-vacation').daterangepicker()
        </script>
    @endif

    {{-- Employee --}}
    @if (auth()->user()->hasRole('employee'))
        <script>
            // Add Vacation
            $('#btn-save-create').click(function(e) {
                e.preventDefault();
                const formData = new FormData($('#form-create')[0]);
                $.ajax({
                    url: "{{ route('vacations.store') }}",
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
                    url: `{{ route('vacations.update', ':uuid') }}`.replace(':uuid', uuid),
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
            $('#date-vacation').daterangepicker()
        </script>
    @endif

@endsection
