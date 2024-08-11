<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Employee;
use App\Models\Posision;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            return view('page.employee.index');
        } catch (\Throwable $e) {
            # code...
        }
    }

    public function tableDataAdmin()
    {
        $employee = Employee::orderBy('id','desc')->get();
        $counter = 1;
        if (request()->ajax()) {
            $dataTable = Datatables::of($employee)
                ->addColumn('No', function () use (&$counter) {
                    return $counter++;
                })
                ->addColumn('Client', function ($item) {
                    return $item->client_id;
                })
                ->addColumn('Full Name', function ($item) {
                    return $item->full_name;
                })
                ->addColumn('NIK', function ($item) {
                    return $item->nik;
                })
                ->addColumn('Date Of Birth', function ($item) {
                    return $item->date_of_birth;
                })
                ->addColumn('Address', function ($item) {
                    return $item->address;
                })
                ->addColumn('Bank Account Name', function ($item) {
                    return $item->bank_account_name;
                })
                ->addColumn('Bank Account Number', function ($item) {
                    return $item->bank_account_number;
                })
                ->addColumn('Phone Number', function ($item) {
                    return $item->phone_number;
                })
                ->addColumn('Code PTKP', function ($item) {
                    return $item->code_ptkp;
                })
                ->addColumn('Action', function ($item)  {
                    $encryptedIdString = "'" . strval($item->uuid) . "'";
                    $url = '';
                    if ($item->getMedia('document')->first() ){
                        $url = $item->getMedia('document')->first()->getUrl();
                        $url = $this->convertUrlMedia($url);
                    }
                    $button = 
                    '
                    <div class="dropdown">
                        <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                            class="btn btn-secondary w-100">Action</a>
                        <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow" style="left: 0px; right: inherit;">
                            <li><a href="'.$url.'" class="dropdown-item" target="_blank">Lihat Dokumen</a></li>
                            <li><a href="'.route('contracts.edit',$item->uuid).'" class="dropdown-item">Ubah</a></li>
                            <li><a href="#" onclick="deleteData(' . $encryptedIdString . ')"  class="dropdown-item">Hapus</a></li>
                        </ul>
                    </div>
                    ';
                    return $button;
                })->rawColumns(['No','Client','Full Name','NIK','Date Of Birth','Address','Bank Account Name','Bank Account Number','Phone Number','Code PTKP', 'Action']);
               
            return $dataTable->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $clients = Client::all();
            $posisions = Posision::all();
            return view('page.employee.create',compact('posisions', 'clients'));
        } catch (\Throwable $e) {
            # code...
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            # code...
        } catch (\Throwable $e) {
            # code...
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee)
    {
        try {
            # code...
        } catch (\Throwable $e) {
            # code...
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Employee $employee)
    {
        try {
            # code...
        } catch (\Throwable $e) {
            # code...
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Employee $employee)
    {
        try {
            # code...
        } catch (\Throwable $e) {
            # code...
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($employee)
    {
        try {
            $employee = Employee::firstWhere('uuid', $employee);
            $employee->delete();
            return response()->json(['status' => 'success', 'msg' => 'Success Delete Employee'], 200);
        } catch (\Throwable $e) {
            return response()->json(['status' => 'error', 'msg' => 'Success Delete Employee', 'err' => $e->getMessage()], 500);
        }
    }
}
