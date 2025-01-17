<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Presence;
use App\Models\Vacation;
use App\Models\Overtime;
use App\Models\Income;
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
            $query->where('date', '>=', $request->start_date);
        }

        if ($request->end_date) {
            $query->where('date', '<=', $request->end_date);
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
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

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
        $startDateFilter = $request->input('startdatefilter'); 
        $endDateFilter = $request->input('enddatefilter');
        $employeeId = $request->input('employee_id');
    
        // Query utama 
        $query = Vacation::with('employee');
    
        // Filter berdasarkan tanggal ato employee
        if ($startDateFilter) {
            $query->where('start_date', '>=', $startDateFilter);
        }
        if ($endDateFilter) {
            $query->where('end_date', '<=', $endDateFilter);
        }
        if ($employeeId) {
            $query->where('employee_id', $employeeId);
        }
    
        $data = $query->get();
        $employees = Employee::all();
    
        return view('page/report.vacation', [
            'data' => $data,
            'employees' => $employees,
            'startdatefilter' => $startDateFilter,
            'enddatefilter' => $endDateFilter,
        ]);
    }

    public function vacationTable(Request $request)
    {
        $query = Vacation::query()->with('employee');

        // Filter berdasarkan input dari request
        if ($request->employee_id) {
            $query->where('employee_id', $request->employee_id);
        }
        if ($request->startdatefilter) {
            $query->where('start_date', '>=', $request->startdatefilter);
        }
        if ($request->enddatefilter) {
            $query->where('end_date', '<=', $request->enddatefilter);
        }

        return DataTables::of($query)
            ->addColumn('employee_name', function ($vacation) {
                return $vacation->employee->full_name ?? '-';
            })
            ->make(true);
    }

    // Vacation Generate PDF
    public function vacationGeneratePDF(Request $request)
    {
        $startDateFilter = $request->input('startdatefilter');
        $endDateFilter = $request->input('enddatefilter');
        $employeeId = $request->input('employee_id');

        $query = Vacation::with('employee');
        if ($employeeId) {
            $query->where('employee_id', $employeeId);
        }
        if ($startDateFilter) {
            $query->where('start_date', '>=', $startDateFilter);
        }
        if ($endDateFilter) {
            $query->where('end_date', '<=', $endDateFilter);
        }

        $data = $query->get();

        $filterstartdate = $startDateFilter ? Carbon::parse($startDateFilter)->format('d F Y') : '[Not Set]';
        $filterenddate = $endDateFilter ? Carbon::parse($endDateFilter)->format('d F Y') : '[Not Set]';

        $html = view('page/report/generate-pdf.vacation-pdf', compact('data', 'filterstartdate', 'filterenddate'))->render();

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
        $startDate = $request->input('start_date', null); 
        $endDate = $request->input('end_date', null); 
        $employeeId = $request->input('employee_id', null); 
    
        $query = Income::with('employee');
    
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
    
        return view('page/report.income', [
            'data' => $data,
            'employees' => $employees,
            'startDate' => $startDate,
            'endDate' => $endDate, 
            'employee_id' => $employeeId, 
        ]);
    }
    
    
    public function incomeTable(Request $request)
    {
        $query = Income::query()->with('employee');
    
        if ($request->employee_id) {
            $query->where('employee_id', $request->employee_id);
        }
    
        if ($request->startdatefilter) {
            $query->where('period', '>=', $request->startdatefilter);
        }
    
        if ($request->enddatefilter) {
            $query->where('period', '<=', $request->enddatefilter);
        }
    
        return DataTables::of($query)
            ->addColumn('employee_name', function($income) {
                return $income->employee->full_name ?? '-';
            })
            ->addColumn('Nominal', function ($item) {
                return 'Rp. '.number_format($item->nominal, 0, ",", ".");
            })
            ->make(true);
    }
    
    

    public function incomeGeneratePDF(Request $request)
    {
        $startDate = $request->input('startdatefilter', null);
        $endDate = $request->input('enddatefilter', null);
        $employeeId = $request->input('employee_id', null);

        $query = Income::with('employee');
    
        if ($request->employee_id) {
            $query->where('employee_id', $request->employee_id);
        }
    
        if ($request->startdatefilter) {
            $query->where('period', '>=', $request->startdatefilter);
        }
    
        if ($request->enddatefilter) {
            $query->where('period', '<=', $request->enddatefilter);
        }
    
        $data = $query->get();
        $totalNoPayment = 0;
        $totalPayment = 0;
        $totalNominal = 0;
    
        $start_date = $request->startdatefilter ? Carbon::parse($request->startdatefilter)->format('d F Y') : '[Not Set]';
        $end_date = $request->enddatefilter ? Carbon::parse($request->enddatefilter)->format('d F Y') : '[Not Set]';
    
        $view = view('page.report.generate-pdf.income-pdf', [
            'data' => $data,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'totalNominal' => $totalNominal,
            'totalPayment' => $totalPayment,
            'totalNoPayment' => $totalNoPayment,
        ])->render();
    

        $options = new \Dompdf\Options();
        $options->set('isRemoteEnabled', true);
        $dompdf = new \Dompdf\Dompdf($options);
        $dompdf->loadHtml($view);
        $dompdf->setPaper('F4', 'portrait');
        $dompdf->render();
    
        return $dompdf->stream('Income_Report.pdf', ['Attachment' => false]);
    }
    

    public function incomeDetailGeneratePDF(Request $request)
    {
        $startDate = $request->input('startdatefilter', null);
        $endDate = $request->input('enddatefilter', null);
        $employeeId = $request->input('employee_id', null);

        $query = Income::with('employee', 'incomeDetails');

        if ($startDate) {
            $query->where('period', '>=', $startDate);
        }

        if ($endDate) {
            $query->where('period', '<=', $endDate);
        }

        if ($employeeId) {
            $query->where('employee_id', $employeeId);
        }

        $incomes = $query->get() ?? collect();

        $totalNoPayment = 0;
        $totalPayment = 0;
        $totalDetails = 0;
        $incomeDetails = [];

        foreach ($incomes as $income) {
            if ($income->status === 'NO_PAYMENT') {
                $totalNoPayment += $income->nominal;
            } elseif ($income->status === 'PAYMENT') {
                $totalPayment += $income->nominal;
            }

            foreach ($income->incomeDetails as $detail) {
                $incomeDetails[] = $detail;
                $totalDetails += $detail->nominal;
            }
        }

        $startDate = $startDate ? \Carbon\Carbon::parse($startDate)->format('d-m-Y') : 'N/A';
        $endDate = $endDate ? \Carbon\Carbon::parse($endDate)->format('d-m-Y') : 'N/A';

        $view = view('page.report.generate-pdf.income-detail-pdf', [
            'incomes' => $incomes,
            'incomeDetails' => $incomeDetails,
            'totalNoPayment' => $totalNoPayment,
            'totalPayment' => $totalPayment,
            'totalDetails' => $totalDetails,
            'start_date' => $startDate,
            'end_date' => $endDate,
        ])->render();

        $pdfOptions = new \Dompdf\Options();
        $pdfOptions->set('isRemoteEnabled', true);

        $dompdf = new \Dompdf\Dompdf($pdfOptions);
        $dompdf->loadHtml($view);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->stream('Income_Detail_Report.pdf', ['Attachment' => false]);
    }   

    

    

}
