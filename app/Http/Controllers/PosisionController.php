<?php

namespace App\Http\Controllers;

use App\Models\Departement;
use App\Models\Posision;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class PosisionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            return view('page.posision.index');
        } catch (\Throwable $e) {
            # code...
        }
    }

    public function tableDataAdmin()
    {
        $posision = Posision::orderBy('id','desc')->get();
        $counter = 1;
        if (request()->ajax()) {
            $dataTable = Datatables::of($posision)
                ->addColumn('No', function () use (&$counter) {
                    return $counter++;
                })
                ->addColumn('Departement', function ($item) {
                    return $item->departement->name;
                })
                ->addColumn('Name', function ($item) {
                    return $item->name;
                })
                ->addColumn('Salary', function ($item) {
                    return $item->salary;
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
            $departements = Departement::all();
            return response()->json(array('status' => 'success', 'msg' => view('page.posision.modal.create',compact('departements'))->render()), 200);
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
                'departement' => 'required',
                'name' => 'required',
                'salary' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(array('status' => 'error','msg' => 'Failed Create Posision','err'=>'Check Input','valid'=>$validator->errors()), 200);
            }
            else{
                $departement = Departement::firstWhere('uuid',$request->get('departement'));
                $posision = Posision::create($request->except('_token', '_method'));
                $posision->departement_id = $departement->id;
                $posision->save();
                return response()->json(array('status' => 'success','msg' => 'Success Create Posision'), 201);

            }
        } catch (\Throwable $e) {
            return response()->json(array('status' => 'error','msg' => 'Failed Create Posision','err'=>$e->getMessage()), 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Posision $posision)
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
    public function edit($posision)
    {
        try {
            $departements = Departement::all();
            $posision = Posision::firstWhere('uuid',$posision);
            return response()->json(array('status' => 'success', 'msg' => view('page.posision.modal.update',compact('departements','posision'))->render()), 200);
        } catch (\Throwable $th) {
            return response()->json(array('status' => 'error', 'msg' =>$th->getMessage() ), 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $posision)
    {
        try {
            $validator = Validator::make($request->all(), [
                'departement' => 'required',
                'name' => 'required',
                'salary' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(array('status' => 'error','msg' => 'Failed Update Posision','err'=>'Check Input','valid'=>$validator->errors()), 200);
            }
            else{
                $departement = Departement::firstWhere('uuid',$request->get('departement'));
                $posision = Posision::firstWhere('uuid',$posision);
                $posision = $posision->fill($request->except('_token', '_method'));
                $posision->departement_id = $departement->id;
                $posision->save();
                return response()->json(array('status' => 'success','msg' => 'Success Update Posision'), 201);

            }
        } catch (\Throwable $e) {
            return response()->json(array('status' => 'error','msg' => 'Failed Update Posision','err'=>$e->getMessage()), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($posision)
    {
        try {
            $posision = Posision::firstWhere('uuid', $posision);
            $posision->delete();
            return response()->json(['status' => 'success', 'msg' => 'Success Delete Posision'], 200);
        } catch (\Throwable $e) {
            return response()->json(['status' => 'error', 'msg' => 'Success Delete Posision', 'err' => $e->getMessage()], 500);
        }
    }
}
