@extends('layouts.base')
@section('title', 'Vacation Report')

@section('toolbar')
    @include('components.toolbar', ['title' => 'Vacation Report', 'subtitle' => 'Vacation Report'])
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Vacation Report</h3>
    </div>
    <div class="card-body">
        <form id="filter-form" class="mb-3">
            <div class="row">
                <div class="col-md-3">
                    <label for="employee">Employee</label>
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
                    <label for="start_date">Start Date</label>
                    <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}">
                </div>
                <div class="col-md-3">
                    <label for="end_date">End Date</label>
                    <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date') }}">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
            </div>
        </form>

        <!-- Print Button -->
        <div class="row">
            <div class="col-md-3 align-self-end d-flex" style="gap: 10px;">
                <form method="GET" action="{{ route('reports.vacation.pdf') }}" target="_blank" class="w-50">
                    <input type="hidden" name="employee_id" id="print-employee-id" value="{{ request('employee_id') }}">
                    <input type="hidden" name="start_date" id="print-start-date" value="{{ request('start_date') }}">
                    <input type="hidden" name="end_date" id="print-end-date" value="{{ request('end_date') }}">
                    <button type="submit" class="btn btn-app justify-content-center text-center">
                        <i class="fas fa-print me-2"></i> Print
                    </button>
                </form>
            </div>
        </div>

        <div class="table-responsive">
            <table id="vacationTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Employee Name</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Subject</th>
                        <th>Information</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        var vacationTable = $('#vacationTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('reports.vacation.table') }}",
                data: function(d) {
                    d.employee_id = $('#employee').val();
                    d.start_date = $('#start_date').val();
                    d.end_date = $('#end_date').val();
                }
            },
            columns: [
                { data: 'employee_name', name: 'employee_name', title: 'Employee Name' },
                { data: 'start_date', name: 'start_date', title: 'Start Date' },
                { data: 'end_date', name: 'end_date', title: 'End Date' },
                { data: 'subject', name: 'subject', title: 'Subject' },
                { data: 'information', name: 'information', title: 'Information' },
                { data: 'status', name: 'status', title: 'Status' },
            ]
        });

        $('#filter-form').on('submit', function(e) {
            e.preventDefault();
            vacationTable.ajax.reload();
        });
    });
</script>
@endsection
