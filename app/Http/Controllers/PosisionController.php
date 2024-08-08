<?php

namespace App\Http\Controllers;

use App\Models\Posision;
use Illuminate\Http\Request;

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
            # code...
        } catch (\Throwable $e) {
            # code...
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
    public function edit(Posision $posision)
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
    public function update(Request $request, Posision $posision)
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
