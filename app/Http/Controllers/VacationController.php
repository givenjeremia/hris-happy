<?php

namespace App\Http\Controllers;

use App\Models\Vacation;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class VacationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            return view('page.vacation.index');
        } catch (\Throwable $e) {
            # code...
        }
    }

    public function tableDataAdmin()
    {
        $user = Auth::user();
        if($user->getRoleNames()->first() == 'admin') {
            $vacation = Vacation::orderBy('id','desc')->get();
        }
        else{
            $vacation = Vacation::where('employee_id',$user->employee->id)->get();
        }
       
        $counter = 1;
        if (request()->ajax()) {
            $dataTable = Datatables::of($vacation)
                ->addColumn('No', function () use (&$counter) {
                    return $counter++;
                })
                ->addColumn('Name', function ($item) {
                    return 'Name Of Employee';
                })
                ->addColumn('Start Date', function ($item) {
                    return $item->start_date;
                })
                ->addColumn('End Date', function ($item) {
                    return $item->end_date;
                })
                ->addColumn('Subject', function ($item) {
                    return $item->subject;
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
                'departement' => 'required',
                'date'  => 'required',
                'subject' => 'required',
                'information' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(array('status' => 'error','msg' => 'Failed Create Vacation','err'=>'Check Input','valid'=>$validator->errors()), 200);
            }
            else{
                $user = Auth::user();
          
                $vacation = Vacation::create($request->except('_token', '_method'));
                $vacation->employee_id = $user->employee->id;

                // Get Date Start And End
                [$startDate, $endDate] = explode(' - ', $request->get('date'));
                $start = Carbon::parse($startDate);
                $end = Carbon::parse($endDate);
                $vacation->start_date = $start;
                $vacation->end_date = $end;

                $vacation->status = Vacation::STATUS_PENDING;
                
                $vacation->save();
                return response()->json(array('status' => 'success','msg' => 'Success Create Vacation'), 201);

            }
        } catch (\Throwable $e) {
            return response()->json(array('status' => 'error','msg' => 'Failed Create Vacation','err'=>$e->getMessage()), 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Vacation $vacation)
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
    public function edit(Vacation $vacation)
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
    public function update(Request $request, $vacation)
    {
        try {
            $validator = Validator::make($request->all(), [
                'departement' => 'required',
                'date'  => 'required',
                'subject' => 'required',
                'information' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(array('status' => 'error','msg' => 'Failed Create Vacation','err'=>'Check Input','valid'=>$validator->errors()), 200);
            }
            else{
                $user = Auth::user();
          
                $vacation = Vacation::firstWhere('uuid',$vacation);
                $vacation = $vacation->fill($request->except('_token', '_method'));
                $vacation->employee_id = $user->employee->id;

                // Get Date Start And End
                [$startDate, $endDate] = explode(' - ', $request->get('date'));
                $start = Carbon::parse($startDate);
                $end = Carbon::parse($endDate);
                $vacation->start_date = $start;
                $vacation->end_date = $end;

                $vacation->status = Vacation::STATUS_PENDING;
                
                $vacation->save();
                return response()->json(array('status' => 'success','msg' => 'Success Create Vacation'), 201);

            }
        } catch (\Throwable $e) {
            return response()->json(['status' => 'error', 'msg' => 'Error Update Vacation', 'err' => $e->getMessage()], 500);
        }
           
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($vacation)
    {
        try {
            $vacation = Vacation::firstWhere('uuid', $vacation);
            $vacation->delete();
            return response()->json(['status' => 'success', 'msg' => 'Success Delete Vacation'], 200);
        } catch (\Throwable $e) {
            return response()->json(['status' => 'error', 'msg' => 'Error Delete Vacation', 'err' => $e->getMessage()], 500);
        }
    }
}
