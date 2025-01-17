@extends('layouts.base')
@section('title', 'Income Report')

@section('toolbar')
    @include('components.toolbar', ['title' => 'Income Report', 'subtitle' => 'Income Report'])
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Income Report</h3>
    </div>
    <div class="card-body">
        <form method="GET" id="filter-form" class="mb-4">
            <div class="row">
                <div class="col-md-3">
                    <label for="employee">Employee:</label>
                    <select name="employee_id" id="employee" class="form-control">
                        <option value="">All Employees</option>
                        @foreach ($employees as $employee)
                            <option value="{{ $employee->id }}" {{ request('employee_id') == $employee->id ? 'selected' : '' }}>
                                {{ $employee->full_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="startdatefilter">Start Date:</label>
                    <input type="date" name="startdatefilter" id="startdatefilter" class="form-control" value="{{ request('startdatefilter') }}">
                </div>
                <div class="col-md-3">
                    <label for="enddatefilter">End Date:</label>
                    <input type="date" name="enddatefilter" id="enddatefilter" class="form-control" value="{{ request('enddatefilter') }}">
                </div>
                <div class="col-md-3 align-self-end d-flex" style="gap: 10px;">
                    <button type="submit" class="btn btn-primary d-flex align-items-center w-100 justify-content-center text-center">
                        <i class="fas fa-filter me-2"></i> Filter
                    </button>
                </div>
            </div>
        </form>

        <!-- Print Button -->
        <div class="row">
            <div class="col-md-3 align-self-end d-flex" style="gap: 10px;">
                <button class="btn btn-secondary" data-toggle="modal" data-target="#printModal">Print PDF</button>
            </div>
        </div>

        <br>
        <div class="table-responsive">
            <table id="example1" class="table table-bordered table-striped"></table>
        </div>
    </div>
</div>

<!-- Print Options Modal -->
<div class="modal fade" id="printModal" tabindex="-1" role="dialog" aria-labelledby="printModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="printModalLabel">Print Options</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="printForm" method="POST" action="{{ route('reports.income.pdf') }}" target="_blank">
                    @csrf
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="print_type" id="defaultPrint" value="default" checked>
                        <label class="form-check-label" for="defaultPrint">Print Default (Income Only)</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="print_type" id="detailPrint" value="detail">
                        <label class="form-check-label" for="detailPrint">Print Detail (Income With Detail of Employee)</label>
                    </div>
                    <input type="hidden" name="employee_id" id="print-employee-id" value="">
                    <input type="hidden" name="startdatefilter" id="print-start-date" value="">
                    <input type="hidden" name="enddatefilter" id="print-end-date" value="">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="printBtn">Print</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
$(document).ready(function () {
    var datatable = $('#example1').DataTable({
        columnDefs: [{ defaultContent: "-", targets: "_all" }],
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('reports.income.table') }}",
            data: function (d) {
                d.employee_id = $('#employee').val();
                d.startdatefilter = $('#startdatefilter').val();
                d.enddatefilter = $('#enddatefilter').val();
            }
        },
        columns: [
            { data: 'employee_name', name: 'employee_name', title: 'Employee Name', className: 'text-nowrap' },
            { data: 'period', name: 'period', title: 'Period' },
            { data: 'Nominal', name: 'Nominal', title: 'Nominal' },
            { data: 'status', name: 'status', title: 'Status' },
        ]
    });

    $('#filter-form').on('submit', function (e) {
        e.preventDefault();
        datatable.ajax.reload();

        document.getElementById('print-employee-id').value = document.getElementById('employee').value;
        document.getElementById('print-start-date').value = document.getElementById('startdatefilter').value;
        document.getElementById('print-end-date').value = document.getElementById('enddatefilter').value;
    });

    document.getElementById('printBtn').addEventListener('click', function () {
        const printForm = document.getElementById('printForm');
        const defaultPrint = document.getElementById('defaultPrint');
        const detailPrint = document.getElementById('detailPrint');

        if (defaultPrint.checked) {
            printForm.action = "{{ route('reports.income.pdf') }}";
        } else if (detailPrint.checked) {
            printForm.action = "{{ route('reports.incomedetail.pdf') }}";
        }

        printForm.submit();
    });
});

</script>
@endsection
