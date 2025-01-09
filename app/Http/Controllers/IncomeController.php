<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Income;
use App\Models\IncomeDetail;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Dompdf\Dompdf;

class IncomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            // If Return With Ajax
            if (request()->ajax()) {
                return $this->tableDataAdmin();
            }

            return view('page.income.index');
        } catch (\Throwable $e) {
            # code...
        }
    }

    public function tableDataAdmin()
    {
        $income = Income::orderBy('id','desc')->get();
        $counter = 1;
        if (request()->ajax()) {
            $dataTable = Datatables::of($income)
                ->addColumn('No', function () use (&$counter) {
                    return $counter++;
                })
                ->addColumn('Name Employee', function ($item) {
                    return $item->employee->full_name;
                })
                ->addColumn('Nominal', function ($item) {
                    return 'Rp. '.number_format($item->nominal, 0, ",", ".");
                })
                ->addColumn('Period', function ($item) {
                    return $item->period;
                })
                ->addColumn('Status', function ($item) {
                    return $item->status;
                })
                ->addColumn('Action', function ($item)  {
                    $encryptedIdString = "'" . $item->uuid . "'";
                    $button = 
                    '
                    <div class="dropdown">
                        <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                            class="btn btn-secondary w-100">Action</a>
                        <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow" style="left: 0px; right: inherit;">
                            <li><a href="#" onclick="detailDataData(' . $encryptedIdString . ')"  class="dropdown-item">Detail</a></li>
                        </ul>
                    </div>
                    ';
                    return $button;
                })->rawColumns(['No',"Name Employee",'Nominal','Period','Status', 'Action']);
               
            return $dataTable->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($income)
    {
        try {
            $income = Income::firstWhere('uuid',$income);
            
            return response()->json([
                'status' => 'success', 
                'msg' => view('page.income.modal.detail', compact('income'))->render()
            ], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'msg' => $th->getMessage()], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Income $income)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Income $income)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Income $income)
    {
        //
    }

    public function generateGajiAll(Request $request)
    {
        try {
            $employee = Employee::all();
            $count =  0;
            foreach ($employee as $key => $value) {
                $income = Income::generateSalary($value->id);
                $count += 1;
            }
            return response()->json(['status'=>'success','msg' => 'Salary generated successy', 'count' => $count], 201);
            
        } catch (\Throwable $e) {
            return response()->json(['status'=>'failed','msg' => 'Failed to generate salary', 'error' => $e->getMessage()], 500);
        }
    }

    public function generatePayslipPDF($incomeId)
    {
        $income = Income::with(['employee', 'incomeDetail'])->findOrFail($incomeId);    
        $data = [
            'employeeName' => $income->employee->full_name,
            'nominal' => number_format($income->nominal, 0, ',', '.'), 
            'period' => $income->period,
            'status' => $income->status,
            'details' => $income->incomeDetail->map(function ($detail) {
                return [
                    'category' => $detail->category,
                    'type' => $detail->type,
                    'nominal' => number_format($detail->nominal, 0, ',', '.'), 
                    'desc' => $detail->desc,
                ];
            })->toArray(),
        ];
    

        $html = view('page/income/modal/slip-gaji', $data)->render();    
        $options = new \Dompdf\Options();
        $options->set('isRemoteEnabled', true); 
        $dompdf = new \Dompdf\Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
    
        return response($dompdf->output(), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="Slip_Gaji.pdf"');
    }
    
    
    


}
