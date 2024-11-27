@extends('layouts.base')
@section('title', 'Overtime')

@section('toolbar')
    @include('components.toolbar', ['title' => 'Overtime', 'subtitle' => 'Overtime'])
@endsection

@section('content')

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Tabel Data</h3>
        <div class="card-tools">
            <a href="{{ route('employee.create') }}" type="button" class="btn btn-primary">Create</a>
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
@endsection
