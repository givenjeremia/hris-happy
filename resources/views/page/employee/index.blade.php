@extends('layouts.base')
@section('title', 'Employee')

@section('toolbar')
    @include('components.toolbar', ['title' => 'Employee', 'subtitle' => 'Employee'])
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
            ajax: "{{ route('employee.table') }}",
            columns: [
                {
                    data: 'No',
                    name: 'No',
                    title: 'No',
                    className: 'px-5 text-nowrap'
                },
                {
                    data: 'Client',
                    name: 'Client',
                    title: 'Client',
                    className: 'text-nowrap'
                },
                {
                    data: 'Full Name',
                    name: 'Full Name',
                    title: 'Full Name',
                    className: 'text-nowrap'
                },
                {
                    data: 'Posision',
                    name: 'Posision',
                    title: 'Posision',
                    className: 'text-nowrap'
                },
                {
                    data: 'NIK',
                    name: 'NIK',
                    title: 'NIK',
                },
                {
                    data: 'Date Of Birth',
                    name: 'Date Of Birth',
                    title: 'Date Of Birth',
                },
                {
                    data: 'Address',
                    name: 'Address',
                    title: 'Address',
                },
                {
                    data: 'Bank Account Name',
                    name: 'Bank Account Name',
                    title: 'Bank Account Name',
                },
                {
                    data: 'Bank Account Number',
                    name: 'Bank Account Number',
                    title: 'Bank Account Number',
                },
                {
                    data: 'Phone Number',
                    name: 'Phone Number',
                    title: 'Phone Number',
                },
                {
                    data: 'Code PTKP',
                    name: 'Code PTKP',
                    title: 'Code PTKP',
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
