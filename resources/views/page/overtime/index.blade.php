@extends('layouts.base')
@section('title', 'Overtime')

@section('toolbar')
    @include('components.toolbar', ['title' => 'Overtime', 'subtitle' => 'Overtime'])
@endsection

@section('content')

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Overtime Data</h3>
        <div class="card-tools">
            @if (auth()->user()->hasRole('employee'))
                <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-create">
                    <i class="fa fa-plus"></i> Add Overtime
                </button>
            @endif
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

@if (auth()->user()->hasRole('employee'))
    @include('page.overtime.create')
@endif

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
            ajax: "{{ route('overtimes.index') }}",
            columns: [
                {
                    data: 'No',
                    name: 'No',
                    title: 'No',
                    className: 'px-5 text-nowrap'
                },
                ...(isAdmin === 'true' ? [{
                    data: 'Employee Name',
                    name: 'Employee Name',
                    title: 'Employee Name',
                    className: 'text-nowrap'
                }] : []),
                {
                    data: 'Date',
                    name: 'Date',
                    title: 'Date',
                    className: 'text-nowrap'
                },
                {
                    data: 'Information',
                    name: 'Information',
                    title: 'Information',
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

    @if (auth()->user()->hasRole('employee'))
        <script>
            // Add Overtime
            $('#btn-save-create').click(function(e) {
                e.preventDefault();
                const formData = new FormData($('#form-create')[0]);
                $.ajax({
                    url: "{{ route('overtimes.store') }}",
                    method: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.status === 'success') {
                            $('#modal-create').modal('hide');
                            Swal.fire('Success', response.msg, 'success');
                            $('#example1').DataTable().ajax.reload();
                        } else {
                            Swal.fire('Error', response.msg, 'error');
                        }
                    },
                    error: function(xhr) {
                        Swal.fire('Error', 'An error occurred. Please try again.', 'error');
                    }
                });
            });

        </script>
    @endif

@endsection
