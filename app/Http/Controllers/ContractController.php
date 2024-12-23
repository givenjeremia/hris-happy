<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Contract;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class ContractController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            return view('page.contract.index');
        } catch (\Throwable $e) {
            # code...
        }
    }

    public function tableDataAdmin()
    {
        $contracts = Contract::with('client')->orderBy('id','desc')->get();
        $counter = 1;
        if (request()->ajax()) {
            $dataTable = Datatables::of($contracts)
                ->addColumn('No', function () use (&$counter) {
                    return $counter++;
                })
                ->addColumn('Client', function ($item) {
                    return $item->client->name ?? '-';
                })
                ->addColumn('Start Date', function ($item) {
                    return $item->start_date;
                })
                ->addColumn('End Date', function ($item) {
                    return $item->end_date;
                })
                ->addColumn('Description', function ($item) {
                    return $item->description;
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
                })->rawColumns(['No','Client','Start Date','End Date','Description', 'Action']);
               
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
            return view('page.contract.create',compact('clients'));
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
                'client' => 'required',
                'start_date' => 'required',
                'end_date' => 'required',
                'description' => 'required',
                'document' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(array('status' => 'error','msg' => 'Failed Create Contract','err'=>'Check Input','valid'=>$validator->errors()), 200);
            }
            else{
                $client = Client::firstWhere('uuid', $request->input('client'));
                $contract = Contract::create($request->except('_token', '_method'));
                $contract->client_id = $client->id;
                $contract->save();
                if($request->file('document')){
                    $contract->addMedia($request->file('document'))->toMediaCollection('document');
                }
                return response()->json(array('status' => 'success','msg' => 'Success Create Contract'), 201);

            }
        } catch (\Throwable $e) {
            return response()->json(array('status' => 'error','msg' => 'Failed Create Contract','err'=>$e->getMessage()), 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Contract $contract)
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
    public function edit($contract)
    {
        try {
            $contract = Contract::firstWhere('uuid',$contract);
            $url = '';
            if ($contract->getMedia('document')->first() ){
                $url = $contract->getMedia('document')->first()->getUrl();
                $url = $this->convertUrlMedia($url);
            }
            $clients = Client::all();
            return view('page.contract.update',compact('contract','url','clients'));
        } catch (\Throwable $e) {
            # code...
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $contract)
    {
        try {
            $validator = Validator::make($request->all(), [
                'client' => 'required',
                'start_date' => 'required',
                'end_date' => 'required',
                'description' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(array('status' => 'error','msg' => 'Failed Update Contract','err'=>'Check Input','valid'=>$validator->errors()), 200);
            }
            else{
                $client = Client::firstWhere('uuid', $request->input('client'));
                $contract = Contract::firstWhere('uuid',$contract);
                $contract = $contract->fill($request->except('_token', '_method'));
                $contract->client_id = $client->id;
                $contract->save();
                if($request->file('document')){
                    $contract->addMedia($request->file('document'))->toMediaCollection('document');
                }
                return response()->json(array('status' => 'success','msg' => 'Success Update Contract'), 201);
                
            }
        } catch (\Throwable $e) {
            return response()->json(array('status' => 'error','msg' => 'Failed Update Contract','err'=>$e->getMessage()), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($contract)
    {
        try {
            $contract = Contract::firstWhere('uuid', $contract);
            $contract->delete();
            return response()->json(['status' => 'success', 'msg' => 'Success Delete Contract'], 200);
        } catch (\Throwable $e) {
            return response()->json(['status' => 'error', 'msg' => 'Success Delete Contract', 'err' => $e->getMessage()], 500);
        }
    }
}
