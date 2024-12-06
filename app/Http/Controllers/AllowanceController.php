<?php

namespace App\Http\Controllers;

use App\Models\Allowance;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class AllowanceController extends Controller
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

            return view('page.allowance.index');
        } catch (\Throwable $e) {
            # code...
        }
    }

    public function tableDataAdmin()
    {
        $allowance = Allowance::orderBy('id','desc')->get();
        $counter = 1;
        if (request()->ajax()) {
            $dataTable = Datatables::of($allowance)
                ->addColumn('No', function () use (&$counter) {
                    return $counter++;
                })
                ->addColumn('Name', function ($item) {
                    return $item->name;
                })
                ->addColumn('Desc', function ($item) {
                    return $item->desc;
                })
                ->addColumn('Nominal', function ($item) {
                    return $item->nominal;
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
                        </ul>
                    </div>
                    ';
                    return $button;
                })->rawColumns(['No','Name','Desc','Nominal', 'Action']);
               
            return $dataTable->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            return response()->json(array('status' => 'success', 'msg' => view('page.allowance.create')->render()), 200);
        } catch (\Throwable $th) {
            return response()->json(array('status' => 'error', 'msg' =>$th->getMessage() ), 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'desc' => 'required',
                'name' => 'required',
                'nominal' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(array('status' => 'error','msg' => 'Failed Create Allowance','err'=>'Check Input','valid'=>$validator->errors()), 200);
            }
            else{
                $allowance = Allowance::create($request->except('_token', '_method'));
                $allowance->save();
                return response()->json(array('status' => 'success','msg' => 'Success Create Allowance'), 201);

            }
        } catch (\Throwable $e) {
            return response()->json(array('status' => 'error','msg' => 'Failed Create Allowance','err'=>$e->getMessage()), 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Allowance $allowance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($allowance)
    {
        try {
            $allowance = Allowance::firstWhere('uuid',$allowance);
            return response()->json(array('status' => 'success', 'msg' => view('page.allowance.update',compact('allowance'))->render()), 200);
        } catch (\Throwable $th) {
            return response()->json(array('status' => 'error', 'msg' =>$th->getMessage() ), 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  $allowance)
    {
        try {
            $validator = Validator::make($request->all(), [
                'desc' => 'required',
                'name' => 'required',
                'nominal' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(array('status' => 'error','msg' => 'Failed Update Allowance','err'=>'Check Input','valid'=>$validator->errors()), 200);
            }
            else{
                $allowance = Allowance::firstWhere('uuid',$allowance);
                $allowance = $allowance->fill($request->except('_token', '_method'));
                $allowance->save();
                return response()->json(array('status' => 'success','msg' => 'Success Update Allowance'), 201);

            }
        } catch (\Throwable $e) {
            return response()->json(array('status' => 'error','msg' => 'Failed Update Allowance','err'=>$e->getMessage()), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Allowance $allowance)
    {
        try {
            $allowance = Allowance::firstWhere('uuid', $allowance);
            $allowance->delete();
            return response()->json(['status' => 'success', 'msg' => 'Success Delete Allowance'], 200);
        } catch (\Throwable $e) {
            return response()->json(['status' => 'error', 'msg' => 'Success Delete Allowance', 'err' => $e->getMessage()], 500);
        }
    }
}
