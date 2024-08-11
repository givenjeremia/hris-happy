<?php

namespace App\Http\Controllers;

use App\Models\Departement;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class DepartementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            return view('page.departement.index');
        } catch (\Throwable $e) {
            # code...
        }
    }

    public function tableDataAdmin()
    {
        $departements = Departement::orderBy('id','desc')->get();
        $counter = 1;
        if (request()->ajax()) {
            $dataTable = Datatables::of($departements)
                ->addColumn('No', function () use (&$counter) {
                    return $counter++;
                })
                ->addColumn('Name', function ($item) {
                    return $item->name;
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
                })->rawColumns(['No','Name','Action']);
               
            return $dataTable->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            return response()->json(array('status' => 'success', 'msg' => view('page.departement.modal.create')->render()), 200);
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
            ]);
            if ($validator->fails()) {
                return response()->json(array('status' => 'error','msg' => 'Failed Create Departement','err'=>'Check Input','valid'=>$validator->errors()), 200);
            }
            else{
                $departement = Departement::create($request->except('_token', '_method'));
                $departement->save();
                return response()->json(array('status' => 'success','msg' => 'Success Create Departement'), 201);

            }
        } catch (\Throwable $e) {
            return response()->json(array('status' => 'error','msg' => 'Failed Create Departement','err'=>$e->getMessage()), 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Departement $departement)
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
    public function edit($departement)
    {
        try {
            $departement = Departement::firstWhere('uuid',$departement);
            return response()->json(array('status' => 'success', 'msg' => view('page.departement.modal.update',compact('departement'))->render()), 200);
        } catch (\Throwable $th) {
            return response()->json(array('status' => 'error', 'msg' =>$th->getMessage() ), 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $departement)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(array('status' => 'error','msg' => 'Failed Update Departement','err'=>'Check Input','valid'=>$validator->errors()), 200);
            }
            else{
                $departement = Departement::firstWhere('uuid',$departement);
                $departement = $departement->fill($request->except('_token', '_method'));
                $departement->save();
                return response()->json(array('status' => 'success','msg' => 'Success Update Departement'), 201);
            }
        } catch (\Throwable $e) {
            return response()->json(array('status' => 'error','msg' => 'Failed Update Departement','err'=>$e->getMessage()), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($departement)
    {
        try {
            $departement = Departement::firstWhere('uuid', $departement);
            $departement->delete();
            return response()->json(['status' => 'success', 'msg' => 'Success Delete Departement'], 200);
        } catch (\Throwable $e) {
            return response()->json(['status' => 'error', 'msg' => 'Success Delete Departement', 'err' => $e->getMessage()], 500);
        }
    }
}
