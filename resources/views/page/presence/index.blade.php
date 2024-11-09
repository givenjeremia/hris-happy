@extends('layouts.base')
@section('title', 'Presence')

@section('toolbar')
    @include('components.toolbar', ['title' => 'Presence', 'subtitle' => 'Presence'])
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Tabel Data</h3>
       
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
            ajax: "{{ route('presences.table') }}",
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
                    data: 'Date',
                    name: 'Date',
                    title: 'Date',
                    className: 'text-nowrap'
                },
                {
                    data: 'Lat In',
                    name: 'Lat In',
                    title: 'Lat In',
                    className: 'text-nowrap'
                },
                {
                    data: 'Long In',
                    name: 'Long In',
                    title: 'Long In',
                    className: 'text-nowrap'
                },
                {
                    data: 'Time In',
                    name: 'Time In',
                    title: 'Time In',
                    className: 'text-nowrap'
                },
                {
                    data: 'Lat Out',
                    name: 'Lat Out',
                    title: 'Lat Out',
                    className: 'text-nowrap'
                },
                {
                    data: 'Long Out',
                    name: 'Long Out',
                    title: 'Long Out',
                    className: 'text-nowrap'
                },
                {
                    data: 'Time Out',
                    name: 'Time Out',
                    title: 'Time Out',
                    className: 'text-nowrap'
                },
                {
                    data: 'Status',
                    name: 'Status',
                    title: 'Status',
                    className: 'text-nowrap'
                },
                {
                    data: 'Information',
                    name: 'Information',
                    title: 'Information',
                    className: 'text-nowrap'
                },

            ],

        });
    });
</script>



@endsection
