<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class ShiftController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            return view('page.shift.index');
        } catch (\Throwable $e) {
            # code...
        }
    }

    public function tableDataAdmin()
    {
        $shifts = Shift::orderBy('id','desc')->get();
        $counter = 1;
        if (request()->ajax()) {
            $dataTable = Datatables::of($shifts)
                ->addColumn('No', function () use (&$counter) {
                    return $counter++;
                })
                ->addColumn('Name', function ($item) {
                    return $item->name;
                })
                ->addColumn('Time In', function ($item) {
                    return $item->time_in;
                })
                ->addColumn('Time Out', function ($item) {
                    return $item->time_out;
                })
                ->addColumn('Action', function ($item)  {
                    $encryptedIdString = "'" . strval($item->uuid) . "'";
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
                })->rawColumns(['No','Name','Time In','Time Out', 'Action']);
               
            return $dataTable->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            return response()->json(array('status' => 'success', 'msg' => view('page.shift.modal.create' )->render()), 200);
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
                'name' => 'required',
                'time_in' => 'required',
                'time_out' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(array('status' => 'error','msg' => 'Failed Create Shif','err'=>'Check Input','valid'=>$validator->errors()), 400);
            }
            else{
                $shift = Shift::create($request->except('_token', '_method'));
                $shift->save();
                return response()->json(array('status' => 'success','msg' => 'Success Create Shift'), 201);

            }
        } catch (\Throwable $e) {
            return response()->json(array('status' => 'error','msg' => 'Failed Create Shift','err'=>$e->getMessage()), 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Shift $shift)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Shift $shift)
    {
        try {
            return response()->json(array('status' => 'success', 'msg' => view('page.shift.modal.update')->render()), 200);
        } catch (\Throwable $th) {
            return response()->json(array('status' => 'error', 'msg' =>$th->getMessage() ), 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $shift)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'time_in' => 'required',
                'time_out' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(array('status' => 'error','msg' => 'Failed Update Shift','err'=>'Check Input','valid'=>$validator->errors()), 200);
            }
            else{
                $shift = Shift::firstWhere('uuid',$shift);
                $shift = $shift->fill($request->except('_token', '_method'));
                $shift->save();
                return response()->json(array('status' => 'success','msg' => 'Success Update Shift'), 200);

            }
        } catch (\Throwable $e) {
            return response()->json(array('status' => 'error','msg' => 'Failed Update Shift','err'=>$e->getMessage()), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($shift)
    {
        try {
            $shift = Shift::firstWhere('uuid', $shift);
            $shift->delete();
            return response()->json(['status' => 'success', 'msg' => 'Success Delete Shift'], 200);
        } catch (\Throwable $e) {
            return response()->json(['status' => 'error', 'msg' => 'Success Delete Shift', 'err' => $e->getMessage()], 500);
        }
    }
}
