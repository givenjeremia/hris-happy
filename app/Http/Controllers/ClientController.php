<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            return view('page.client.index');
        } catch (\Throwable $e) {
            # code...
        }
    }

    public function tableDataAdmin()
    {
        $clients = Client::orderBy('id','desc')->get();
        $counter = 1;
        if (request()->ajax()) {
            $dataTable = Datatables::of($clients)
                ->addColumn('No', function () use (&$counter) {
                    return $counter++;
                })
                ->addColumn('Name', function ($item) {
                    return $item->name;
                })
                ->addColumn('Address', function ($item) {
                    return $item->address;
                })
                ->addColumn('Email', function ($item) {
                    return $item->email;
                })
                ->addColumn('Latitude', function ($item) {
                    return $item->latitude;
                })
                ->addColumn('Longitude', function ($item) {
                    return $item->longitude;
                })
                ->addColumn('Action', function ($item)  {
                    $encryptedIdString = "'" . strval($item->uuid) . "'";
                    $button = 
                    '
                    <div class="dropdown">
                        <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                            class="btn btn-secondary w-100">Action</a>
                        <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow" style="left: 0px; right: inherit;">
                            <li><a href="'.route('clients.edit',$item->uuid).'" class="dropdown-item">Ubah</a></li>
                            <li><a href="#" onclick="deleteData(' . $encryptedIdString . ')"  class="dropdown-item">Hapus</a></li>
                
                        </ul>
                    </div>
                    ';
                    return $button;
                })->rawColumns(['No','Name','Address','Email','Latitude','Longitude', 'Action']);
               
            return $dataTable->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            return view('page.client.create');
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
                'name' => 'required',
                'address' => 'required',
                'email' => 'required',
                'latitude' => 'required',
                'longitude' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(array('status' => 'error','msg' => 'Failed Create Client','err'=>'Check Input','valid'=>$validator->errors()), 200);
            }
            else{
                $client = Client::create($request->except('_token', '_method'));
                $client->save();
                return response()->json(array('status' => 'success','msg' => 'Success Create Client'), 201);

            }
        } catch (\Throwable $e) {
            return response()->json(array('status' => 'error','msg' => 'Failed Create Client','err'=>$e->getMessage()), 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client)
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
    public function edit($client)
    {
        try {
            $client = Client::firstWhere('uuid', $client);
            return view('page.client.update',compact('client'));
        } catch (\Throwable $e) {
            # code...
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $client)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'address' => 'required',
                'email' => 'required',
                'latitude' => 'required',
                'longitude' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(array('status' => 'error','msg' => 'Failed Update Client','err'=>'Check Input','valid'=>$validator->errors()), 200);
            }
            else{
                $client = Client::firstWhere('uuid',$client);
                $client = $client->fill($request->except('_token', '_method'));
                $client->save();
                return response()->json(array('status' => 'success','msg' => 'Success Update Client'), 201);
            }
        } catch (\Throwable $e) {
            return response()->json(array('status' => 'error','msg' => 'Failed Update Client','err'=>$e->getMessage()), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($client)
    {
        try {
            $client = Client::firstWhere('uuid', $client);
            $client->delete();
            return response()->json(['status' => 'success', 'msg' => 'Success Delete Client'], 200);
        } catch (\Throwable $e) {
            return response()->json(['status' => 'error', 'msg' => 'Success Delete Client', 'err' => $e->getMessage()], 500);
        }
    }
}
