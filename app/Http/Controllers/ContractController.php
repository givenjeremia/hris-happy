<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use Illuminate\Http\Request;

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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            return view('page.contract.create');
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
            return view('page.contract.update',compact('contract'));
        } catch (\Throwable $e) {
            # code...
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Contract $contract)
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
