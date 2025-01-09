<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Presence;
use App\Models\Vacation;
use App\Models\Overtime;
use App\Models\Employee;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;

class ReportController extends Controller
{
    public function presenceReport(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $employeeId = $request->input('employee_id');
    
        $query = Presence::with('employee');
    
        if ($startDate) {
            $query->where('date', '>=', $startDate);
        }
    
        if ($endDate) {
            $query->where('date', '<=', $endDate);
        }
    
        if ($employeeId) {
            $query->where('employee_id', $employeeId);
        }
    
        $data = $query->get();
    
        $employees = Employee::all();
    
        return view('page/report.presences', [
            'data' => $data,
            'employees' => $employees,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }

    public function presenceTable(Request $request)
    {
        $query = Presence::with('employee');

        if ($request->employee_id) {
            $query->where('employee_id', $request->employee_id);
        }

        if ($request->start_date) {
            $query->where('date', '>=', $request->start_date);
        }

        if ($request->end_date) {
            $query->where('date', '<=', $request->end_date);
        }

        return DataTables::of($query)
            ->addColumn('employee_name', function ($presence) {
                return $presence->employee->full_name ?? '-';
            })
            ->make(true);
    }

    public function presenceGeneratePDF(Request $request)
    {
        $query = Presence::with('employee');
    
        if ($request->employee_id) {
            $query->where('employee_id', $request->employee_id);
        }
    
        if ($request->start_date) {
            $query->where('date', '>=', $request->start_date);
        }
    
        if ($request->end_date) {
            $query->where('date', '<=', $request->end_date);
        }
    
        $data = $query->get();
        foreach ($data as $presence) {
            preg_match('/Masuk.*?Pada Area\s*([\d.]+)\s*KM/', $presence->information, $cekInMatches);
            preg_match('/Keluar.*?Pada Area\s*([\d.]+)\s*KM/', $presence->information, $cekOutMatches);
    
            $presence->area_cek_in = $cekInMatches[1] ?? 'N/A';
            $presence->area_cek_out = $cekOutMatches[1] ?? 'N/A';
        }
    
        $employee_statistics = $data
            ->groupBy('employee.full_name')
            ->map(function ($presences) {
                return $presences->count();
            });
    
        $start_date = $request->start_date ? Carbon::parse($request->start_date)->format('d F Y') : 'N/A';
        $end_date = $request->end_date ? Carbon::parse($request->end_date)->format('d F Y') : 'N/A';
    
        $html = view('page/report/generate-pdf.presences-pdf', compact('data', 'employee_statistics', 'start_date', 'end_date'))->render();
    
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();    
        return $dompdf->stream('Presence_Report.pdf', ['Attachment' => false]);
    }
    
    
    

    public function overtimeReport(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $employeeId = $request->input('employee_id');
    
        $query = Overtime::with('employee');
    
        if ($startDate) {
            $query->where('start_date', '>=', $startDate);
        }
    
        if ($endDate) {
            $query->where('end_date', '<=', $endDate);
        }
    
        if ($employeeId) {
            $query->where('employee_id', $employeeId);
        }
    
        $data = $query->get();
    
        $employees = Employee::all();
    
        return view('page/report.overtime', [
            'data' => $data,
            'employees' => $employees,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }

    public function overtimeTable(Request $request)
    {
        $query = Overtime::query()->with('employee');

        if ($request->employee_id) {
            $query->where('employee_id', $request->employee_id);
        }

        if ($request->start_date) {
            $query->where('start_date', '>=', $request->start_date);
        }

        if ($request->end_date) {
            $query->where('end_date', '<=', $request->end_date);
        }

        return DataTables::of($query)
            ->addColumn('employee_name', function($overtime) {
                return $overtime->employee->full_name ?? '-';
            })
            ->make(true);
    }

    public function overtimeGeneratePDF(Request $request)
    {
        $query = Overtime::with('employee');

        if ($request->employee_id) {
            $query->where('employee_id', $request->employee_id);
        }

        if ($request->start_date) {
            $query->where('date', '>=', $request->start_date);
        }

        if ($request->end_date) {
            $query->where('date', '<=', $request->end_date);
        }

        $data = $query->get();

        $start_date = $request->start_date ? Carbon::parse($request->start_date)->format('d F Y') : 'N/A';
        $end_date = $request->end_date ? Carbon::parse($request->end_date)->format('d F Y') : 'N/A';

        $html = view('page/report/generate-pdf.overtime-pdf', compact('data', 'start_date', 'end_date'))->render();

        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        return $dompdf->stream('Overtime_Report.pdf', ['Attachment' => false]);
    }


    public function vacationReport(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $employeeId = $request->input('employee_id');
    
        $query = Vacation::with('employee');
    
        if ($startDate) {
            $query->where('start_date', '>=', $startDate);
        }
    
        if ($endDate) {
            $query->where('end_date', '<=', $endDate);
        }
    
        if ($employeeId) {
            $query->where('employee_id', $employeeId);
        }
    
        $data = $query->get();
    
        $employees = Employee::all();
    
        return view('page/report.vacation', [
            'data' => $data,
            'employees' => $employees,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }

    public function vacationTable(Request $request)
    {
        $query = Vacation::query()->with('employee');

        if ($request->employee_id) {
            $query->where('employee_id', $request->employee_id);
        }

        if ($request->start_date) {
            $query->where('start_date', '>=', $request->start_date);
        }

        if ($request->end_date) {
            $query->where('end_date', '<=', $request->end_date);
        }

        return DataTables::of($query)
            ->addColumn('employee_name', function($vacation) {
                return $vacation->employee->full_name ?? '-';
            })
            ->make(true);
    }

    public function vacationGeneratePDF(Request $request)
    {
        $query = Vacation::with('employee');
        if ($request->employee_id) {
            $query->where('employee_id', $request->employee_id);
        }

        if ($request->start_date) {
            $query->where('start_date', '>=', $request->start_date);
        }

        if ($request->end_date) {
            $query->where('end_date', '<=', $request->end_date);
        }

        $data = $query->get();

        $start_date = $request->start_date ? Carbon::parse($request->start_date)->format('d F Y') : '[Not Set]';
        $end_date = $request->end_date ? Carbon::parse($request->end_date)->format('d F Y') : '[Not Set]';

        $html = view('page/report/generate-pdf.vacation-pdf', compact('data', 'start_date', 'end_date'))->render();

        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        return $dompdf->stream('Vacation_Report.pdf', ['Attachment' => false]);
    }


    public function incomeReport(Request $request)
    {
        // (Implementasi serupa untuk laporan income, gunakan tabel income jika tersedia)
    }
}
