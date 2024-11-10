<?php

namespace App\Http\Controllers;

use App\Models\Overtime;
use App\Models\Vacation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class OvertimeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            return view('page.overtime.index');
        } catch (\Throwable $e) {
            # code...
        }
    }

    public function tableDataAdmin()
    {
        $user = Auth::user();
        if($user->getRoleNames()->first() == 'admin') {
            $overtime = Overtime::orderBy('id','desc')->get();
        }
        else{
            $overtime = Overtime::where('employee_id',$user->employee->id)->get();
        }
       
        $counter = 1;
        if (request()->ajax()) {
            $dataTable = Datatables::of($overtime)
                ->addColumn('No', function () use (&$counter) {
                    return $counter++;
                })
                ->addColumn('Name', function ($item) {
                    return 'Name Of Employee';
                })
                ->addColumn('Date', function ($item) {
                    return $item->date;
                })
                ->addColumn('Long Overtime', function ($item) {
                    return $item->long_overtime;
                })
                ->addColumn('Information', function ($item) {
                    return $item->information;
                })
                ->addColumn('Action', function ($item)  {
                    $encryptedIdString = "'" . $item->uuid . "'";
                    $button = 
                    '
                    <div class="dropdown">
                        <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                            class="btn btn-secondary w-100">Action</a>
                        <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow" style="left: 0px; right: inherit;">
                            <li><a href="#" onclick="updateData(' . $encryptedIdString . ')"  class="dropdown-item">Ubah</a></li>
                            <li><a href="#" onclick="deleteData(' . $encryptedIdString . ')"  class="dropdown-item">Hapus</a></li>
                        </ul>
                    </div>
                    ';
                    return $button;
                })->rawColumns(['No','Departement','Name','Salary', 'Action']);
               
            return $dataTable->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            # code...
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
            $validator = Validator::make($request->all(), [
                'date' => 'required',
                'long_overtime' => 'required',
                'information' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(array('status' => 'error','msg' => 'Failed Create Overtime','err'=>'Check Input','valid'=>$validator->errors()), 200);
            }
            else{
                $user = Auth::user();

                $overtime = Overtime::create($request->except('_token', '_method'));
                $overtime->employee_id = $user->employee->id;
                $overtime->save();

                return response()->json(array('status' => 'success','msg' => 'Success Create Overtime'), 201);

            }
        } catch (\Throwable $e) {
            return response()->json(array('status' => 'error','msg' => 'Failed Create Overtime','err'=>$e->getMessage()), 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Overtime $overtime)
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
    public function edit(Overtime $overtime)
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
    public function update(Request $request, $overtime)
    {
        try {
            $validator = Validator::make($request->all(), [
                'date' => 'required',
                'long_overtime' => 'required',
                'information' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(array('status' => 'error','msg' => 'Failed Update Overtime','err'=>'Check Input','valid'=>$validator->errors()), 200);
            }
            else{
                $user = Auth::user();

                $overtime = Overtime::firstWhere('uuid',$overtime);
                $overtime = $overtime->fill($request->except('_token', '_method'));
                $overtime->employee_id = $user->employee->id;
                $overtime->save();

                return response()->json(array('status' => 'success','msg' => 'Success Update Overtime'), 201);

            }
        } catch (\Throwable $e) {
            return response()->json(array('status' => 'error','msg' => 'Failed Update Posision','err'=>$e->getMessage()), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($overtime)
    {
        try {
            $overtime = Overtime::firstWhere('uuid', $overtime);
            $overtime->delete();
            return response()->json(['status' => 'success', 'msg' => 'Success Delete Overtime'], 200);
        } catch (\Throwable $e) {
            return response()->json(['status' => 'error', 'msg' => 'Success Delete Overtime', 'err' => $e->getMessage()], 500);
        }
    }
}
