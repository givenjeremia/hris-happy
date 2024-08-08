@extends('layouts.base')
@section('title', 'Client')

@section('toolbar')
    @include('components.toolbar', ['title' => 'Client', 'subtitle' => 'Client'])
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Tabel Data</h3>
        <div class="card-tools">
            <a href="{{ route('clients.create') }}" type="button" class="btn btn-primary">Tambah</a>
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
            ajax: "{{ route('clients.table') }}",
            columns: [{
                    data: 'No',
                    name: 'No',
                    title: 'No',
                    className: 'px-5 text-nowrap'
                },
                {
                    data: 'Name',
                    name: 'Name',
                    title: 'Name',
                    className: 'text-nowrap'
                },
                {
                    data: 'Address',
                    name: 'Address',
                    title: 'Address',
                },
                {
                    data: 'Email',
                    name: 'Email',
                    title: 'Email',
                },
                {
                    data: 'Latitude',
                    name: 'Latitude',
                    title: 'Latitude',
                    className: 'text-nowrap'
                },
                {
                    data: 'Longitude',
                    name: 'Longitude',
                    title: 'Longitude',
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
