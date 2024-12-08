<?php

namespace App\Http\Controllers;

use App\Models\Bpjs;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;


class BpjsController extends Controller
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

            return view('page.bpjs.index');
        } catch (\Throwable $e) {
            # code...
        }
    }

    public function tableDataAdmin()
    {
        $bpjs = Bpjs::orderBy('id','desc')->get();
        $counter = 1;
        if (request()->ajax()) {
            $dataTable = Datatables::of($bpjs)
                ->addColumn('No', function () use (&$counter) {
                    return $counter++;
                })
                ->addColumn('Nominal', function ($item) {
                    return $item->nominal;
                })
                ->addColumn('Type', function ($item) {
                    return $item->type;
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
                })->rawColumns(['No',"Type",'Nominal', 'Action']);
               
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
    public function show(Bpjs $bpjs)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Bpjs $bpjs)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Bpjs $bpjs)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bpjs $bpjs)
    {
        //
    }
}
