@extends('layouts.base')
@section('title', 'Presence Report')

@section('toolbar')
    @include('components.toolbar', ['title' => 'Presence Report', 'subtitle' => 'Presence Report'])
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Presence Report</h3>
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
                    <label for="start_date">Start Date:</label>
                    <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}">
                </div>
                <div class="col-md-3">
                    <label for="end_date">End Date:</label>
                    <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date') }}">
                </div>
                <div class="col-md-3 align-self-end d-flex" style="gap: 10px;">
                    <!-- Filter Button -->
                    <button type="submit" class="btn btn-primary d-flex align-items-center w-100 justify-content-center text-center">
                        <i class="fas fa-filter me-2"></i> Filter
                    </button>
                </div>
            </div>
        </form>

        <!-- Print Button -->
        <div class="row">
            <div class="col-md-3 align-self-end d-flex" style="gap: 10px;">
                <form method="GET" action="{{ route('reports.presence.pdf') }}" target="_blank" class="w-50">
                    <input type="hidden" name="employee_id" id="print-employee-id" value="{{ request('employee_id') }}">
                    <input type="hidden" name="start_date" id="print-start-date" value="{{ request('start_date') }}">
                    <input type="hidden" name="end_date" id="print-end-date" value="{{ request('end_date') }}">
                    <button type="submit" class="btn btn-app justify-content-center text-center">
                        <i class="fas fa-print me-2"></i> Print
                    </button>
                </form>
            </div>
        </div>
        <br>    
        <div class="table-responsive">
            <table id="example1" class="table table-bordered table-striped">
            </table>
        </div>
    </div>
</div>


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
            ajax: {
                url: "{{ route('reports.presence.table') }}",
                data: function (d) {
                    d.employee_id = $('#employee').val();
                    d.start_date = $('#start_date').val();
                    d.end_date = $('#end_date').val();
                }
            },
            columns: [
                {
                    data: 'employee_name',
                    name: 'employee_name',
                    title: 'Employee Name',
                    className: 'text-nowrap'
                },
                {
                    data: 'date',
                    name: 'date',
                    title: 'Date',
                },
                {
                    data: 'time_in',
                    name: 'time_in',
                    title: 'Time In',
                },
                {
                    data: 'time_out',
                    name: 'time_out',
                    title: 'Time Out',
                },
                {
                    data: 'status',
                    name: 'status',
                    title: 'Status',
                },
                {
                    data: 'information',
                    name: 'information',
                    title: 'Information',
                }
            ],
        });

        $('form#filter-form').on('submit', function(e) {
            e.preventDefault();
            datatable.draw();

            $('#print-employee-id').val($('#employee').val());
            $('#print-start-date').val($('#start_date').val());
            $('#print-end-date').val($('#end_date').val());
        });
    });
</script>
@endsection
